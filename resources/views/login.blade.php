@extends('layouts.master')

@section('content')

    <div class="main">
      <p class="sign" align="center">Login.</p>
      <form class="form1" method="post" action="{{ route('loginUser') }}">
        @csrf
        <input class="un" type="text" align="center" placeholder="Username" name="user">
        <input class="pass" type="password" align="center" placeholder="Password" name="pw">
        <input type="submit" class="submit" align="center" value="Login"/>
        <p class="forgot" align="center"><a href="#">Forgot Password?</p>
    </div>

@stop
