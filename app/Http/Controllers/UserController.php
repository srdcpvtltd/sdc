<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Facades\UtilityFacades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\PoliceStation;
use App\Models\State;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use File;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;


class UserController extends Controller
{


    public function index(UsersDataTable $table)
    {
        if (\Auth::user()->can('manage-user')) {

            return $table->render('users.index');
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create-user')) {
            $countries = Country::get();
            $roles = Role::pluck('name', 'name')->all();
            return view('users.create', compact('roles', 'countries'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|same:confirm-password',
    //         'roles' => 'required'
    //     ]);
    //     $role_r = Role::findByName($request->roles);

    //     $user   = User::create(
    //         [
    //             'name' => $request['name'],
    //             'email' => $request['email'],
    //             'password' => Hash::make($request['password']),
    //             'confirm_password' => 'required|same:password',
    //             'type' => $role_r->name,
    //             'created_by' => Auth::user()->id,
    //         ]
    //     );


    //     $user->assignRole($role_r);

    //     return redirect()->route('users.index')
    //         ->with('message', __('User created successfully'));
    // }
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|same:confirm-password',
            'roles' => 'required',
            'country_id' => 'required|integer',
            'state_id' => 'required|integer',
            'city_id' => 'required|integer',
            'police_station_id' => 'required|integer',
        ]);

        // Retrieve the role
        $role = Role::findByName($request->roles);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $role->name,
            'country' => $request->country_id,
            'state' => $request->state_id,
            'city' => $request->city_id,
            'police_station' => $request->police_station_id,
            'created_by' => Auth::id(),
        ]);

        // Assign the role to the user
        $user->assignRole($role);

        // Redirect back with success message
        return redirect()->route('users.index')
            ->with('message', __('User created successfully'));
    }


    public function show($id)
    {
        if (\Auth::user()->can('show-user')) {

            $user = User::find($id);
            return view('users.show', compact('user'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function edit($id)
    {
        if (\Auth::user()->can('edit-user')) {

            $user = User::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            $countries = Country::all();
            $states = State::all();
            $city = City::all();
            $policestation = PoliceStation::all();

            return view('users.edit', compact('user', 'roles', 'userRole', 'countries', 'states', 'city', 'policestation'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,

            'roles' => 'required'
        ]);
        $input = $request->except('password', 'confirm_password');

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'country' => $request->country_id,
            'state' => $request->state_id,
            'city' => $request->city_id,
            'police_station' => $request->police_station_id,
        ]);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        if ($request->password) {
            $user->password = bcrypt($request->get('password'));
            $user->save();
        }
        return redirect()->route('users.index')
            ->with('message', __('User updated successfully'));
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('delete-user')) {


            if ($id == 1) {

                return redirect()->back()->with('error', 'Permission denied.');
            } else {

                DB::table("users")->delete($id);
                return redirect()->route('users.index')->with('message', __('User delete successfully.'));
            }
        }
    }


    public function profile()
    {
        $setting = UtilityFacades::settings();
        if (isset($setting['authentication']) && $setting['authentication'] == 'activate') {


            if (extension_loaded('imagick')) {

                $user = Auth::user();
                $google2fa_url = "";
                $secret_key = "";

                if ($user->loginSecurity()->exists()) {
                    $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
                    $google2fa_url = $google2fa->getQRCodeInline(
                        config('app.name'),
                        $user->email,
                        $user->loginSecurity->google2fa_secret
                    );
                    $secret_key = $user->loginSecurity->google2fa_secret;
                }

                $data = array(
                    'user' => $user,
                    'secret' => $secret_key,
                    'google2fa_url' => $google2fa_url
                );
            }
            $userDetail = Auth::user();

            return view('users.profile', compact('data', 'userDetail'));
        } else {
            $userDetail = Auth::user();

            return view('users.profile', compact('userDetail'));
        }
    }

    public function editprofile(Request $request)
    {
        $userDetail = Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                'profile' => 'image|mimes:jpeg,png,jpg,svg|max:3072',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('profile')) {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir             = storage_path('uploads/avatar/');
            $image_path      = $dir . $userDetail['avatar'];

            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);
        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }
        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with('message', __('Profile successfully updated.'));
    }
}
