<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvatarToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the remember_token column
            $table->dropRememberToken();

            // Add the avatar column
            $table->string('avatar')->default('default-avatar.png')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the avatar column
            $table->dropColumn('avatar');

            // Add the remember_token column back
            $table->rememberToken();
        });
    }
}
