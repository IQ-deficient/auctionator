<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|between:8,128',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Something went wrong.'], 401);
        }

        // Find a User that is trying to Authenticate and deny access if their User profile is inactive
        $active_user = User::query()
            ->where('email', $request->email)
            ->first();
        abort_if($active_user->is_active != 1, 403, 'This user is inactive!');

        return $this->createNewToken($token);
    }

    /**
     * Register a User, in this case Client.
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3', 'max:32', 'unique:users,username'],
            /**
             * The hash should always be 60 characters long, no matter what you input as the password.
             * You can have a 500-character paragraph as a password, but when hashed, it will always be 60 characters long.
             * Thus, it will be completely right to store the 128 character password.
             */
            'password' => 'required|string|confirmed|min:8|max:128',
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
            'email' => ['required', 'email:rfc,dns', 'min:10', 'max:254', 'unique:users,email'],
            'country' => 'required|string|max:32|exists:countries,name',
            'birthdate' => 'nullable|date',
//            'image' => 'required'
//            'gender' => 'nullable|string|max:32|exists:genders,name',
            'phone_number' => ['required', 'digits_between:6,15', 'unique:users,phone_number'],
        ]);

        if ($validator->fails()) {
//            return response()->json($validator->errors()->toJson(), 400);
            return response()->json($validator->errors(), 400);
        }

        // Generate a new User entry with validated given data, with password hashed appropriately
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        // Assign this User a role of Client
        UserRole::create([
            'username' => $user->username,
            'role' => 'Client'
        ]);

        return response()->json([
            'message' => 'User successfully registered.',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     * @return JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    public function userImage()
    {
        return response()->json(auth()->user()->image);
    }

    /**
     * Get the token array structure.
     * @param string $token
     * @return JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 120,
            'user' => auth()->user(),
            'user_roles' => User::getUserRoles(Auth::user()->username)
        ]);
    }

}
