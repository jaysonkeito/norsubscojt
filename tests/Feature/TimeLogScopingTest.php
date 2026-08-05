<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeLogScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_coordinator_can_record_time_for_their_own_advisee(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = Student::create(['user_id' => $studentUser->id, 'coordinator_id' => $coordinator->id]);

        $response = $this->actingAs($coordinator)->post('/time-logs', [
            'student_id' => $student->id,
            'log_date' => now()->toDateString(),
            'time_in' => '08:00',
            'time_out' => '17:00',
            'tasks_performed' => 'Testing',
        ]);

        $response->assertRedirect(route('timelogs.index'));
        $this->assertDatabaseHas('time_logs', [
            'student_id' => $student->id,
            'status' => 'approved', // recorded by an authorized party — auto-approved
        ]);
    }

    public function test_coordinator_cannot_record_time_for_a_student_not_assigned_to_them(): void
    {
        $coordinatorA = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $coordinatorB = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        // Student is assigned to Coordinator B, not A
        $student = Student::create(['user_id' => $studentUser->id, 'coordinator_id' => $coordinatorB->id]);

        $response = $this->actingAs($coordinatorA)->post('/time-logs', [
            'student_id' => $student->id,
            'log_date' => now()->toDateString(),
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('time_logs', 0);
    }

    public function test_company_cannot_record_time_for_a_student_not_assigned_to_their_company(): void
    {
        $companyA = Company::create(['name' => 'Company A']);
        $companyB = Company::create(['name' => 'Company B']);
        $companyRepA = User::factory()->create(['role' => 'company', 'status' => 'active', 'company_id' => $companyA->id]);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = Student::create(['user_id' => $studentUser->id, 'company_id' => $companyB->id]);

        $response = $this->actingAs($companyRepA)->post('/time-logs', [
            'student_id' => $student->id,
            'log_date' => now()->toDateString(),
            'time_in' => '08:00',
            'time_out' => '17:00',
        ]);

        $response->assertForbidden();
    }

    public function test_student_cannot_access_the_create_time_log_route_at_all(): void
    {
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        Student::create(['user_id' => $studentUser->id, 'student_id_no' => 'S001', 'course' => 'BSIT', 'year_level' => '4th Year']);

        $this->actingAs($studentUser)->get('/time-logs/create')->assertForbidden();
    }
}
