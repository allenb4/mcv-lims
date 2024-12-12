<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_consumptions', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->nullableMorphs('testable');
            $table->float('quantity')->nullable();
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
        Schema::dropIfExists('products_consumptions');
    }
}
