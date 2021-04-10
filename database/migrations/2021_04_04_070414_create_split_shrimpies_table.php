<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSplitShrimpiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('split_shrimpies', function (Blueprint $table) {
            $table->id();
            $table->enum('action',['send','convert','remove'])->nullable();
            $table->string('conversion_coin')->nullable();
            $table->foreignId('my_coin_id')->constrained();
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
        Schema::dropIfExists('split_shrimpies');
    }
}
