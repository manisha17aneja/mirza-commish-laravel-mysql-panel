<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
	
	public function __construct() {
        if(\Session::has('selected_database')){
           Config::set('database.default',\Session::get('selected_database'));
		   //DB::reconnect('mysql');
		   //DB::connection(\Session::get('selected_database'));
        }
      }
    public function dashboard()  {			
        return view('admin.dashboard');
    }

    public function profileSetting() {
        return view('admin.setting.profile');
    }

    public function editProfileSetting(Request $request) {
        $user = auth()->user();
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
            ]);
            if ($request['email'] != $user->email) {
                $request->validate([
                    'email' => 'required | unique:users',
                ]);
                $user->email = $request['email'];
            }

        /*$imageName = '';
        if($request->file('profile_img')!=null){
            $image = $request->file('profile_img');
            $image_path = $image->store('public/profile/images/');
            $imageName = $request->profile_img->hashName();
        }*/

        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {

                $extension = $request->image->extension();
                $image_name = time().".".$extension;
                $request->image->storeAs('/public/profile/images/', $image_name);
                //$user->image = $image_name;

            }else{

                //$image_name ="default.png";

            }

        }else{

            //$image_name ="default.png";

        }

        if ($request['username'] != $user->username) {
            $request->validate([
                'username' => 'required | unique:users',
            ]);
            $user->username = $request['username'];
        }
            if ($request['password']) {
                $request->validate([
                    'password' => [
                        'required',
                        'string',
                        'same:retype_password'
                    ],
                    'retype_password' => 'required',
                ]);
                $user->password = Hash::make($request['password']);
            }
            $user->fname = $request['first_name'];
            //$user->image = $imageName;
            $user->lname = $request['last_name'];
            $user->save();
            return redirect()->back()->with('success','Profile updated successfully.');
    }
}
