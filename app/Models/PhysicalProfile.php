<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalProfile extends Model
{
    protected $fillable = ['user_id', 'fitness_level', 'daily_activity_level', 'height_cm', 'current_weight_kg', 'calories_per_day'];

    protected function casts(): array
    {
        return ['height_cm' => 'decimal:2', 'current_weight_kg' => 'decimal:2', 'calories_per_day' => 'integer'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
