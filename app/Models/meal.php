<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    protected $fillable = ['user_id', 'nutrition_plan_id', 'nutrition_plan_version_id', 'title', 'description', 'goal', 'calories'];
    protected function casts(): array { return ['calories' => 'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function nutritionPlan(): BelongsTo { return $this->belongsTo(NutritionPlan::class); }
    public function nutritionPlanVersion(): BelongsTo { return $this->belongsTo(NutritionPlanVersion::class); }
    public function macronutrients(): BelongsToMany { return $this->belongsToMany(Macronutrient::class, 'meal_macronutrients')->withPivot('amount'); }
    public function tags(): BelongsToMany { return $this->belongsToMany(MealTag::class, 'meal_tag_assignments'); }
    public function logs(): HasMany { return $this->hasMany(MealLog::class); }
}
