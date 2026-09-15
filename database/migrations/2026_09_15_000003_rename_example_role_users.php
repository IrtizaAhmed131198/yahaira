<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $leadAccount = DB::table('users')
            ->where('name', 'Setter User')
            ->where('email', 'setter@example.com')
            ->first();

        if ($leadAccount) {
            if (DB::table('users')->where('email', 'lead@example.com')->exists()) {
                throw new RuntimeException('Cannot rename example lead account: lead@example.com already exists.');
            }

            DB::table('users')->where('id', $leadAccount->id)->update([
                'name' => 'Lead User',
                'email' => 'lead@example.com',
            ]);
        }

        $setterAccount = DB::table('users')
            ->where('name', 'Closer User')
            ->where('email', 'closer@example.com')
            ->first();

        if ($setterAccount) {
            if (DB::table('users')->where('email', 'setter@example.com')->exists()) {
                throw new RuntimeException('Cannot rename example setter account: setter@example.com already exists.');
            }

            DB::table('users')->where('id', $setterAccount->id)->update([
                'name' => 'Setter User',
                'email' => 'setter@example.com',
            ]);
        }
    }

    public function down(): void
    {
        // Preserve renamed example account identities.
    }
};
