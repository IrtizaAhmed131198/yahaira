<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign('deals_assigned_closer_id_foreign');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->renameColumn('assigned_closer_id', 'assigned_setter_id');
            $table->renameIndex('deals_assigned_closer_id_foreign', 'deals_assigned_setter_id_foreign');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->foreign('assigned_setter_id', 'deals_assigned_setter_id_foreign')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropForeign('deals_assigned_setter_id_foreign');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->renameColumn('assigned_setter_id', 'assigned_closer_id');
            $table->renameIndex('deals_assigned_setter_id_foreign', 'deals_assigned_closer_id_foreign');
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->foreign('assigned_closer_id', 'deals_assigned_closer_id_foreign')
                ->references('id')->on('users')->nullOnDelete();
        });
    }
};
