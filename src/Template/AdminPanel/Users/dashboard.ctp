<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?php echo $totalOrders; ?></h3>
        <p>Total Orders</p>
      </div>
      <div class="icon"><i class="ion ion-bag"></i></div>
      <?= $this->Html->link('More info <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Orders', 'action' => 'index'], ['class' => 'small-box-footer' , 'escape' => false]); ?>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3><?php echo $total_users; ?></h3>
        <p>Registered Users</p>
      </div>
      <div class="icon"><i class="ion ion-person-add"></i></div>
      <?= $this->Html->link('More info <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Users', 'action' => 'index'], ['class' => 'small-box-footer' , 'escape' => false]); ?>
    </div>
  </div>
  <!-- ./col -->
</div>
      


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-md-3">
      <?php
        $dropDownOptions = [1 => 'Horoscope Calendar Subscribers', 2 => 'Soft Exit Users'];
        echo $this->Form->create();
        echo $this->Form->input('chartOption', ['type' => 'select', 'options'=>$dropDownOptions, 'class' => 'form-control col-md-3']);
        echo $this->Form->end();
      ?>
    </div>
    <div class="col-md-9 pull-right">
    	<ul class="chart-options">
      		<li id="cal-subs-daily" class="activeChartPeriod">Daily</li>
      		<li id="cal-subs-weekly">Weekly</li>
      		<li id="cal-subs-monthly">Monthly</li>
      		<li id="cal-subs-yearly">Yearly</li>
    	</ul>
    </div>
  <div class="col-md-12">
    <div id="horoscope-calendar-subscriber-chart"></div>
  </div>
</div>

<?php
  $weekDataDes = '';
  $weekNo = date('W')-1; // current week number
  $year = date('Y');
  $limit = 5;
  for($i=0; $i<$limit; $i++){
    /*if($weekNo == 0) {
      $weekNo = 51;
      $year = $year-1;
    }*/
    $year = $year;
    $week = $weekNo;
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('d/m/Y', $time);
    $time += 6*24*3600;
    $return[1] = date('d/m/Y', $time);

    $weekPosition = '';
    $modeVal = ($week+1) % 10;
    switch($modeVal){
      case 1: $weekPosition = ($week+1).'st Week';
      case 2: $weekPosition = ($week+1).'nd Week';
      case 3: $weekPosition = ($week+1).'rd Week';
      default: $weekPosition = ($week+1).'th Week';
    }
    if($i==($limit-1)) {
      $weekDataDes .= $weekPosition.' : '.$return[0].' to '.$return[1];
    } else {
      $weekDataDes .= $weekPosition.' : '.$return[0].' to '.$return[1].', \n';
    }
    $weekNo = $week-1;
  }
?>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  

  $('#chartoption').on('change', function(){
    $(".chart-options li").removeClass("activeChartPeriod");
    $("li#cal-subs-daily").addClass("activeChartPeriod");
    drawChart(1);
  });

  function drawChart(timePeriod='') {
    var adminSelection = $('#chartoption').val();
    $('#horoscope-calendar-subscriber-chart').html('Loading...');
    var XAxisTitle = ['Time Period ( Daily )', 'Time Period ( Weekly )', 'Time Period ( Monthly )', 'Time Period ( Yearly )'];
    var hAxisTitle = chartTitle = '';
    if(timePeriod!='' && timePeriod != undefined) {
      hAxisTitle = XAxisTitle[timePeriod-parseInt(1)];
      
      if (timePeriod==2) {
        hAxisTitle = hAxisTitle+"\n <?= $weekDataDes; ?>";
      }

      if (timePeriod==3) {
        var range = "<?= date('F-Y', strtotime(date('d-m-Y', strtotime('- 11 months')))).' to '.date('F-Y'); ?>";
        hAxisTitle = 'Time Period ( Monthly: '+range+' )';
      }
    } else {
      timePeriod = 1;
      hAxisTitle = XAxisTitle[timePeriod-parseInt(1)];
    }



    if(adminSelection == 2) {
    	chartTitle = 'Registrations through Soft Exit';
    } else {
    	chartTitle = 'Horoscope Calendar Subscribers';
    }

    var calendarPrediction = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'calendar-subscribers']);?>";
    $.ajax({
      type: 'POST',
      url: calendarPrediction,
      data: {'type' : timePeriod, 'adminSelection' : adminSelection},
      success: function (data1) {
        var data = new google.visualization.DataTable();
        var vAxis = 'Number of Subscribers';
        // Add legends with data type
        data.addColumn('string', 'Time of Day');
        if(adminSelection==2) {
          data.addColumn('number', 'No. of Soft Exit Users');
          vAxis = 'Number of Soft Exit Users';
        }/* else if(adminSelection==3) {
          data.addColumn('number', 'No. of Subscribers');
          data.addColumn('number', 'No. of Soft Exit Users');
          vAxis = 'Calendar Subscribers & Soft Exit Users Data';
        }*/ else {
          vAxis = 'Number of Subscribers';
          data.addColumn('number', 'No. of Subscribers');
        }
        //Parse data into Json
        var jsonData = $.parseJSON(data1);
        for (var i = 0; i < jsonData.length; i++) {
          /*if(adminSelection==3){
            data.addRow([jsonData[i].date, parseInt(jsonData[i].value), parseInt(jsonData[i].value2)]);
          } else {*/
            data.addRow([jsonData[i].date, parseInt(jsonData[i].value)]);
          //}
        }
        var options = {
                  pieSliceText: 'label',
                  height: 400,
                  title: chartTitle, //'Horoscope Calendar Subscribers',
                  hAxis: {
                    title: hAxisTitle
                  },
                  vAxis: {
                    title: vAxis
                  },
                };
        var chart = new google.visualization.ColumnChart(document.getElementById('horoscope-calendar-subscriber-chart'));
        chart.draw(data, options);
      }
    });
  }

$('#cal-subs-daily').on('click', function() {
  $(".chart-options li").removeClass("activeChartPeriod");
  $("li#cal-subs-daily").addClass("activeChartPeriod");
  drawChart(1);
});

$('#cal-subs-weekly').on('click', function() {
  $(".chart-options li").removeClass("activeChartPeriod");
  $("li#cal-subs-weekly").addClass("activeChartPeriod");
  drawChart(2);
});

$('#cal-subs-monthly').on('click', function() {
  $(".chart-options li").removeClass("activeChartPeriod");
  $("li#cal-subs-monthly").addClass("activeChartPeriod");
  drawChart(3);
});

$('#cal-subs-yearly').on('click', function() {
  $(".chart-options li").removeClass("activeChartPeriod");
  $("li#cal-subs-yearly").addClass("activeChartPeriod");
  drawChart(4);
});

</script>
<!-- Bar Chart - END -->


<?php //} ?>