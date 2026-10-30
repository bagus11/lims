<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_guest_is_redirected_to_login()
    {
        // root dashboard berada di balik middleware auth — guest diarahkan ke login
        $response = $this->get('/');

        $response->assertStatus(302);
    }
}
