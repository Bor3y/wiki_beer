<?php

namespace Tests\Unit;

use Tests\TestCase;

class SearchRequestTest extends TestCase
{
    public function testEmptyRequest()
    {
        $response = $this->json('GET', '/api/search', []);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "type" => ["The type field is required."],
                "q" => ["The q field is required."]
            ]
        ], false);
    }

    public function testWrongType()
    {
        $response = $this->json('GET', '/api/search', [
            'type' => "glass",
            "q" => "wine"
        ]);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "type" => ["The selected type is invalid."],
            ]
        ], false);
    }

    public function testInvalidQuery()
    {
        $response = $this->json('GET', '/api/search', [
            'type' => "beer",
            "q" => "wine #55"
        ]);

        $response->assertStatus(422)->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "q" => ["The q format is invalid."],
            ]
        ], false);

    }


    public function testValidQuery()
    {
        $response = $this->json('GET', '/api/search', [
            'type' => "beer",
            "q" => "red old-wine 1990"
        ]);

        $response->assertStatus(200);

    }

}
