<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMyProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->string('project_name');
            $table->integer('status');
            $table->string('website_url');
            $table->string('website_language');
            $table->string('add_language');
            $table->string('metadata_translation');
            $table->string('media_translation');
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
        Schema::dropIfExists('my_projects');
    }
}
