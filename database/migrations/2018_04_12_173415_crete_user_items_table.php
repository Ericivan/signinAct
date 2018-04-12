<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteUserItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('user_items')) {
            return;
        }

        Schema::table('user_items', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id',false,true)->unsign();

            $table->string('item_name', 100);

            $table->timestamp('created_at')->nullable();

            $table->timestamp('updated_at')->nullable();

            $table->tinyInteger('is_get', false, true)->default(0);

            $table->timestamp('deleted_at')->nullable();

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
        Schema::dropIfExists('user_items');
    }
}
