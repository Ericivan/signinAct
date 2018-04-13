<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatUserSign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_sign')) {
            return;
        }
        Schema::create('user_sign', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_resign')->default(0);
            $table->integer('user_id',false,true);
            $table->integer('reward_id',false,true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('resign_at')->nullable();
            $table->charset = 'utf8mb4';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sign');
    }
}
