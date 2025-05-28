<?php

namespace Marufnwu\Utils\Tests\Unit;

use Marufnwu\Utils\Pipeline;
use Marufnwu\Utils\Tests\TestCase;

class PipelineTest extends TestCase
{
    /** @test */
    public function it_creates_success_pipeline()
    {
        $data = ['user' => 'John'];
        $pipeline = Pipeline::success($data, 'User created');

        $this->assertTrue($pipeline->isSuccess());
        $this->assertFalse($pipeline->isError());
        $this->assertEquals($data, $pipeline->getData());
        $this->assertEquals('User created', $pipeline->message);
        $this->assertEquals(200, $pipeline->status);
    }

    /** @test */
    public function it_creates_error_pipeline()
    {
        $pipeline = Pipeline::error('Something went wrong', 400);

        $this->assertFalse($pipeline->isSuccess());
        $this->assertTrue($pipeline->isError());
        $this->assertEquals('Something went wrong', $pipeline->message);
        $this->assertEquals(400, $pipeline->status);
    }

    /** @test */
    public function it_creates_validation_error_pipeline()
    {
        $errors = ['email' => ['Email is required']];
        $pipeline = Pipeline::validationError($errors);

        $this->assertFalse($pipeline->isSuccess());
        $this->assertEquals(422, $pipeline->status);
        $this->assertEquals(1001, $pipeline->errorCode);
        $this->assertEquals($errors, $pipeline->getData());
    }

    /** @test */
    public function it_converts_to_api_response()
    {
        $data = ['test' => 'value'];
        $pipeline = Pipeline::success($data, 'Success message');
        $response = $pipeline->toApiResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals('Success message', $content['message']);
        $this->assertEquals($data, $content['data']);
        $this->assertArrayHasKey('timestamp', $content);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $pipeline = Pipeline::success(['initial' => 'data'])
            ->withData(['updated' => 'data'])
            ->withMessage('Updated message')
            ->withStatus(201)
            ->withMeta(['total' => 10])
            ->withHeaders(['X-Custom' => 'header']);

        $this->assertEquals(['updated' => 'data'], $pipeline->getData());
        $this->assertEquals('Updated message', $pipeline->message);
        $this->assertEquals(201, $pipeline->status);
        $this->assertEquals(['total' => 10], $pipeline->meta);
        $this->assertEquals(['X-Custom' => 'header'], $pipeline->headers);
    }

    /** @test */
    public function it_converts_to_array()
    {
        $pipeline = Pipeline::success(['test' => 'data'], 'Test message');
        $array = $pipeline->toArray();

        $this->assertIsArray($array);
        $this->assertTrue($array['success']);
        $this->assertEquals('Test message', $array['message']);
        $this->assertEquals(['test' => 'data'], $array['data']);
        $this->assertArrayHasKey('timestamp', $array);
    }

    /** @test */
    public function it_converts_to_json()
    {
        $pipeline = Pipeline::success(['test' => 'data']);
        $json = $pipeline->toJson();

        $this->assertJson($json);
        $decoded = json_decode($json, true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals(['test' => 'data'], $decoded['data']);
    }
}
