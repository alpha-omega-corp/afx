<?php

namespace App\Models;

use App\Interfaces\ILocale;
use App\Traits\HasLocaleScope;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model implements ILocale
{
    use HasFactory;
    use HasLocaleScope;

    protected $fillable = [
        'image',
    ];

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }

    public function locale(): HasOne
    {
        return $this->hasOne(PageLocale::class);
    }
}
