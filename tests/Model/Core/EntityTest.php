<?php

namespace Tests\Model\Core;

use App\Ccu\Core\Entity;
use App\Ccu\User\User;
use Tests\TestCase;

class EntityTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_user_table_name()
    {
        $this->assertSame('users', User::getTableName());
    }

    /**
     * @test
     */
    public function it_should_return_entity_table_name()
    {
        $this->assertSame('entities', Entity::getTableName());
    }

    /**
     * @test
     */
    public function it_should_return_the_minutes_of_quarter_day()
    {
        $this->assertSame(60 * 6, Entity::MINUTES_QUARTER_DAY);
    }

    /**
     * @test
     */
    public function it_should_return_the_minutes_of_half_day()
    {
        $this->assertSame(60 * 12, Entity::MINUTES_HALF_DAY);
    }

    /**
     * @test
     */
    public function it_should_return_the_minutes_of_one_day()
    {
        $this->assertSame(60 * 24, Entity::MINUTES_PER_DAY);
    }

    /**
     * @test
     */
    public function it_should_return_the_minutes_of_one_week()
    {
        $this->assertSame(60 * 24 * 7, Entity::MINUTES_PER_WEEK);
    }

    /**
     * @test
     */
    public function it_should_return_the_minutes_of_one_month()
    {
        $this->assertSame(60 * 24 * 30, Entity::MINUTES_PER_MONTH);
    }
}
