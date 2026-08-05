<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluationScopingTest extends TestCase
{
    use RefreshDatabase;

    private function evaluationPayload(int $studentId): array
    {
        return [
            'student_id' => $studentId,
            'evaluator_name' => 'Test Evaluator',
            'evaluation_date' => now()->toDateString(),
            'attendance_score' => 18,
            'work_quality_score' => 18,
            'attitude_score' => 18,
            'initiative_score' => 18,
            'communication_score' => 18,
        ];
    }

    public function test_coordinator_can_evaluate_their_own_advisee(): void
    {
        $coordinator = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = Student::create(['user_id' => $studentUser->id, 'coordinator_id' => $coordinator->id]);

        $response = $this->actingAs($coordinator)->post('/evaluations', $this->evaluationPayload($student->id));

        $response->assertRedirect(route('evaluations.index'));
        $this->assertDatabaseHas('evaluations', ['student_id' => $student->id, 'total_score' => 90]);
    }

    public function test_coordinator_cannot_evaluate_a_student_not_assigned_to_them(): void
    {
        $coordinatorA = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $coordinatorB = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = Student::create(['user_id' => $studentUser->id, 'coordinator_id' => $coordinatorB->id]);

        $response = $this->actingAs($coordinatorA)->post('/evaluations', $this->evaluationPayload($student->id));

        $response->assertForbidden();
        $this->assertDatabaseCount('evaluations', 0);
    }

    public function test_coordinator_cannot_delete_an_evaluation_for_a_student_not_theirs(): void
    {
        $coordinatorA = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $coordinatorB = User::factory()->create(['role' => 'coordinator', 'status' => 'active']);
        $studentUser = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $student = Student::create(['user_id' => $studentUser->id, 'coordinator_id' => $coordinatorB->id]);

        $this->actingAs($coordinatorB)->post('/evaluations', $this->evaluationPayload($student->id));
        $evaluation = $student->evaluations()->first();

        $this->actingAs($coordinatorA)->delete("/evaluations/{$evaluation->id}")->assertForbidden();
        $this->assertDatabaseHas('evaluations', ['id' => $evaluation->id]);
    }
}
