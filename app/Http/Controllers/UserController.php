<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate();
        return new UserCollection($users);
    }

    public function store(Request $request)
    {
        $user = User::create(
            $request->only('name', 'email', 'is_admin') + ['password' => Hash::make(1234)]
        );

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function show(User $user)
    {
        return new UserCollection(User::find($user));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->only('name', 'email', 'is_admin'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $user_name = User::findOrFail($id)->name;

        $msg = 'User ' . $user_name . ' deleted successfully';

        User::destroy($id);

        return response()->json([
            'code' => 204,
            'message' => $msg
        ]);
    }
}
