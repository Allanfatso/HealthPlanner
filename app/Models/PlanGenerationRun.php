<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanGenerationRun extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['user_id', 'plan_type', 'provider', 'provider_model', 'status', 'requested_at', 'completed_at', 'request_snapshot', 'response_snapshot', 'failure_message'];

    protected function casts(): array { return ['requested_at' => 'datetime', 'completed_at' => 'datetime', 'request_snapshot' => 'array', 'response_snapshot' => 'array']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function exercisePlanVersions(): HasMany { return $this->hasMany(ExercisePlanVersion::class, 'generation_run_id'); }
    public function nutritionPlanVersions(): HasMany { return $this->hasMany(NutritionPlanVersion::class, 'generation_run_id'); }
}
