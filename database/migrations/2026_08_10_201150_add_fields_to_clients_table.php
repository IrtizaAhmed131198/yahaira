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
        Schema::table('clients', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->string('occupation')->nullable();
            $table->string('relationship_goal')->nullable();
            $table->string('commitment_timeline')->nullable();
            $table->text('core_values')->nullable();
            $table->text('lifestyle')->nullable();
            $table->string('faith')->nullable();
            $table->string('children')->nullable();
            $table->text('deal_breakers')->nullable();
            $table->string('current_stage')->nullable();
            $table->text('learned_from_last_relationship')->nullable();
            $table->text('ready_for_now')->nullable();
            $table->text('support_system')->nullable();
            $table->string('partner_age_range')->nullable();
            $table->string('partner_location_radius')->nullable();
            $table->string('partner_education_level')->nullable();
            $table->string('partner_career_stage')->nullable();
            $table->text('partner_must_haves')->nullable();
            $table->text('partner_nice_to_haves')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->text('review_notes')->nullable();
            $table->string('application_status')->default('Under Review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'occupation',
                'relationship_goal',
                'commitment_timeline',
                'core_values',
                'lifestyle',
                'faith',
                'children',
                'deal_breakers',
                'current_stage',
                'learned_from_last_relationship',
                'ready_for_now',
                'support_system',
                'partner_age_range',
                'partner_location_radius',
                'partner_education_level',
                'partner_career_stage',
                'partner_must_haves',
                'partner_nice_to_haves',
                'reviewed_by',
                'review_notes',
                'application_status'
            ]);
        });
    }
};
