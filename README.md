# Laravel-project
Basic Laravel project, user inlog, chat with pusher and location. I am a beginner in Laravel and used a lot of souces to complete this project. All the soucres are included on the bottom of this page.
I followd tutorials and implemented this in my own application.

# instal
Instal Laravel via Composer
In terminal go to you destination map for you project and run this command : composer create-project --prefer-dist laravel/YOUR-APP-NAME
Set up your .env file with your databes credentials. 
## NOTE if youre using MAMP as virtual host add UNIX_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock to youre .env file to resolve future issues in developemnt

Your database credentials should look something like this.
```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laravel-chat
DB_USERNAME=root
DB_PASSWORD=root
```

# Make users log in.
Run command : php artisan make:auth in terminal.
This will create the necessary routes, views and controllers needed for an authentication system.
Update with your own database details. Now, we can run our migration: php artisan migrate

# create edit profile page 
Create in resouces/views a new file caled profile.blade.php.

```
@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                  
                    <div class="panel-heading">
                        <h1>Profiel van <strong>{{ $user->name }}</strong></h1>
                        <img src="/uploads/avatars/{{ $user->avatar }}" style="width: auto; height: 150px ">
                    </div>
                  
                    <div class="panel-body">
                      
                        <form  enctype="multipart/form-data"  action="/profile" method="post">
                          
                            {{ csrf_field() }}
                          
                            <div class="form-group">
                                <label for="profilePicture">Verander je profiel foto.</label>
                                <input id="input-b1" name="avatar" type="file">
                            </div>
                          
                            <div class="form-group">
                                <label for="name">Naam</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}">
                            </div>
                          
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="text" name="email" value="{{ Auth::user()->email }}">
                            </div>
                    
                            <div class="form-group">
                                <label for="age">Leeftijd</label>
                                <input type="number" name="age" value="{{ Auth::user()->age }}">
                            </div>

                            <button type="submit">Opslaan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
@endsection
```
Got to routes/web.php
Add :
```
Route::get('/profile','ProfileController@profile');
Route::post('/profile','ProfileController@update_avatar');
```
This wil show your profile and handle the update reqeust.

Go to app/Http/Controllers
Add: ProfileController.php
```
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

        $user->save();

        return view('profile', array('user'=> Auth::user()) );

    }

}
```
Go to database/migrations
Update your create_user_table.php

## NOTE : to edit a user avatar image I used a package image intervetion 
Go to http://image.intervention.io/getting_started/installation#laravel and follow the steps to install.

```
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('avatar')->default('default.png');
            $table->integer('age')->default(18);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```
Now run you migration.
```
php artisan migrate:refresh 
```
Now you have a profile page where you can edit a user and add a user image and age.

# add user location

For this functionalitie i used javascript.

Go to resources/views/layouts/app.blade.php
Add 
```
   <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY">
    </script>
```
Replace Your_key with your own google API key, You can get an API key here 
```
https://developers.google.com/maps/documentation/javascript/get-api-key
```

Go to resouces/assets/js/app.js
Update your file with
```

// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see the error "The Geolocation service
// failed.", it means you probably did not give permission for the browser to
// locate you.

$( document ).ready(function() {
    initMap();
});

var map, infoWindow;
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 52.2215, lng: 6.8936},
        zoom: 18
    });
    infoWindow = new google.maps.InfoWindow;

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            infoWindow.open(map);
            map.setCenter(pos);
        }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}
```
run your compiler with mix
```
npm run dev
```
Go to resouces/views/home.blade.php
Update your file with 
```
@if (Auth::check())
    <h1>hallo {{Auth::user()->name}}</h1>
    <h2>You are logged in</h2>
    <div id="map"></div>
@else
    <h1>Log in</h1>
@endif
```
If you are logged in is wil initialize the map in <div id="map"></div> with youre location.

# Get all the registerd users.
to get all the registerd users
```
 $users = User::all();
```
Then return the varabile with a view 
```
        return view('YOUR_VIEW_NAME', compact('users'));

```
Replace YOUR_VIEW_NAME with your own view u wish to retrive this information.
In this view you can use this information like this.
```
 @foreach($users as $user)
    <div class="user-wrapper">
        <img src="uploads/avatars/{{ $user->avatar }}">
        <h3> {{ $user->name }}</h3>
    </div>
@endforeach
```
First argumentd in the foreach loop is the variable name, the second is a local variable for each item in the array You can name it as you please.
Now you can use all the atributes of each user like {{ $user->avatar }} or {{ $user->name }} or {{ $user->age }} or {{ $user->email }}

# Facebook inlog

Socialite is a package that makes building authentification with popular social networks simple.

Install it with composer:
```
composer require laravel/socialite
```
... and by following the instructions from github page https://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.WhGLwLSdVhG

you can follow all the steps to create a facebook inlog

# create message 

To create communication between users I used pusher. Go to https://blog.pusher.com/how-to-build-a-laravel-chat-app-with-pusher/
and follow all the steps to intergrate pusher in your application.

In the souce files you can see how I used this tutorials and intergrate it in my own application. 

## sources :
```
https://developers.google.com/maps/documentation/javascript/geolocation\

https://blog.pusher.com/how-to-build-a-laravel-chat-app-with-pusher/

https://laravel.com/docs/5.5/broadcasting

https://pusher.com/docs/authenticating_users

https://pusher.com/docs/javascript_quick_start

http://image.intervention.io/getting_started/installation#laravel

https://laravel.com/docs/5.5/socialite

https://developers.facebook.com/docs/facebook-login/

https://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.Wg19l7SdVTY

```

