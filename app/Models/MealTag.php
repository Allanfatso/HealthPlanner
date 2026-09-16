<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MealTag extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function meals(): BelongsToMany { return $this->belongsToMany(Meal::class, 'meal_tag_assignments'); }
}
