<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_assigned_setter_id_foreign');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('assigned_setter_id', 'assigned_lead_id');
            $table->renameIndex('leads_assigned_setter_id_index', 'leads_assigned_lead_id_index');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('assigned_lead_id', 'leads_assigned_lead_id_foreign')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign('leads_assigned_lead_id_foreign');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('assigned_lead_id', 'assigned_setter_id');
            $table->renameIndex('leads_assigned_lead_id_index', 'leads_assigned_setter_id_index');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreign('assigned_setter_id', 'leads_assigned_setter_id_foreign')
                ->references('id')->on('users')->nullOnDelete();
        });
    }
};
