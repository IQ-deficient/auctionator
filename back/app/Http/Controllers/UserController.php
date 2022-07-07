<?php

namespace App\Http\Controllers;

use App\Mail\MailContact;
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
use Illuminate\Support\Facades\Mail;
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

        $usernames = DB::table('user_roles')
            ->whereIn('role', ['Manager', 'Auctioneer'])
            ->pluck('username');
        $managers_auctioneers = User::query()
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

        $usernames = DB::table('user_roles')
            ->where('role', 'Client')
            ->pluck('username');
        $clients = User::query()
            ->where('is_active', true)
            ->whereIn('username', $usernames)
            ->get();

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

        abort_if(!in_array('Administrator', $roles), 403, 'Only Administrators are allowed to register employees.');

        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'min:3', 'max:32', 'unique:users,username'],
            'password' => 'required|string|confirmed|min:8|max:128',
            'first_name' => 'required|string|between:1,32',
            'last_name' => 'required|string|between:1,32',
            'email' => ['required', 'email:rfc,dns', 'min:10', 'max:254', 'unique:users,email'],
            'country' => 'required|string|max:32|exists:countries,name',
            'phone_number' => ['required', 'digits_between:6,15', 'unique:users,phone_number'],
            'roles' => 'required',
            'roles.*' => 'required|string|distinct|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

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

        abort_if($auth->id == $user->id, 409, 'Please update your own profile through user profile page.');

        if (in_array('Manager', $auth_roles)) {

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
                    'roles.*' => 'required|string|distinct|exists:roles,name'
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                UserRole::query()
                    ->where('username', $user->username)
                    ->delete();

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

            }
            else abort(405, 'You are not allowed to edit other Administrators');

        } else {
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

        abort_if(!Hash::check($request->old_password, $user->password), 400, 'Old password is not correct.');

        abort_if(Hash::check($request->password, $user->password), 400, 'New password is identical to old password.');

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
            'image' => 'required|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $array = explode('/', $user->image);
        $image_name = end($array);
        Storage::disk('public')->delete("user_images/" . $image_name);

        $originalName = explode(
            ".",
            preg_replace("/[^A-Za-z0-9.!?]/", '', $request->image->getClientOriginalName()), 2)[0];
        $extension = $request->image->getClientOriginalExtension();
        $time = now()->getTimestamp();
        $filename = "{$originalName}-{$time}.{$extension}";

        $request->image->storeAs('/user_images', $filename, ['disk' => 'public']);

        $user->update([
            'image' => $filename
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

        $roles = User::getUserRoles(Auth::user()->username);
        abort_if(!(in_array('Manager', $roles) || in_array('Administrator', $roles)),
            400,
            'Only Managers and Admins are allowed to update User activity status.');

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return User::query()->where('id', $user->id)->first();
    }

    public function getUserImage($name)
    {
        return response()->file(public_path('storage/user_images/' . $name));
    }

    /**
     * Email authorities about certain topic or question
     * @return JsonResponse
     */
    public function sendMail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns',
            'title' => 'required|string|max:30',
            'message' => 'required|string|min:10|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Mail::to(env('CONTACT_US_MAIL'))->send(new MailContact($request->all()));

        return response()->json('Email sent!', 200);
    }

}
