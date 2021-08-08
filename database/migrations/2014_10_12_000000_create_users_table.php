<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->string('email')->unique();
            
            $table->string('oauth_id')->unique()->nullable()->default(null);
            $table->string('oauth_type')->nullable()->default("default");
            $table->string('profile_photo_url')->nullable()->default(null);

            $table->string('api_token')->unique()->nullable()->default(null);
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            
            $table->rememberToken();  // session
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
