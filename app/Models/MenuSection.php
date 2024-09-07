<?php

namespace App\Models;

use Database\Factories\MenuSectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
    ];

    protected static function newFactory(): MenuSectionFactory
    {
        return MenuSectionFactory::new();
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_section_id', 'id');
    }
}
