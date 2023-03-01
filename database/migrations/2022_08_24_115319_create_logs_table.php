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
        Schema::create('logs', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('instance')->index();
          $table->string('channel')->index();
          $table->string('level')->index();
          $table->string('level_name');
          $table->text('message');
          $table->text('context');

          $table->integer('remote_addr')->nullable()->unsigned();
          $table->string('user_agent')->nullable();
          $table->integer('created_by')->nullable()->index();

          $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
