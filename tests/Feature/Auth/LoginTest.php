<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk ke akun Anda');
    }

    public function test_user_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        Role::query()->create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Mengelola data operasional sekolah dan PPDB.',
        ]);

        $user->syncRoles(['admin']);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_non_admin_role_cannot_login_to_admin_area(): void
    {
        $user = User::factory()->create([
            'email' => 'siswa@example.test',
        ]);

        Role::query()->create([
            'name' => 'siswa',
            'display_name' => 'Siswa',
            'description' => 'Akses pengguna siswa pada fitur yang diizinkan.',
        ]);

        $user->syncRoles(['siswa']);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
