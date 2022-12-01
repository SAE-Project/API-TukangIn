<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date("order_date");
            $table->boolean("is_paid");
            $table->date("order_end");
            $table->string("order_address");
            $table->string("order_status");
            $table->foreignId("user_id")->references("id")->on("users");
            $table->foreignId("tukang_id")->references("tukang_id")->on("tukangs");
            $table->foreignId("layanan_id")->references("layanan_id")->on("layanans");
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
        Schema::dropIfExists('orders');
    }
}
