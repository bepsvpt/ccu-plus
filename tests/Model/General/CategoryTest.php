<?php

namespace Tests\Model\General;

use App\Ccu\General\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 測試資料.
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->initialize();
    }

    /**
     * 建置測試資料.
     *
     * @return void
     */
    protected function initialize()
    {
        foreach (['case1', 'case2', 'case3'] as $value) {
            $this->categories[] = Category::create([
                'category' => 'test-case',
                'name' => $value,
            ])->fresh();
        }
    }

    /**
     * @test
     */
    public function it_should_be_same_as_model_all_method_if_no_params_is_present()
    {
        $this->assertEquals(Category::all(), Category::getCategories());
    }

    /**
     * @test
     */
    public function it_should_return_specific_category_collection_if_category_param_is_present()
    {
        $this->assertCount(count($this->categories), Category::getCategories('test-case'));

        $this->assertEquals($this->categories, Category::getCategories('test-case')->all());
    }

    /**
     * @test
     */
    public function it_should_return_specific_category_if_category_and_name_params_are_present()
    {
        $this->assertEquals($this->categories[0], Category::getCategories('test-case', 'case1'));
        $this->assertEquals($this->categories[1], Category::getCategories('test-case', 'case2'));
        $this->assertEquals($this->categories[2], Category::getCategories('test-case', 'case3'));
    }

    /**
     * @test
     */
    public function it_should_return_specific_category_id_if_all_params_are_present()
    {
        $this->assertSame($this->categories[0]->getAttribute('id'), Category::getCategories('test-case', 'case1', true));
        $this->assertSame($this->categories[1]->getAttribute('id'), Category::getCategories('test-case', 'case2', true));
        $this->assertSame($this->categories[2]->getAttribute('id'), Category::getCategories('test-case', 'case3', true));
    }
}
