<?php

namespace Test\Application;

use App\Ccu\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function sign_in_meta_tag_should_be_0_if_user_not_sign_in()
    {
        Auth::guard()->logout();

        $this->visit(route('home'))->see('<meta name="sign-in" content="0">');
    }

    /**
     * @test
     */
    public function sign_in_meta_tag_should_be_1_if_user_is_signed_in()
    {
        Auth::guard()->login(factory(User::class)->create());

        $this->visit(route('home'))->see('<meta name="sign-in" content="1">');
    }
}
