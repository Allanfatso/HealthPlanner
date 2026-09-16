<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSessionExercise extends Model
{
    public $timestamps = false;
    protected $fillable = ['workout_session_id', 'exercise_id', 'sort_order', 'exercise_name_snapshot', 'planned_sets', 'planned_repetitions', 'planned_duration_minutes', 'completed', 'notes'];
    protected function casts(): array { return ['sort_order' => 'integer', 'planned_sets' => 'integer', 'planned_duration_minutes' => 'integer', 'completed' => 'boolean']; }
    public function session(): BelongsTo { return $this->belongsTo(WorkoutSession::class, 'workout_session_id'); }
    public function exercise(): BelongsTo { return $this->belongsTo(Exercise::class); }
    public function sets(): HasMany { return $this->hasMany(WorkoutSet::class); }
}
