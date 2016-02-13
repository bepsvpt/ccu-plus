<?php

namespace Test\Application;

use Tests\TestCase;
use Validator;

class PhoneTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_pass_the_phone_validation()
    {
        $validator = Validator::make(['test' => '0912345678'], ['test' => 'phone']);

        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function it_should_not_pass_the_phone_validation_if_value_not_begin_with_09()
    {
        $validator = Validator::make(['test' => '0123456789'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());

        $validator = Validator::make(['test' => '9876543210'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());
    }

    /**
     * @test
     */
    public function it_should_not_pass_the_phone_validation_if_value_length_is_not_10()
    {
        // length is 5
        $validator = Validator::make(['test' => '09123'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());

        // length is 11
        $validator = Validator::make(['test' => '09123456789'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());
    }

    /**
     * @test
     */
    public function it_should_not_pass_the_phone_validation_if_value_is_not_number()
    {
        $validator = Validator::make(['test' => '0912-4568-95'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());
    }

    /**
     * @test
     */
    public function it_should_not_pass_the_phone_validation_although_first_characters_are_correct()
    {
        $validator = Validator::make(['test' => '0912345678-s'], ['test' => 'phone']);

        $this->assertFalse($validator->passes());
    }
}
