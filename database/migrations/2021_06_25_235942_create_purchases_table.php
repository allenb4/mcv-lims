<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->float('subtotal')->default(0);
            $table->float('tax')->default(0);
            $table->float('total')->default(0);
            $table->float('paid')->default(0);
            $table->float('due')->default(0);
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('inventory_orders');
    }
}
