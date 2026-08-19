<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Serves privately-stored uploads (photos, resumes, requirement files).
 * Every method checks the requester's permission before returning
 * anything — these paths are never web-accessible directly.
 */
class FileController extends Controller
{
    private function stream(?string $path)
    {
        abort_if(!$path || !Storage::disk('private')->exists($path), 404);
        return Storage::disk('private')->response($path);
    }

    public function studentPhoto(Request $request, Student $student)
    {
        $user = $request->user();
        $isOwner = $student->user_id === $user->id;
        $isOverseer = in_array($user->role, ['admin', 'dean'])
            || ($user->isCoordinator() && $student->coordinator_id === $user->id)
            || ($user->isCompany() && $student->company_id === $user->company_id);

        abort_unless($isOwner || $isOverseer, 403);

        return $this->stream($student->photo_path);
    }

    public function coordinatorPhoto(Request $request, User $coordinator)
    {
        $this->authorizeStaffFile($request, $coordinator);
        return $this->stream($coordinator->coordinatorProfile?->photo_path);
    }

    public function coordinatorResume(Request $request, User $coordinator)
    {
        $this->authorizeStaffFile($request, $coordinator);
        return $this->stream($coordinator->coordinatorProfile?->resume_path);
    }

    public function companyPhoto(Request $request, User $companyRep)
    {
        $this->authorizeStaffFile($request, $companyRep);
        return $this->stream($companyRep->companyProfile?->photo_path);
    }

    /**
     * User-level photos (Admin, Dean) — only the owner themselves;
     * no other feature displays these.
     */
    public function userPhoto(Request $request, User $user)
    {
        abort_unless($request->user()->id === $user->id, 403);

        return $this->stream($user->photo_path);
    }

    public function requirementFile(Request $request, Requirement $requirement)
    {
        $user = $request->user();
        $student = $requirement->student;
        $isOwner = $student->user_id === $user->id;
        $isOverseer = in_array($user->role, ['admin', 'dean'])
            || ($user->isCoordinator() && $student->coordinator_id === $user->id);

        abort_unless($isOwner || $isOverseer, 403);

        return $this->stream($requirement->file_path);
    }

    /**
     * Shared check for Coordinator/Company staff files: the file owner
     * themselves, or Admin/Dean overseeing the whole program.
     */
    private function authorizeStaffFile(Request $request, User $fileOwner): void
    {
        $user = $request->user();
        $isOwner = $fileOwner->id === $user->id;
        $isOverseer = in_array($user->role, ['admin', 'dean']);

        abort_unless($isOwner || $isOverseer, 403);
    }
}
