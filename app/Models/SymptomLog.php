<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SymptomLog extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['user_id', 'health_condition_id', 'occurred_at', 'symptom_name', 'severity', 'notes'];

    protected function casts(): array { return ['occurred_at' => 'datetime', 'severity' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function healthCondition(): BelongsTo { return $this->belongsTo(HealthCondition::class); }
}
