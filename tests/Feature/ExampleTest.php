<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestApi extends TestCase
{
    /**
     * Test the API works in less than 50ms
     *
     * @return void
     */
    public function testApi()
    {
        $start = microtime(true);
        $response = $this->get(route('api.jobs.index'));
        $end = microtime(true);

        $response->assertStatus(200);
        $this->assertLessThan(0.05, $end - $start, 'Expected a response time of under 50ms');
    }
}
