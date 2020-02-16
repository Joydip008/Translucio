<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('stripe_response');
            $table->string('stripe_plan_id');
            $table->string('product_id');
            $table->string('plan_name');
            $table->integer('period_id');
            $table->string('period_time');
            $table->integer('status');
            $table->decimal('monthly_cost');
            $table->integer('max_languages')->nullable();
            $table->integer('included_pageviews')->nullable();
            $table->decimal('extra_cost_pageviews')->nullable();
            $table->integer('included_characters')->nullable();
            $table->decimal('additional_characters')->nullable();
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
        Schema::dropIfExists('plans');
    }
}
