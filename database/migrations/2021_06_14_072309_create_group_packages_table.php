<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->float('price')->default(0);
            $table->float('discount')->default(0);
            $table->float('commission')->default(0);
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
        Schema::dropIfExists('group_packages');
    }
}
