<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedInteger('subcategory_id');
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            $table->string('name', 50);
            $table->decimal('amount', 8, 2)->default('0');
            $table->integer('installments')->default(1);
            $table->date('due_date');
            $table->timestamps();
            $table->boolean('fl_pay')->default(0);
            $table->string('description', 100)->nullable();
            $table->boolean('fl_fixed')->default(0);
            $table->boolean('fl_essential')->default(0);
            $table->boolean('fl_split')->default(0);
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
        Schema::dropIfExists('expenses');
    }
}
