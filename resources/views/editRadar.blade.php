@extends('layouts.master')

@section('content')

<figure class="highcharts-figure">
    <div id="container"></div>
</figure>

@if(App\Models\User::where('username',session()->get('user'))->first()->isModerator())
<!-- <label for="valueInput" style="width:10%;float:right">Value for the selected item:</label>
<input id="valueInput" style="width:10%;float:right;margin-top:1%"> -->
<button class="edit-button" onclick="openPopupWindow()" style="margin-top:5%">
  <span>Edit entry</span>
</button>
<!-- <button class="edit-button" onclick="chart.fullScreen(true)" style="margin-top:1%">Fullscreen</button> -->
<button class="edit-button" onclick="addSlice()" style="margin-top:2%">
  <span>Add Slice</span>
</button>
<button class="edit-button" onclick="popSlice()" style="margin-top:2%">
  <span>Remove last slice</span>
</button>
<button class="edit-button" onclick="save()" style="background: #0078E7;margin-top:2%;margin-bottom:2%">
  <span>Save</span>
</button>
<fieldset id ="levels" style="margin-right:1%">
<legend>Ring:</legend>
</fieldset>
@endif

<script>
  var radarId = {!! request()->radarId !!};
  // Initialise chart
  var data = {!! $data !!};
  var chart = anychart.sunburst(data, "as-table");
  var level = chart.level(4);
  chart.labels().position("circular");
  // chart.level(1).normal().fill("#96a6a6", 0.4);
  //chart.normal().fill("#96a6a6", 0.4);
  // level.labels({fontColor: 'gray', fontWeight: 600});
  chart.interactivity().selectionMode("single-select");
  chart.title('Project Name: '+'{!! $title !!}');
  // set the container id
  chart.container("container");
  // initiate drawing the chart
  chart.draw();

  // styling
  var center = chart.level(0);
  chart.radius('100%');
  chart.labels({fontColor:'black'});
  chart.labels().fontFamily("Verdana");
  chart.labels().fontWeight(700);
  chart.labels().width("30%");
  chart.labels().height("10%");
  center.labels().width("10%")
  center.labels().height("50%");
  chart.labels().adjustFontSize(true);
  center.labels().adjustFontSize(false);
  center.labels().fontSize('90%');

  chart.selected().fill("#8C8C8C", 0.6);

  //Ring View
  var maxDepth = chart.getStat('treeMaxDepth');
  for (var i = 0; i <= maxDepth; i++) {
  	var enabled = chart.level(i).enabled();
  	$('#levels').append('<input type="checkbox" checked="true"  name="levels" id="level ' + i + '" value="' + i + '" ' + (enabled ? 'checked' : '') + '>');
  	$('#levels').append('<label for="' + i + '">Ring ' + (i+1) + '</label><br>');
  }

  $('input[name=levels]').on('change', function() {
  	chart.level(+$(this).val()).enabled($(this).is(':checked'));
    if(chart.level(0).enabled() == false) {
      chart.level(1).labels().width("20%");
    } else {
      chart.level(1).labels().width("30%");
    }
  });

   // Number of slices
   var slicesBefore = Number(data[data.length-1].id.split('.')[0]);
   var slicesAfter = Number(data[data.length-1].id.split('.')[0]);

  // This function takes the number of the last slice and adds a new slice based on that number
  function addSlice() {
    var lastIndex = data[data.length-1].id.split('.');
    var index = Number(lastIndex[0]) + 1;
    var firstId = index + '.1';
    var secondId = index + '.2';
    var thirdId = index + '.31';
    var fourthId = index + '.32';
    var fifthId = index + '.4';
    var avp = 'Actor Value Proposition ' + index;
    var aca = 'Actor co-production activity ' + index;
    var ac = 'Actor cost ' + index;
    var ab = 'Actor benefit ' + index;
    var act = 'Actor ' + index;
    data.push(
      {name: avp, parent: '0.0', id: firstId},
			{name: aca, parent: firstId, id: secondId},
			{name: ac, parent: secondId, id: thirdId},
      {name: ab, parent: secondId, id: fourthId},
			{name: act, normal: {fill: "white"}, parent: thirdId, id: fifthId}
    );
    chart.data(data, "as-table");
    slicesAfter++;
  }

  // removes the last added slice
  function popSlice() {
    if (slicesAfter > 2) {
      data.pop(); // remove all 4 entries corresponding to this slice
      data.pop();
      data.pop();
      data.pop();
      data.pop();
      chart.data(data, "as-table");
      slicesAfter--;
    } else {
      alert('A valid project must have at least two actors! Last slice could not be removed');
    }
  }

  // edit entries through popupwindow
  var popupWindow;
  var sharedObject = {};

  function openPopupWindow() {

    // if you want to pass e.g. number of slices through shared object
    //sharedObject.slices = slicesAfter;

    // Open the popup window
    popupWindow = this.open('{{ route('editEntry') }}', 'Edit Radar Entry', 'resizable=yes,height=600,width=450,location=0,menubar=0,scrollbars=1');
    if (this.focus) {
      popupWindow.focus();
    }
  }

  // Keep track of the ids of changed entries
  var changedEntries = [];

  function closePopupWindow() {
    popupWindow.close();
    // Retrieve the data from popup popupWindow
    var slice = sharedObject.slice;
    var ring = sharedObject.ring;
    var value = sharedObject.value;
    var changed = false;

    if (slice!=='' && ring!=='' && value!=='') {
      // Search entry in data
      data.forEach(function (entry) {
        if (entry.id === slice + '.' + ring) {
          if (ring==='4') {
            // Add slice number to changed actor name so user knows which slice is which
            value = value + ' (' + slice + ')';
          }
          entry.name = value;
          changedEntries.push(entry.id);
          changed = true;
        }
      });
      if(!changed) {
        alert('Changes could not be executed. Make sure to enter a valid slice number. Current max number for slice is: ' + slices);
      } else {
        chart.data(data, "as-table");
      }
    } else {
      alert('Make sure to enter the number of the slice and the ring in which the entry to change is located as well as the new value of the entry');
    }
  }

  //Edit onclick without popup
  var selectedItem = null;

  chart.listen('pointClick', function(e) {
    if (e.point) {
      selectedItem = e.point;
	  var name = e.point.get("name");
	  $("#valueInput").val(name);
	  console.log(name);
    } else {
    	selectedItem = null;
    }
  });

  function updateInfo() {
    var newName = document.getElementById("valueInput").value;
    newName.innerHTML = 'shh';
    var newData = [newName];
    selectedItem.set('name', newData);
    changedEntries.push(selectedItem.get('id'));
    // console.log(changedEntries);
  }

  // Set up asynchronous request to save the changes
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
  });

  function save() {
    $.ajax({
      url: '/save',
      method: 'POST',
      data: { 'radar': radarId ,'data': data, 'slicesBefore': slicesBefore, 'slicesAfter': slicesAfter, 'changedEntries': changedEntries },
      success: function (data) {
        console.log(data);
        alert('The changes have been successfully changed');
      },
      error: function (data) {
        console.log(data);
      }
    })
  }
</script>
@stop
