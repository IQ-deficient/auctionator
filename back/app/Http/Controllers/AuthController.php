<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
     * Return Users for management of User Profiles based on the Authenticated User Role
     * @return array|Response
     */
    public function index()
    {
//        return User::all();
//        return DB::table('users')->get();
        $roles = User::getUserRoles();

        // Get all active Users that fit the role of Manager and Auctioneer
        $usernames = DB::table('user_roles')
            ->whereIn('role', ['Manager', 'Auctioneer'])
            ->pluck('username');
        $managers_auctioneers = DB::table('users')
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

        // Get all active Users that fit the role of Client
        $usernames = DB::table('user_roles')
            ->where('role', 'Client')
            ->pluck('username');
        $clients = DB::table('users')
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

        // For Manager return Client Users, and for Administrator also compile Managers & Auctioneers in separate variable
        if (in_array('Administrator', $roles)) {
            return ['clients' => $clients, 'managers_auctioneers' => $managers_auctioneers];
        } elseif (in_array('Manager', $roles)) {
            return ['clients' => $clients];
        }
        return response('Something went wrong while fetching users!', 400);
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
            'password' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Find a User that is trying to Authenticate and deny access if their User profile is inactive
        $active_user = DB::table('users')
            ->where('email', $request->email)
            ->first();
        abort_if($active_user->is_active != 1, '421', 'This user is inactive!');

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        // todo: this api will be also used by admins to make accounts for employees
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'User successfully registered',
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
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, User $user)
    {
        // todo: validate if the auth user has required roles to perform this action (this goes for other stuff asw but alas)
        // todo: This will also be used as edit profile for Clients
        $validator = Validator::make($request->all(), [
            // Here we make sure that if User enters a different username, only then it is checked to be unique on users table
            'username' => ['required', 'string', Rule::when($request->username != $user->username, 'unique:users')],
            'password' => 'required|string|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => ['required', 'email:rfc,dns', Rule::when($request->email != $user->email, 'unique:users')],
            'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users')],
            'gender' => 'nullable|string|exists:genders,name',
            'country' => 'nullable|string|exists:countries,name',
            'birthdate' => 'nullable|date',
//            'image' => 'required|integer|exists:warehouses,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Updating username here will result in cascading update of username in child rows because of onUpdate in migrations
        $user->update([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'country' => $request->country,
            'birthdate' => $request->birthdate,     // todo: test if updating birthdate works (should work)
//            'image' => $request->image,
            'updated_at' => Carbon::now()
        ]);

        return User::where('id', $user->id)->first();
    }

    /**
     * Alter activity status for the specified resource in storage.
     * @return mixed
     */
    public function destroy(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return User::where('id', $user->id)->first();
    }
}
