<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miles_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('miles_conversion_currency', 255);
            $table->string('miles_conversion_amount', 255);
            $table->string('miles_operation_type', 25);
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
        Schema::dropIfExists('miles_conversions');
    }
};
