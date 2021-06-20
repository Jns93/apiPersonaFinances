<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('expense_id');
            $table->foreign('expense_id')->references('id')->on('expenses');
            $table->integer('number_installment')->default(1);
            $table->integer('amount_installments')->default(1);
            $table->decimal('amount', 8, 2)->default('0');
            $table->date('due_date');
            $table->boolean('fl_pay')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
