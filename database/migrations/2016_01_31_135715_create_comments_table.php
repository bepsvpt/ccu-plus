<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('comment_id')->nullable();
            $table->string('content', 10000);
            $table->boolean('anonymous')->default(false);
            $table->unsignedSmallInteger('likes')->default(0);
            $table->unsignedInteger('commentable_id')->nullable();
            $table->string('commentable_type', 180)->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('comment_id');
            $table->index(['commentable_id', 'commentable_type']);
            $table->index('created_at');
            $table->index('deleted_at');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_user_id_foreign');
            $table->dropForeign('comments_comment_id_foreign');
        });

        Schema::drop('comments');
    }
}
