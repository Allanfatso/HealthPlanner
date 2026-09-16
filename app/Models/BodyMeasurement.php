<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyMeasurement extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'measured_at', 'weight_kg', 'body_fat_percentage', 'waist_cm', 'chest_cm', 'hips_cm', 'source', 'notes'];

    protected function casts(): array
    {
        return ['measured_at' => 'datetime', 'weight_kg' => 'decimal:2', 'body_fat_percentage' => 'decimal:2', 'waist_cm' => 'decimal:2', 'chest_cm' => 'decimal:2', 'hips_cm' => 'decimal:2'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
