<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Auth/Login')
            ->has('canResetPassword')
        );
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authorized_admin_can_view_dashboard_data(): void
    {
        $user = User::factory()->create();
        Permission::findOrCreate('dashboard.view', 'web');
        $user->givePermissionTo('dashboard.view');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('metrics.users', 1)
                ->where('metrics.products', null)
                ->has('recentOrders', 0)
            );
    }
}
