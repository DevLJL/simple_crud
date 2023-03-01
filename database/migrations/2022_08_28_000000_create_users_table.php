<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('role_id')->nullable()->index();
            $table->tinyInteger('is_admin')->nullable()->comment('[0..1] 0-False, 1-True');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'adm',
                'email' => 'adm@msn.com',
                'email_verified_at' => now(),
                'password' => bcrypt('adm123'),
                'is_admin' => 1,
                'role_id' => null,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
            [
                'name' => 'user',
                'email' => 'user@msn.com',                
                'email_verified_at' => now(),
                'password' => bcrypt('user123'),
                'is_admin' => 0,
                'role_id' => 1,
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
            ],
        ]); 
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
};
