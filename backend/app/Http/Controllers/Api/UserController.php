<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users
     *
     * Returns a list of all users with their logs
     *
     * @return \Illuminate\Http\JsonResponse JSON response with users
     */
    public function index()
    {
        $users = User::with('logs')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    /**
     * Create a new user
     *
     * @param StoreUserRequest $request Request with validated user data
     * @return \Illuminate\Http\JsonResponse JSON response with success message
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('images', 'public');
            $user->update(['avatar_url' => $path]);
        }

        $user->logs()->create([
            'details' => [
                'action' => 'created',
                'agent' => $request->header('User-Agent'),
                'ip' => $request->ip(),
            ],
        ]);

        return response()->json([
            'message' => 'User created successfully',
        ]);
    }

    /**
     * Update an existing user
     *
     * @param UpdateUserRequest $request Request with validated user data
     * @param User $user User to update
     * @return \Illuminate\Http\JsonResponse JSON response with success message
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('images', 'public');
            $user->update(['avatar_url' => $path]);

            $user->logs()->create([
                'details' => [
                    'action' => 'upload avatar',
                    'agent' => $request->header('User-Agent'),
                    'ip' => $request->ip(),
                    'avatar_url' => $path,
                ],
            ]);
        }

        $user->logs()->create([
            'details' => [
                'action' => 'updated',
                'agent' => $request->header('User-Agent'),
                'ip' => $request->ip(),
            ],
        ]);
        return response()->json([
            'message' => 'User updated successfully',
        ]);
    }

    /**
     * Delete or deactivate a user
     *
     * @param Request $request HTTP request with optional 'force' parameter
     * @param User $user User to delete
     * @return \Illuminate\Http\JsonResponse JSON response with result message
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->user()->id) {
            return response()->json([
                'message' => 'You cannot delete your own account',
            ], 403);
        }

        if ($request->input('force') === 'true') {
            $user->delete();

            $user->logs()->create([
                'details' => [
                    'action' => 'deleted',
                    'agent' => request()->header('User-Agent'),
                    'ip' => request()->ip(),
                ],
            ]);
            return response()->json([
                'message' => 'User deleted successfully',
            ]);
        }

        $user->update(['status' => Status::inactive()]);
        $user->logs()->create([
            'details' => [
                'action' => 'inactive',
                'agent' => request()->header('User-Agent'),
                'ip' => request()->ip(),
            ],
        ]);

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    /**
     * Store an image for a user
     *
     * @param Request $request HTTP request with image file
     * @param User $user User to store the image for
     * @return \Illuminate\Http\JsonResponse JSON response with success message and image path
     */
    public function storeImage(Request $request, User $user)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('images', 'public');

        $user->update(['image' => $path]);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'path' => $path,
        ]);
    }

}
