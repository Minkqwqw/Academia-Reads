<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /**
     * Get the books for the category.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
