<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CoordinatorController extends Controller
{
    public function index()
    {
        $coordinators = User::where('role', 'coordinator')
            ->withCount('studentsAdvised')
            ->latest()
            ->paginate(15);

        return view('coordinators.index', compact('coordinators'));
    }

    public function create()
    {
        return view('coordinators.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'coordinator',
        ]);

        return redirect()->route('coordinators.index')->with('success', 'OJT Coordinator added successfully.');
    }

    public function edit(User $coordinator)
    {
        abort_unless($coordinator->role === 'coordinator', 404);
        return view('coordinators.edit', compact('coordinator'));
    }

    public function update(Request $request, User $coordinator)
    {
        abort_unless($coordinator->role === 'coordinator', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $coordinator->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $coordinator->name = $validated['name'];
        $coordinator->email = $validated['email'];
        if (!empty($validated['password'])) {
            $coordinator->password = Hash::make($validated['password']);
        }
        $coordinator->save();

        return redirect()->route('coordinators.index')->with('success', 'OJT Coordinator updated successfully.');
    }

    public function destroy(User $coordinator)
    {
        abort_unless($coordinator->role === 'coordinator', 404);

        // Un-assign any interns advised by this coordinator before deleting
        $coordinator->studentsAdvised()->update(['coordinator_id' => null]);
        $coordinator->delete();

        return redirect()->route('coordinators.index')->with('success', 'OJT Coordinator removed.');
    }
}
