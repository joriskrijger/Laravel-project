<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{

    public function profile()
    {
        return view('profile', array('user'=> Auth::user()) );

    }

    public function update_avatar(Request $request)
    {
        $user = Auth::user();
        $email = Input::get('email');
        $name = Input::get('name');
        $age = Input::get('age');
        $gender = Input::get('gender') == 'checked' ? 1 : 0;

        dump($gender);

        //handel user upload of avatar
        if( $request->hasFile( 'avatar' ) ){
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(null,300, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/uploads/avatars/' . $filename));

            $user-> avatar = $filename;
        }

        $user-> email = $email;
        $user-> name = $name;
        $user-> age = $age;

//        $gender -> gender = $gender;

        $user->save();

        return view('profile', array('user'=> Auth::user()) );

    }

}
