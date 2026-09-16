<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity_date', 'steps', 'active_minutes', 'calories_burned', 'sleep_minutes', 'source'];

    protected function casts(): array
    {
        return ['activity_date' => 'date', 'steps' => 'integer', 'active_minutes' => 'integer', 'calories_burned' => 'integer', 'sleep_minutes' => 'integer'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
