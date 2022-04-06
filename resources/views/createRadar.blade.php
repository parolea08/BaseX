@extends('layouts.master')

@section('content')

@if(session()->has('loggedin'))

  <!-- TODO: check if logged in user is moderator -->

  <div class="main">
    <p class="sign" align="center">Create new project:</p>
    <form class="form1" method="post" action="{{ route('createRadar') }}">
      @csrf
      <input class="un" type="text" align="center" placeholder="Project Name" name="name">
      <input class="un" type="text" align="center" placeholder="Description" name="description">
      <input type="submit" class="submit" align="center" value="Save"/>
  </div>

@else

  <div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    You have to be logged in in order to create a new project
  </div>

  <section id="showcase">
    <div class="content">
      <h1>Hit your <br> target <u style="text-decoration-color: #0078E7"> every </u>  <br> time</h1>
      <p class="par">Never miss with our online BASE/X radar tool</p>
      <a href="{{ route('register') }}"><button class="btn" style="vertical-align:middle"><span>Join us </span></button></a>
    </div>
  </section>

  <section id="boxes">
    <div class="container">
      <div class="box">
        <div class="text">
          <p class="motto">At BASE/X Inc. it is our goal to provide you
            a flexible,<br> yet complete solution to identify,
            define and summarize your <br> service-based business model
          </p>
        </div>
      </div>
      <div class="box1">
        <div class="logo-box">
          <h3>Proud partners we have worked with:</h3>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <p>BASE/X Inc. Copyright &copy; 2022</p>
  </footer>

@endif
@stop
