<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NutritionPlan extends Model
{
    protected $fillable = ['user_id', 'daily_activity_level', 'daily_calorie_target'];
    protected function casts(): array { return ['daily_calorie_target' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function versions(): HasMany { return $this->hasMany(NutritionPlanVersion::class); }
    public function meals(): HasMany { return $this->hasMany(Meal::class); }
    public function dietaryRestrictions(): BelongsToMany { return $this->belongsToMany(DietaryRestriction::class, 'nutrition_plan_dietary_restrictions'); }
}
