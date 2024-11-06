@extends('layouts.layout')
@section('title', 'ZeroDiffussion')
@section('content')

    <div class="home-container">
        <div class="connect-runtime">
            <form action="/generate" method="POST">
                @csrf
                <input type="text" name="runtime-url" class="input" id="runtime-url" placeholder="https://123-456-789.ngrok.app/">
                <button class="connect btn">Connect</button>
            </form>
        </div>
    </div>
    
@endsection