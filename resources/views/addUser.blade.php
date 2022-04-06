@extends('layouts.master')

@section('content')

<div class="main">
  <p class="sign" align="center">Add user to project: {{ $project }}</p>
  <form class="form1" method="post" action="{{ route('setWorkingOn', ['radarId' => request()->radarId]) }}">
    @csrf
    <p class="par" align="center">Choose Username:</p>
    <select class="un" align="center" placeholder="Username" name="user">
      @foreach($users as $user)
      <option>{{ $user['username'] }}</option>
      @endforeach
    </select>
    <input type="submit" class="submit" align="center" value="Add" style="margin-top:20%"/>
</div>


@stop
