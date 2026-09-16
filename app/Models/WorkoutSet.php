<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutSet extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['workout_session_exercise_id', 'set_number', 'repetitions', 'weight_kg', 'duration_seconds', 'completed'];
    protected function casts(): array { return ['set_number' => 'integer', 'repetitions' => 'integer', 'weight_kg' => 'decimal:2', 'duration_seconds' => 'integer', 'completed' => 'boolean']; }
    public function sessionExercise(): BelongsTo { return $this->belongsTo(WorkoutSessionExercise::class, 'workout_session_exercise_id'); }
}
