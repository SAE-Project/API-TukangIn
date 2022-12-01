<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTukangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tukangs', function (Blueprint $table) {
            $table->bigIncrements("tukang_id");
            $table->string("tukang_name");
            $table->foreignId("layanan_id")->references("layanan_id")->on("layanans");
            $table->string("tukang_address");
            $table->integer("rating");
            $table->timestamps();
        });
        //change name id to tukang_id
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tukangs');
    }
}
