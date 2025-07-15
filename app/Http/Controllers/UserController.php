<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return UserResource::collection($users);
    }

    public function search(SearchUserRequest $request)
    {
        $filters = $request->validated();

        $users = User::query()
            ->when($filters['role'] ?? null, fn($q, $v)   => $q->where('role', '=', $v))
            ->when($filters['name'] ?? null, fn($q, $v)   => $q->where('name', 'like', "%{$v}%"))
            ->when($filters['email'] ?? null, fn($q, $v)  => $q->where('email', '=', $v))
            ->orderBy('name')
            ->paginate(15);

        return UserResource::collection($users);
    }


    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => [
                'sometimes',
                Rule::in(['doctor', 'patient']),
            ],
        ]);

        $user = User::create($data);

        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'role'     => [
                'sometimes',
                Rule::in(['admin', 'doctor', 'patient', 'user']),
            ],
        ]);

        $user->update($data);

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
