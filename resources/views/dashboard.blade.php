@extends('layouts.master')

@section('content')

  @if(session()->has('loggedin'))

  <section>
      <div class="sidenav">
        <a href="{{ route('createRadar') }}"><button class="prjct-btn" style="vertical-align:middle"><span>New Project </span></button></a>
        <a href="#">Your Projects</a>
        <a href="#">Shared With You</a>
      </div>

      <div class="container">
        <div class="prjct-title">
          <h1>Projects</h1>
        </div>
        <div class="table-wrapper">
          <table id="projects" class="display" style="width:100%">
            <thead>
              <tr>
                  <th>Name</th>
                  <th>Moderator</th>
                  <th>Description</th>
                  <th>Details<th>
                  <th><th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </section>

  <!-- <div class="share-link">
    <a href="javascript:;" class="btn open-modal">Share Link</a>
  </div>
    <div class="modal">
      <div class="modal_head">
        <h2 class="modal_title">Share.</h2>
        <a href="javascript:;" class="modal_close">
          <i class='bx bx-x'></i>
        </a>
      </div>
      <div class="modal_body">
        <h3 class="modal_name">Share this link:</h3>
        <div class="modal_label">
          <input type="text" class="modal_input" value="file:///C:/Users/Max/Documents/Master%20DS/Projects/Project%201/Web/projects.html">
          <button class="link-btn">Copy</button>
        </div>
      </div>
    </div> -->

  <script>

  $(document).ready( function () {

    $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name="_token"]').attr('content') }
    });

    $('#projects').DataTable({
      processing: true,
      ajax: {
            url: '{!! route('projectOverview')!!}',
            error: function (data) {
              console.log(data);
            }
      },
      columns: [
            { data: 'name' },
            { data: 'moderator' },
            { data: 'description' },
            { data: 'link',
              render: function(data, type, row, meta) {
                  if (type === 'display') {
                      data = '<a href="' + data + '">Show</a>';
                  }
                  return data;
              }
            },
            { data: 'link2',
              render: function(data, type, row, meta) {
                  if (type === 'display') {
                      data = '<a href="' + data + '">Add User</a>';
                  }
                  return data;
              },
              visible : "{{ App\Models\User::where('username',session()->get('user'))->first()->isModerator() }}"
            },
      ],
    });
  });
  </script>

  @else
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
