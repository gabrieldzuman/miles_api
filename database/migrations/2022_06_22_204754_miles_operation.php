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
        Schema::create('miles_operations', function (Blueprint $table) {
            $table->id();
            $table->integer('miles_operation_amount');
            $table->integer('miles_operation_type');
            $table->unsignedBigInteger('purchases_id');
            $table->foreign('purchases_id')->references('id')->on('purchases');
            $table->unsignedBigInteger('miles_account_id');
            $table->foreign('miles_account_id')->references('id')->on('miles_accounts');
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
        Schema::dropIfExists('miles_operations');
    }
};
