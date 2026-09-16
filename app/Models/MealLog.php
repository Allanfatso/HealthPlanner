<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealLog extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['user_id', 'meal_id', 'consumed_at', 'meal_type', 'servings', 'calories_consumed', 'notes'];
    protected function casts(): array { return ['consumed_at' => 'datetime', 'servings' => 'decimal:2', 'calories_consumed' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function meal(): BelongsTo { return $this->belongsTo(Meal::class); }
}
