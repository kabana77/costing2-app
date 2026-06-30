<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_username_field_and_be_redirected_to_costing_input(): void
    {
        $user = User::create([
            'nomor_wa' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
        ]);

        $response = $this->post('/login', [
            'username' => '081234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/costing/input');
        $this->assertAuthenticatedAs($user);
    }
}
