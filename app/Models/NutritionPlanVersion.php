<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NutritionPlanVersion extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['nutrition_plan_id', 'health_goal_id', 'generation_run_id', 'version_number', 'daily_activity_level', 'daily_calorie_target', 'valid_from', 'valid_to', 'change_reason'];
    protected function casts(): array { return ['version_number' => 'integer', 'daily_calorie_target' => 'integer', 'valid_from' => 'datetime', 'valid_to' => 'datetime']; }
    public function plan(): BelongsTo { return $this->belongsTo(NutritionPlan::class, 'nutrition_plan_id'); }
    public function healthGoal(): BelongsTo { return $this->belongsTo(HealthGoal::class); }
    public function generationRun(): BelongsTo { return $this->belongsTo(PlanGenerationRun::class); }
    public function meals(): HasMany { return $this->hasMany(Meal::class); }
}
