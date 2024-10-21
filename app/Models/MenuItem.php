<?php

namespace App\Models;

use App\Traits\HasLocaleScope;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;
    use HasLocaleScope;

    protected $fillable = [
        'title',
        'description',
        'price',
        'menu_section_id',
    ];

    protected static function newFactory(): MenuItemFactory
    {
        return MenuItemFactory::new();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class);
    }

}
