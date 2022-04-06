<DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Radar Entry</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="_token" content="{!! csrf_token() !!}" />
  </head>
  <body>
    <div class="main">
      <p class="sign" align="center">Edit Entry</p>
      <form class="form1">
        <input class="un" type="number" align="center" placeholder="Slice Number (0 for Business Proposition)" id="slice">
        <select class="un" align="center" placeholder="Ring" id="ring" >
          <option value="0">Business Proposition</option>
          <option value="1">Actor Value Proposition</option>
          <option value="2">Actor co-production activity</option>
          <option value="31">Actor cost</option>
          <option value="32">Actor benefit</option>
          <option value="4">Actor</option>
        </select>
        <input class="un" type="text" align="center" placeholder="Value" id="value">
        <button class="submit" onclick="myClose()">Submit</button>
    </div>
  </body>
</html>

<script>
  var sharedObject = window.opener.sharedObject;

  function myClose() {
    sharedObject.slice = document.getElementById('slice').value;
    sharedObject.ring = document.getElementById('ring').value;
    sharedObject.value = document.getElementById('value').value;
    window.opener.closePopupWindow();
  };
</script>
