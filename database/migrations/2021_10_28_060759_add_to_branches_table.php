<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->boolean('show_header_image')->default(false);
            $table->boolean('show_watermark_image')->default(false);
            $table->boolean('show_footer_image')->default(false);
            $table->string('header_image')->nullable();
            $table->string('watermark_image')->nullable();
            $table->string('footer_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
}
