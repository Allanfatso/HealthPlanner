<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('fitness_level', 20);
            $table->string('daily_activity_level', 20)->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('current_weight_kg', 5, 2)->nullable();
            $table->unsignedInteger('calories_per_day')->nullable();
            $table->timestampsTz();
        });

        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
        });

        Schema::create('health_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
        });

        Schema::create('dietary_restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
        });

        Schema::create('user_preferences', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('preference_id')->constrained()->restrictOnDelete();
            $table->primary(['user_id', 'preference_id']);
        });

        Schema::create('user_health_conditions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('health_condition_id')->constrained()->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->primary(['user_id', 'health_condition_id']);
        });

        Schema::create('user_dietary_restrictions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dietary_restriction_id')->constrained()->restrictOnDelete();
            $table->primary(['user_id', 'dietary_restriction_id']);
        });

        Schema::create('health_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('goal_type', 30);
            $table->string('title')->nullable();
            $table->decimal('target_weight_kg', 5, 2)->nullable();
            $table->date('target_date')->nullable();
            $table->timestampTz('started_at')->useCurrent();
            $table->timestampTz('ended_at')->nullable();
            $table->timestampsTz();
            $table->index('user_id');
        });

        Schema::create('body_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('measured_at')->useCurrent();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('body_fat_percentage', 5, 2)->nullable();
            $table->decimal('waist_cm', 5, 2)->nullable();
            $table->decimal('chest_cm', 5, 2)->nullable();
            $table->decimal('hips_cm', 5, 2)->nullable();
            $table->string('source', 20)->default('manual');
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['user_id', 'measured_at']);
        });

        Schema::create('plan_generation_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan_type', 20);
            $table->string('provider', 100)->nullable();
            $table->string('provider_model', 100)->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestampTz('requested_at')->useCurrent();
            $table->timestampTz('completed_at')->nullable();
            $table->jsonb('request_snapshot')->nullable();
            $table->jsonb('response_snapshot')->nullable();
            $table->text('failure_message')->nullable();
            $table->index(['user_id', 'requested_at']);
        });

        Schema::create('exercise_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('fitness_level', 20);
            $table->unsignedTinyInteger('days_per_week');
            $table->unsignedSmallInteger('session_duration_minutes');
            $table->unsignedSmallInteger('plan_duration_weeks');
            $table->timestampsTz();
        });

        Schema::create('exercise_plan_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('health_goal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('generation_run_id')->nullable()->constrained('plan_generation_runs')->nullOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('fitness_level', 20);
            $table->unsignedTinyInteger('days_per_week');
            $table->unsignedSmallInteger('session_duration_minutes');
            $table->unsignedSmallInteger('plan_duration_weeks');
            $table->timestampTz('valid_from')->useCurrent();
            $table->timestampTz('valid_to')->nullable();
            $table->string('change_reason')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['exercise_plan_id', 'version_number']);
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exercise_plan_version_id')->nullable()->constrained()->nullOnDelete();
            $table->string('day_label', 50);
            $table->string('name');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->string('repetitions', 50)->nullable();
            $table->string('sets', 50)->nullable();
            $table->string('equipment')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->index('user_id');
        });

        Schema::create('nutrition_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('daily_activity_level', 20);
            $table->unsignedInteger('daily_calorie_target')->nullable();
            $table->timestampsTz();
        });

        Schema::create('nutrition_plan_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('health_goal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('generation_run_id')->nullable()->constrained('plan_generation_runs')->nullOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('daily_activity_level', 20);
            $table->unsignedInteger('daily_calorie_target')->nullable();
            $table->timestampTz('valid_from')->useCurrent();
            $table->timestampTz('valid_to')->nullable();
            $table->string('change_reason')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['nutrition_plan_id', 'version_number']);
        });

        Schema::create('nutrition_plan_dietary_restrictions', function (Blueprint $table) {
            $table->foreignId('nutrition_plan_id')->constrained(indexName: 'npdr_nutrition_plan_fk')->cascadeOnDelete();
            $table->foreignId('dietary_restriction_id')->constrained(indexName: 'npdr_dietary_restriction_fk')->restrictOnDelete();
            $table->primary(['nutrition_plan_id', 'dietary_restriction_id']);
        });

        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nutrition_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('nutrition_plan_version_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('goal')->nullable();
            $table->unsignedInteger('calories')->nullable();
            $table->timestampsTz();
            $table->index('user_id');
        });

        Schema::create('macronutrients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('unit', 10)->default('g');
        });

        Schema::create('meal_macronutrients', function (Blueprint $table) {
            $table->foreignId('meal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('macronutrient_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 8, 2);
            $table->primary(['meal_id', 'macronutrient_id']);
        });

        Schema::create('meal_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
        });

        Schema::create('meal_tag_assignments', function (Blueprint $table) {
            $table->foreignId('meal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meal_tag_id')->constrained()->restrictOnDelete();
            $table->primary(['meal_id', 'meal_tag_id']);
        });

        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_plan_version_id')->nullable()->constrained()->nullOnDelete();
            $table->timestampTz('started_at');
            $table->timestampTz('completed_at')->nullable();
            $table->string('status', 20)->default('in_progress');
            $table->unsignedTinyInteger('perceived_exertion')->nullable();
            $table->text('notes')->nullable();
            $table->timestampsTz();
            $table->index(['user_id', 'started_at']);
        });

        Schema::create('workout_session_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('sort_order');
            $table->string('exercise_name_snapshot');
            $table->unsignedSmallInteger('planned_sets')->nullable();
            $table->string('planned_repetitions', 50)->nullable();
            $table->unsignedSmallInteger('planned_duration_minutes')->nullable();
            $table->boolean('completed')->default(false);
            $table->text('notes')->nullable();
            $table->unique(['workout_session_id', 'sort_order']);
        });

        Schema::create('workout_sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('set_number');
            $table->unsignedSmallInteger('repetitions')->nullable();
            $table->decimal('weight_kg', 7, 2)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('completed')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->unique(['workout_session_exercise_id', 'set_number']);
        });

        Schema::create('meal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meal_id')->nullable()->constrained()->nullOnDelete();
            $table->timestampTz('consumed_at');
            $table->string('meal_type', 20)->nullable();
            $table->decimal('servings', 6, 2)->default(1);
            $table->unsignedInteger('calories_consumed')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['user_id', 'consumed_at']);
        });

        Schema::create('daily_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->unsignedInteger('steps')->nullable();
            $table->unsignedInteger('active_minutes')->nullable();
            $table->unsignedInteger('calories_burned')->nullable();
            $table->unsignedSmallInteger('sleep_minutes')->nullable();
            $table->string('source', 20)->default('manual');
            $table->timestampsTz();
            $table->unique(['user_id', 'activity_date']);
        });

        Schema::create('symptom_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('health_condition_id')->nullable()->constrained()->nullOnDelete();
            $table->timestampTz('occurred_at')->useCurrent();
            $table->string('symptom_name', 100);
            $table->unsignedTinyInteger('severity')->nullable();
            $table->text('notes')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['user_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        foreach ([
            'symptom_logs', 'daily_activity_logs', 'meal_logs', 'workout_sets',
            'workout_session_exercises', 'workout_sessions', 'meal_tag_assignments',
            'meal_tags', 'meal_macronutrients', 'macronutrients', 'meals',
            'nutrition_plan_dietary_restrictions', 'nutrition_plan_versions',
            'nutrition_plans', 'exercises', 'exercise_plan_versions', 'exercise_plans',
            'plan_generation_runs', 'body_measurements', 'health_goals',
            'user_dietary_restrictions', 'user_health_conditions', 'user_preferences',
            'dietary_restrictions', 'health_conditions', 'preferences', 'physical_profiles',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
