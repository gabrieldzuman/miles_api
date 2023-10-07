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
        Schema::create('miles_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('miles_account_number', 255);
            $table->unsignedBigInteger('miles_supplier_id');
            $table->foreign('miles_supplier_id')->references('id')->on('miles_suppliers');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('miles_accounts_balance');
            $table->integer('miles_accounts_limit');
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
        Schema::dropIfExists('miles_accounts');
    }
};
