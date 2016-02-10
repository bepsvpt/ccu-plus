<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentProfessorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_professor', function (Blueprint $table) {
            $table->unsignedInteger('comment_id');
            $table->unsignedInteger('professor_id');

            $table->primary(['comment_id', 'professor_id']);

            $table->foreign('comment_id')->references('id')->on('comments')
                ->onUpdate('cascade');
            $table->foreign('professor_id')->references('id')->on('categories')
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
        Schema::table('comment_professor', function (Blueprint $table) {
            $table->dropForeign('comment_professor_comment_id_foreign');
            $table->dropForeign('comment_professor_professor_id_foreign');
        });

        Schema::drop('comment_professor');
    }
}
