<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('candidate_name');
            $table->string('status')->default('identified'); // identified, reviewed, proposed, approved, scheduled, completed, outcome
            $table->timestamps();
        });

        Schema::create('match_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->string('values_score')->nullable();
            $table->string('lifestyle_score')->nullable();
            $table->string('goal_alignment')->nullable();
            $table->string('deal_breaker_check')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('match_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->string('date_time')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('match_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->cascadeOnDelete();
            $table->text('client_feedback')->nullable();
            $table->text('candidate_feedback')->nullable();
            $table->string('rating')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_feedbacks');
        Schema::dropIfExists('match_dates');
        Schema::dropIfExists('match_compatibilities');
        Schema::dropIfExists('matches');
    }
};
