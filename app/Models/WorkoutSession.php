<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSession extends Model
{
    protected $fillable = ['user_id', 'exercise_plan_version_id', 'started_at', 'completed_at', 'status', 'perceived_exertion', 'notes'];
    protected function casts(): array { return ['started_at' => 'datetime', 'completed_at' => 'datetime', 'perceived_exertion' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function planVersion(): BelongsTo { return $this->belongsTo(ExercisePlanVersion::class, 'exercise_plan_version_id'); }
    public function exercises(): HasMany { return $this->hasMany(WorkoutSessionExercise::class); }
}
