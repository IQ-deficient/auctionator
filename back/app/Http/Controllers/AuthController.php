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
     * Return Users for management of User Profiles based on the Authenticated User Role
     * @return array|Response
     */
    public function index()
    {
        $roles = User::getUserRoles();

        // Get all active Users that fit the role of Manager and Auctioneer
        $usernames = DB::table('user_roles')
            ->whereIn('role', ['Manager', 'Auctioneer'])
            ->pluck('username');
        $managers_auctioneers = User::query()
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

        // Get all active Users that fit the role of Client
        $usernames = DB::table('user_roles')
            ->where('role', 'Client')
            ->pluck('username');
        $clients = User::query()
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

        // For Manager return Client Users, and for Administrator also compile Managers & Auctioneers in separate variable
        if (in_array('Administrator', $roles)) {
            return ['clients' => $clients, 'managers_auctioneers' => $managers_auctioneers];
        } elseif (in_array('Manager', $roles)) {
            return ['clients' => $clients];
        }
        return response('You do not have permissions for requested data!', 400);
    }

    /**
     * Get User Roles array with ones that are used for employees.
     * @return Collection
     */
    public function getEmployeeRoles()
    {
        $nonEmployees = ['Administrator', 'Client'];
        return DB::table('roles')
            ->where('is_active', true)
            ->whereNotIn('name', $nonEmployees)
            ->get();
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
            return response()->json(['error' => 'Unauthorized'], 401);
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
            'phone_number' => ['required', 'digits_between:6,15', 'unique:users,phone_number'],
//            'gender' => 'nullable|string|max:32|exists:genders,name',
            'country' => 'nullable|string|max:32|exists:countries,name',
            'birthdate' => 'nullable|date',
//            'image' => 'required'
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
     * The Administration User is able to create an entry for Employee Users.
     * @return JsonResponse
     * @throws ValidationException
     */
    public function registerEmployee(Request $request)
    {
        $roles = User::getUserRoles();

        // No personnel other than Admins can make User instances for the employed
        abort_if(!in_array('Administrator', $roles), 403, 'Only Administrators are allowed to register employees.');

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3', 'max:32', 'unique:users,username'],
            'password' => 'required|string|confirmed|min:8|max:128',
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
            'email' => ['required', 'email:rfc,dns', 'min:10', 'max:254', 'unique:users,email'],
            'country' => 'nullable|string|max:32|exists:countries,name',
            'phone_number' => ['required', 'digits_between:6,15', 'unique:users,phone_number'],
//            'gender' => 'nullable|string|max:32|exists:genders,name',
//            'country' => 'nullable|string|max:32|exists:countries,name',
//            'birthdate' => 'nullable|date',
//            'image' => 'required',
            'roles' => 'required',
            'roles.*' => 'required|string|distinct|exists:roles,name'       // Array validator
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        // Assign this Employee the chosen Roles
        foreach ($request->roles as $role) {
            UserRole::create([
                'username' => $user->username,
                'role' => $role
            ]);
        }

        return response()->json([
            'message' => 'Employee successfully registered.',
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
            'expires_in' => auth()->factory()->getTTL() * 120,
            'user' => auth()->user(),
            'user_roles' => User::getUserRoles()
        ]);
    }

    /**
     * Change all User data other than Password and Image.
     * @param User $user
     * @param Request $request
     * @return Builder|JsonResponse|Model|object|null
     */
    public function update(Request $request, User $user)
    {
        /**
         * between:min,max -> The field under validation must have a size between the given min and max.
         * Strings, numerics, arrays, and files are evaluated in the same fashion as the size rule.
         * size:value -> The field under validation must have a size matching the given value.
         * For string data, value corresponds to the number of characters.
         * For numeric data, value corresponds to a given integer value.
         * For an array, size corresponds to the count of the array.
         * For files, size corresponds to the file size in kilobytes.
         */
        // todo: validate if the auth user has required roles to perform this action (this goes for other stuff asw but alas)
        //  This will also be used as edit profile for Clients
        //  We should probably make it available for admin to alter the roles for select users (and select multiple)
        $validator = Validator::make($request->all(), [
//            'username' => ['required', 'string', 'between:3,32', Rule::when($request->username != $user->username, 'unique:users')],
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
//            'email' => ['required', 'email:rfc,dns', 'between:10,254', Rule::when($request->email != $user->email, 'unique:users')],
            'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users')],
            'gender' => 'nullable|string|max:32|exists:genders,name',
            'country' => 'nullable|string|max:32|exists:countries,name',
            'birthdate' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Updating username here will result in cascading update of username in child rows because of onUpdate in migrations
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'country' => $request->country,
            'birthdate' => $request->birthdate,
            'updated_at' => Carbon::now()
        ]);

        return User::query()->where('id', $user->id)->first();
    }

    /**
     * Update the specified Users' Password using old password confirmation.
     * @param Request $request
     * @param User $user
     * @return Builder|JsonResponse|Model|object|null
     */
    public function changePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|between:8,128',
            'password' => 'required|string|confirmed|between:8,128'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Old password from input must be same as the one already in database for change to occur
        abort_if(!Hash::check($request->old_password, $user->password), 400, 'Old password is not correct.');

        // Change the user password into hashed value and return User object
        $user->update([
            'password' => bcrypt($request->password),
            'updated_at' => Carbon::now()
        ]);

        return User::query()->where('id', $user->id)->first();
    }

    /**
     * Update the specified Users' Profile Image.
     * @param Request $request
     * @param User $user
     * @return Builder|JsonResponse|Model|object|null
     */
    public function changeUserImage(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required'
        ]);

        // TODO: do

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'image' => $request->image,
            'updated_at' => Carbon::now()
        ]);

        return User::query()->where('id', $user->id)->first();
    }

    /**
     * Deactivate or reactivate User by changing their is_active status.
     * @param User $user
     * @return Builder|Model|object|null
     */
    public function destroy(User $user)
    {
        // TODO: there is a middleware in place that does not allow access to api routes for inactive users
        //  but in any case we should invalidate token for that user [which is on frontend so that will be a problem]
        // auth()->logout();

        // When User is being deactivated, all their bids are also permanently deactivated
        // todo: can we actually do this?

        // figure it out, expired, sold++++

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return User::query()->where('id', $user->id)->first();
    }
}
