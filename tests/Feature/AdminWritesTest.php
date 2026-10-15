<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * The page and gallery writes sat outside the admin gate: anybody could
 * rewrite a page or upload any file into public storage.
 */
class AdminWritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_cannot_upload_to_a_gallery(): void
    {
        $gallery = Gallery::query()->create(['name' => 'restaurant']);

        $this->post(route('gallery.store', $gallery), [
            'items' => [UploadedFile::fake()->image('a.jpg')],
        ])->assertRedirect();

        $this->assertDatabaseCount('gallery_items', 0);
    }

    public function test_a_visitor_cannot_delete_from_a_gallery(): void
    {
        $this->delete(route('gallery.delete'), ['items' => [1]])->assertRedirect();
    }

    public function test_an_admin_cannot_upload_a_file_that_is_not_an_image(): void
    {
        $gallery = Gallery::query()->create(['name' => 'restaurant']);

        $this->actingAs(User::factory()->create())
            ->post(route('gallery.store', $gallery), [
                'items' => [UploadedFile::fake()->create('shell.php', 1, 'text/x-php')],
            ])->assertSessionHasErrors('items.0');

        $this->assertDatabaseCount('gallery_items', 0);
    }
}
