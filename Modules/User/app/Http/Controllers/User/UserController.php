<?php

namespace Modules\User\app\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\User\app\Events\UserLoggedIn;
use Modules\User\app\Events\UserRegistered;
use Modules\User\app\Models\User;

class UserController extends Controller
{
    /**
     * Handles user logins
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('User Access Token');

        // Dispatch logged in event
        UserLoggedIn::dispatch($user);

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at,
        ]);
    }

    /**
     * Register API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'unique:users,email|required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // Create a new user
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Dispatch register event
        UserRegistered::dispatch($user);

        // Create and return an access token along with user details
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => $success]);
    }
}
