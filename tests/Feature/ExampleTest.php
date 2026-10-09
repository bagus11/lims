<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        // root dashboard berada di balik middleware auth — guest diarahkan ke login
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
