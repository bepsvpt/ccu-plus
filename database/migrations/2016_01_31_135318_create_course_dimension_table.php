<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseDimensionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_dimension', function (Blueprint $table) {
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('dimension_id');

            $table->primary(['course_id', 'dimension_id']);

            $table->foreign('course_id')->references('id')->on('courses')
                ->onUpdate('cascade');
            $table->foreign('dimension_id')->references('id')->on('categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_dimension', function (Blueprint $table) {
            $table->dropForeign('course_dimension_course_id_foreign');
            $table->dropForeign('course_dimension_dimension_id_foreign');
        });

        Schema::drop('course_dimension');
    }
}
