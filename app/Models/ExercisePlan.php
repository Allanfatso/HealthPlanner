<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExercisePlan extends Model
{
    protected $fillable = ['user_id', 'fitness_level', 'days_per_week', 'session_duration_minutes', 'plan_duration_weeks'];
    protected function casts(): array { return ['days_per_week' => 'integer', 'session_duration_minutes' => 'integer', 'plan_duration_weeks' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function versions(): HasMany { return $this->hasMany(ExercisePlanVersion::class); }
    public function exercises(): HasMany { return $this->hasMany(Exercise::class); }
}
