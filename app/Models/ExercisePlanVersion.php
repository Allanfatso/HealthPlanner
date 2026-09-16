<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExercisePlanVersion extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['exercise_plan_id', 'health_goal_id', 'generation_run_id', 'version_number', 'fitness_level', 'days_per_week', 'session_duration_minutes', 'plan_duration_weeks', 'valid_from', 'valid_to', 'change_reason'];
    protected function casts(): array { return ['version_number' => 'integer', 'days_per_week' => 'integer', 'session_duration_minutes' => 'integer', 'plan_duration_weeks' => 'integer', 'valid_from' => 'datetime', 'valid_to' => 'datetime']; }
    public function plan(): BelongsTo { return $this->belongsTo(ExercisePlan::class, 'exercise_plan_id'); }
    public function healthGoal(): BelongsTo { return $this->belongsTo(HealthGoal::class); }
    public function generationRun(): BelongsTo { return $this->belongsTo(PlanGenerationRun::class); }
    public function exercises(): HasMany { return $this->hasMany(Exercise::class); }
    public function workoutSessions(): HasMany { return $this->hasMany(WorkoutSession::class); }
}
