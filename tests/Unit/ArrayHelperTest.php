<?php

namespace Marufnwu\Utils\Tests\Unit;

use Marufnwu\Utils\Helpers\ArrayHelper;
use Marufnwu\Utils\Tests\TestCase;

class ArrayHelperTest extends TestCase
{
    /** @test */
    public function it_gets_value_using_dot_notation()
    {
        $array = ['user' => ['name' => 'John', 'email' => 'john@example.com']];

        $this->assertEquals('John', ArrayHelper::get($array, 'user.name'));
        $this->assertEquals('john@example.com', ArrayHelper::get($array, 'user.email'));
        $this->assertEquals('default', ArrayHelper::get($array, 'user.age', 'default'));
    }

    /** @test */
    public function it_sets_value_using_dot_notation()
    {
        $array = [];
        ArrayHelper::set($array, 'user.name', 'John');
        ArrayHelper::set($array, 'user.email', 'john@example.com');

        $this->assertEquals('John', $array['user']['name']);
        $this->assertEquals('john@example.com', $array['user']['email']);
    }

    /** @test */
    public function it_checks_if_key_exists_using_dot_notation()
    {
        $array = ['user' => ['name' => 'John']];

        $this->assertTrue(ArrayHelper::has($array, 'user.name'));
        $this->assertFalse(ArrayHelper::has($array, 'user.age'));
    }

    /** @test */
    public function it_forgets_value_using_dot_notation()
    {
        $array = ['user' => ['name' => 'John', 'email' => 'john@example.com']];
        ArrayHelper::forget($array, 'user.email');

        $this->assertTrue(ArrayHelper::has($array, 'user.name'));
        $this->assertFalse(ArrayHelper::has($array, 'user.email'));
    }

    /** @test */
    public function it_flattens_array()
    {
        $array = [1, [2, 3], [4, [5, 6]]];
        $flattened = ArrayHelper::flatten($array);

        $this->assertEquals([1, 2, 3, 4, 5, 6], $flattened);
    }

    /** @test */
    public function it_groups_array_by_key()
    {
        $array = [
            ['category' => 'A', 'value' => 1],
            ['category' => 'B', 'value' => 2],
            ['category' => 'A', 'value' => 3],
        ];

        $grouped = ArrayHelper::groupBy($array, 'category');

        $this->assertCount(2, $grouped['A']);
        $this->assertCount(1, $grouped['B']);
    }

    /** @test */
    public function it_plucks_values_from_array()
    {
        $array = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
        ];

        $names = ArrayHelper::pluck($array, 'name');
        $namesWithKeys = ArrayHelper::pluck($array, 'name', 'id');

        $this->assertEquals(['John', 'Jane'], $names);
        $this->assertEquals([1 => 'John', 2 => 'Jane'], $namesWithKeys);
    }

    /** @test */
    public function it_filters_array_where()
    {
        $array = [
            ['name' => 'John', 'age' => 25],
            ['name' => 'Jane', 'age' => 30],
            ['name' => 'Bob', 'age' => 20],
        ];

        $filtered = ArrayHelper::where($array, 'age', '>', 22);

        $this->assertCount(2, $filtered);
    }

    /** @test */
    public function it_sorts_array_by_key()
    {
        $array = [
            ['name' => 'John', 'age' => 25],
            ['name' => 'Jane', 'age' => 30],
            ['name' => 'Bob', 'age' => 20],
        ];

        $sorted = ArrayHelper::sortBy($array, 'age');

        $this->assertEquals('Bob', $sorted[0]['name']);
        $this->assertEquals('Jane', $sorted[2]['name']);
    }

    /** @test */
    public function it_checks_if_array_is_associative()
    {
        $this->assertTrue(ArrayHelper::isAssoc(['name' => 'John']));
        $this->assertFalse(ArrayHelper::isAssoc([1, 2, 3]));
    }
}
