<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Return Users for management of User Profiles based on the Authenticated User Role
     * @return array|Response
     */
    public function index()
    {
        $roles = User::getUserRoles(Auth::user()->username);

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
     * The Administration User is able to create an entry for Employee Users.
     * @return JsonResponse
     * @throws ValidationException
     */
    public function registerEmployee(Request $request)
    {
        $roles = User::getUserRoles(Auth::user()->username);

        // No personnel other than Admins can make User instances for the employed
        abort_if(!in_array('Administrator', $roles), 403, 'Only Administrators are allowed to register employees.');

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3', 'max:32', 'unique:users,username'],
            'password' => 'required|string|confirmed|min:8|max:128',
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
            'email' => ['required', 'email:rfc,dns', 'min:10', 'max:254', 'unique:users,email'],
            'country' => 'required|string|max:32|exists:countries,name',
            'phone_number' => ['required', 'digits_between:6,15', 'unique:users,phone_number'],
//            'gender' => 'nullable|string|max:32|exists:genders,name',
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
     * Change all User data (Update Profile for self) other than Password and Image.
     * @param User $user
     * @param Request $request
     * @return Builder|JsonResponse|Model|object|null
     */
    public function update(Request $request, User $user)
    {
        $auth = Auth::user();

        // Confirm that the User being Updated from api route is the one that is Authenticated
        abort_if($auth->id != $user->id, 409, 'Authenticated User does not match the User being edited.');

        /**
         * between:min,max -> The field under validation must have a size between the given min and max.
         * Strings, numerics, arrays, and files are evaluated in the same fashion as the size rule.
         * size:value -> The field under validation must have a size matching the given value.
         * For string data, value corresponds to the number of characters.
         * For numeric data, value corresponds to a given integer value.
         * For an array, size corresponds to the count of the array.
         * For files, size corresponds to the file size in kilobytes.
         */
        $validator = Validator::make($request->all(), [
//            'username' => ['required', 'string', 'between:3,32', Rule::when($request->username != $user->username, 'unique:users')],
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
//            'email' => ['required', 'email:rfc,dns', 'between:10,254', Rule::when($request->email != $user->email, 'unique:users')],
            'country' => 'required|string|max:32|exists:countries,name',
            'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users')],
            'gender' => 'nullable|string|max:32|exists:genders,name',
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
        ]);

        return User::query()->where('id', $user->id)->first();
    }

    /**
     * Manage other User Profiles.
     * @param User $user
     * @param Request $request
     * @return Builder|JsonResponse|Model|object|null
     */
    public function manage(Request $request, User $user)
    {
        $auth = Auth::user();
        $auth_roles = User::getUserRoles($auth->username);
        $edit_user_roles = User::getUserRoles($user->username);

        // Confirm that the User being Updated from api route is NOT the one that is Authenticated
        abort_if($auth->id == $user->id, 409, 'Please update your own profile through user profile page.');

        if (in_array('Manager', $auth_roles)) {

            // Manager is allowed to only Update Clients
            abort_if(!in_array('Client', $edit_user_roles), 405, 'The User you are trying to edit is not a Client');

            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|between:1,32',
                'last_name' => 'required|string|between:1,32',
                'country' => 'required|string|max:32|exists:countries,name',
                'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users')],
                'gender' => 'nullable|string|max:32|exists:genders,name',
                'birthdate' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'country' => $request->country,
                'birthdate' => $request->birthdate,
            ]);

        } elseif ((in_array('Administrator', $auth_roles))) {

            // Client and Employee Users have separate logic where dedicated personnel change their data
            if (in_array('Client', $edit_user_roles)) {

                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string|between:1,32',
                    'last_name' => 'required|string|between:1,32',
                    'country' => 'required|string|max:32|exists:countries,name',
                    'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users,phone_number')],
                    'gender' => 'nullable|string|max:32|exists:genders,name',
                    'birthdate' => 'nullable|date',
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'country' => $request->country,
                    'birthdate' => $request->birthdate,
                ]);

            } elseif (in_array('Manager', $edit_user_roles) || in_array('Auctioneer', $edit_user_roles)) {

                $validator = Validator::make($request->all(), [
                    'first_name' => 'required|string|between:1,32',
                    'last_name' => 'required|string|between:1,32',
                    'country' => 'required|string|max:32|exists:countries,name',
                    'phone_number' => ['required', 'digits_between:6,15', Rule::when($request->phone_number != $user->phone_number, 'unique:users,phone_number')],
                    'gender' => 'nullable|string|max:32|exists:genders,name',
                    'birthdate' => 'nullable|date',
                    'roles' => 'required',
                    'roles.*' => 'required|string|distinct|exists:roles,name'       // Array validator
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                // Remove all present roles assigned to the User being updated
                UserRole::query()
                    ->where('username', $user->username)
                    ->delete();

                // Give them new roles based on the input
                foreach ($request->roles as $role) {
                    UserRole::create([
                        'username' => $user->username,
                        'role' => $role
                    ]);
                }

                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'country' => $request->country,
                    'birthdate' => $request->birthdate,
                ]);

            } // Administrator can Update any User that is not an Admin
            else abort(405, 'You are not allowed to edit other Administrators');

        } else {
            // Finally, any other Role gets an error, and we abort this action
            abort(403, 'You do not own permissions to perform this action.');
        }

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

        // New password can't be identical to old password
        abort_if(Hash::check($request->password, $user->password), 400, 'New password is identical to old password.');

        // Change the user password into hashed value and return User object
        $user->update([
            'password' => bcrypt($request->password)
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
            'image' => 'required|mimes:jpeg,png,jpg|max:2048'  // Check laravel max validation for filesize
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Delete previous image from storage if it exists
        $array = explode('/', $user->image);
        $image_name = end($array);
        Storage::disk('public')->delete("user_images/" . $image_name);

        // Format the name for image being stored
        $originalName = explode(
            ".",
            preg_replace("/[^A-Za-z0-9.!?]/", '', $request->image->getClientOriginalName()), 2)[0];
        $extension = $request->image->getClientOriginalExtension();
        $time = now()->getTimestamp();
        $filename = "{$originalName}-{$time}.{$extension}";

        // Store the image in specified folder
        $dest_path = 'storage/user_images/' . $filename;
        $request->image->storeAs('/user_images', $filename, ['disk' => 'public']);

        $user->update([
            'image' => $dest_path
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
        // : there is a middleware in place that does not allow access to api routes for inactive users
        //  but in any case we should invalidate token for that user [which is on frontend so that will be a problem]
        // auth()->logout();

        // later: When User is being deactivated, all their bids are also permanently deactivated (and restored to previous on on auctions)

        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Manager', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Managers and Admins are allowed to update User activity status.');

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return User::query()->where('id', $user->id)->first();
    }

}
