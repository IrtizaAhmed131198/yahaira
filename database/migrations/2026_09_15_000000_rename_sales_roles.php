<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        $leadRole = DB::table('roles')->where('name', 'setter')->where('guard_name', 'web')->first();

        if ($leadRole) {
            if (DB::table('roles')->where('name', 'lead')->where('guard_name', 'web')->exists()) {
                throw new RuntimeException('Cannot rename setter: lead role already exists.');
            }

            DB::table('roles')->where('id', $leadRole->id)->update(['name' => 'lead']);
        }

        $setterRole = DB::table('roles')->where('name', 'closer')->where('guard_name', 'web')->first();

        if ($setterRole) {
            if (DB::table('roles')->where('name', 'setter')->where('guard_name', 'web')->exists()) {
                throw new RuntimeException('Cannot rename closer: setter role already exists.');
            }

            DB::table('roles')->where('id', $setterRole->id)->update(['name' => 'setter']);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Keep renamed role identities and their existing user assignments.
    }
};
