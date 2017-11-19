@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Home</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (Auth::check())

                        <h1>hallo {{Auth::user()->name}}</h1>
                        <h2>Je bent ingelogd.</h2>
                        <div id="map"></div>

                    @else
                        <h1>Log nu in</h1>

                    @endif
                </div>
            </div>
        </div>
    </div>
    @if (Auth::check())
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2>Dee mensen hebben een acount.</h2>
            @foreach($users as $user)
                <div class="user-wrapper">
                    <img src="uploads/avatars/{{ $user->avatar }}">
                    <h3> {{ $user->name }}</h3>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
