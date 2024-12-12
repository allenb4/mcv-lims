<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->float('initial_quantity')->default(0);
            $table->float('alert_quantity')->default(0);
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
        Schema::dropIfExists('branch_products');
    }
}
