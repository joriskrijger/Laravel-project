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
                                <label><input type="radio" name="gender" value="man">man</label>
                                <label><input type="radio" name="gender" value="vrouw">vrouw</label>
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
