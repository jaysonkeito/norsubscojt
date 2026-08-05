<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationApprovalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_is_active_immediately_and_can_log_in(): void
    {
        $response = $this->post('/register', [
            'account_type' => 'student',
            'username' => 'juandelacruz',
            'email' => 'juan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
        ]);

        $response->assertRedirect(route('login'));

        $user = User::where('email', 'juan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('student', $user->role);
        $this->assertSame('active', $user->status);
        $this->assertNotNull($user->student);

        // Should be able to log in right away
        $login = $this->post('/login', [
            'login' => 'juan@example.com',
            'password' => 'Password123!',
        ]);
        $login->assertRedirect(route('profile.complete'));
    }

    public function test_non_student_registration_defers_account_creation_to_account_completion(): void
    {
        // Step 1: no account should exist yet, just a session stash
        $step1 = $this->post('/register', [
            'account_type' => 'non_student',
            'username' => 'coordjane',
            'email' => 'jane@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'first_name' => 'Jane',
            'last_name' => 'Santos',
        ]);

        $step1->assertRedirect(route('account-completion.show'));
        $this->assertNull(User::where('email', 'jane@example.com')->first());

        // Step 2: now the account gets created, as pending
        $step2 = $this->post('/account-completion', [
            'designation' => 'coordinator',
        ]);

        $step2->assertRedirect(route('login'));

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('coordinator', $user->role);
        $this->assertSame('pending', $user->status);
        $this->assertNotNull($user->coordinatorProfile);
    }

    public function test_pending_account_cannot_log_in_until_approved(): void
    {
        $this->post('/register', [
            'account_type' => 'non_student',
            'username' => 'coordjohn',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'first_name' => 'John',
            'last_name' => 'Reyes',
        ]);
        $this->post('/account-completion', ['designation' => 'coordinator']);

        $login = $this->post('/login', [
            'login' => 'john@example.com',
            'password' => 'Password123!',
        ]);
        $login->assertSessionHasErrors('login');

        // Now approve as Admin and confirm login works afterward
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $user = User::where('email', 'john@example.com')->first();

        $this->actingAs($admin)->patch("/pending-approvals/{$user->id}/approve");

        $user->refresh();
        $this->assertSame('active', $user->status);

        $login2 = $this->post('/login', [
            'login' => 'john@example.com',
            'password' => 'Password123!',
        ]);
        $login2->assertRedirect(route('coordinator-profile.complete'));
    }

    public function test_dean_can_approve_coordinator_but_not_another_dean(): void
    {
        $dean = User::factory()->create(['role' => 'dean', 'status' => 'active']);
        $pendingCoordinator = User::factory()->create(['role' => 'coordinator', 'status' => 'pending']);
        $pendingDean = User::factory()->create(['role' => 'dean', 'status' => 'pending']);

        $this->actingAs($dean)->patch("/pending-approvals/{$pendingCoordinator->id}/approve")
            ->assertRedirect(route('pending-approvals.index'));
        $this->assertSame('active', $pendingCoordinator->fresh()->status);

        // A Dean must NOT be able to approve another pending Dean account
        $this->actingAs($dean)->patch("/pending-approvals/{$pendingDean->id}/approve")
            ->assertForbidden();
        $this->assertSame('pending', $pendingDean->fresh()->status);
    }
}
