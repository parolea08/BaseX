@extends('layouts.master')

@section('content')

    <div class="main1">
      <p class="sign" align="center">Register.</p>
      <form class="form1" method="post" action="{{ route('registerUser') }}">
        @csrf
        <input class="un " type="text" align="center" placeholder="Username" name="user">
        <input class="email" type="text" align="center" placeholder="E-Mail" name="email">
        <input class="pass" type="password" align="center" placeholder="Password" name="pw">
        <input class="pass" type="password" align="center" placeholder="Repeat Password" name="pwrepeated">
        <input type="submit" class="submit" align="center" value="Register"/>
      <p class="forgot" align="center"><a href="{{ route('login')}}">Already have an account? Sign in.</p>
    </div>

@stop
