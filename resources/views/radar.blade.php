@extends('layouts.master')

@section('content')


<figure class="highcharts-figure">
    <div id="container"></div>
</figure>

<button class="btn" onclick="exportAsPng()" style="width:10%;margin-top:2%;background-color: #8C8C8C;">
  <span>Export</span>
</button>
<button class="btn" onclick="chart.fullScreen(true)" style="width:10%;margin-top:2%;background-color: #8C8C8C;">Fullscreen</button>

@if(App\Models\User::where('username',session()->get('user'))->first()->isModerator())
<a href="{{ route('editRadar', ['radarId' => request()->radarId]) }}">
  <button class="btn" style="width:10%;margin-top:2%;background-color: #8C8C8C;">
    <span>Edit Project</span></button>
</a>
@endif

<fieldset id ="levels" style="margin-top:5%; margin-right:1%">
<legend>Ring:</legend>
</fieldset>

<script>
  var data = {!! $data !!};
  var chart = anychart.sunburst(data, "as-table");
  var level = chart.level(4);
  chart.labels().position("circular");
  //chart.level(1).normal().fill("#96a6a6", 0.4);
  //chart.normal().fill("#96a6a6", 0.4);
  //level.labels({fontColor: 'gray', fontWeight: 600});
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

  function exportAsPng() {
      // Saves into PNG file.
      chart.saveAsPng(2000, 1200, 0.7, 'Project_{!! $title !!}');
  }
</script>
@stop
