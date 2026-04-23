<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the username column if it exists (from the failed migration)
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }

        // Add username column
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // Generate usernames for users who don't have one
        $users = DB::table('users')->whereNull('username')->get();
        
        foreach ($users as $user) {
            $emailPrefix = explode('@', $user->email)[0];
            $username = $emailPrefix;
            $counter = 1;
            
            // Ensure uniqueness
            while (DB::table('users')->where('username', $username)->whereNotNull('username')->exists()) {
                $username = $emailPrefix . $counter;
                $counter++;
            }
            
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        // Make username NOT NULL
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->change()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};
