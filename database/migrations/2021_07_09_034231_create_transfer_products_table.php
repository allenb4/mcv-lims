<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_products', function (Blueprint $table) {
            $table->id();
            $table->integer('transfer_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->float('quantity')->nullable();
            $table->integer('from_branch_id')->nullable();
            $table->integer('to_branch_id')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('transfer_products');
    }
}
