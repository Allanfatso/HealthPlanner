<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthCondition extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description'];

    public function users(): BelongsToMany { return $this->belongsToMany(User::class, 'user_health_conditions')->withPivot('notes'); }
    public function symptomLogs(): HasMany { return $this->hasMany(SymptomLog::class); }
}
