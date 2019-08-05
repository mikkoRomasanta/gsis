<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name', 30);
            $table->string('item_desc', 255)->nullable();
            $table->float('quantity')->default('0');
            $table->char('uom', 4)->default('pc');
            $table->boolean('payment')->default('0')->comment('0: flowlites || 1: cprf');
            $table->float('buffer_stocks')->default('0');
            $table->smallInteger('lead_time')->default('30')->comment('days');
            $table->string('image')->default('noimage.jpg');
            $table->string('status',10)->default('ACTIVE');
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
