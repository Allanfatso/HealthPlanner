<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = ['user_id', 'exercise_plan_id', 'exercise_plan_version_id', 'day_label', 'name', 'duration_minutes', 'repetitions', 'sets', 'equipment', 'notes'];
    protected function casts(): array { return ['duration_minutes' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function plan(): BelongsTo { return $this->belongsTo(ExercisePlan::class, 'exercise_plan_id'); }
    public function planVersion(): BelongsTo { return $this->belongsTo(ExercisePlanVersion::class, 'exercise_plan_version_id'); }
    public function sessionExercises(): HasMany { return $this->hasMany(WorkoutSessionExercise::class); }
}
