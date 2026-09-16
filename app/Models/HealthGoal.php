<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthGoal extends Model
{
    protected $fillable = ['user_id', 'goal_type', 'title', 'target_weight_kg', 'target_date', 'started_at', 'ended_at'];

    protected function casts(): array
    {
        return ['target_weight_kg' => 'decimal:2', 'target_date' => 'date', 'started_at' => 'datetime', 'ended_at' => 'datetime'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function exercisePlanVersions(): HasMany { return $this->hasMany(ExercisePlanVersion::class); }
    public function nutritionPlanVersions(): HasMany { return $this->hasMany(NutritionPlanVersion::class); }
}
