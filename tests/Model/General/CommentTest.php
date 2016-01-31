<?php

namespace Tests\Model\General;

use App\Ccu\General\Comment;
use App\Ccu\General\Like;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_define_comments_relationship()
    {
        $comment = new Comment;

        $this->assertTrue(method_exists($comment, 'comments'));
        $this->assertEquals(new Comment, $comment->comments()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_likes_relationship()
    {
        $comment = new Comment;

        $this->assertTrue(method_exists($comment, 'likes'));
        $this->assertEquals(new Like, $comment->likes()->getRelated());
    }
}
