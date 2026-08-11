<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('timezone')->nullable();
            $table->string('source')->nullable(); // website, fb, insta etc
            $table->string('status')->nullable(); // new, contacted, qualified, lost
            $table->string('interested_service')->nullable();
            $table->string('gender')->nullable();
            $table->string('age_range')->nullable();
            $table->string('location')->nullable();
            $table->string('occupation')->nullable();
            $table->string('budget_range')->nullable();
            $table->unsignedBigInteger('assigned_setter_id')->nullable();
            $table->dateTime('next_followup_at')->nullable();
            $table->timestamps();

            $table->foreign('assigned_setter_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
