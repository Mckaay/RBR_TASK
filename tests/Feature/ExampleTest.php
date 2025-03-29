<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ExampleTest extends TestCase
{
    public function test_if_not_logged_in_user_gets_redirected_to_login_route(): void
    {
        $response = $this->get('/');

        $this->assertGuest();
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
