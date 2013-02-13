<?php
include ("settings.php");

$energy= array();

try {
  $dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  /*** echo a message saying we have connected ***/
  echo 'Connected to database<br>';

  /*** Grab latest temperature value ***/
  $sql = "SELECT * FROM $table WHERE sensor_id = 0 and sensor_type = 0 ORDER BY datetime DESC LIMIT 1 OFFSET 0";
  $sth = $dbh->prepare($sql);
  $sth->execute();
  $result = $sth->fetch(PDO::FETCH_ASSOC);
  $tmpr = $result['sensor_value'];

  /*** Grab electricity reading ***/
  for($i=0; $i<10; $i++){
    $sql = "SELECT * FROM $table WHERE sensor_id = $i and sensor_type = 1 ORDER BY datetime DESC LIMIT 1 OFFSET 0";

    $sth = $dbh->prepare($sql);
    $sth->execute();

    /* Fetch all of the remaining rows in the result set */
    // print("Fetch all of the remaining rows in the result set:\n");

    while($result = $sth->fetch(PDO::FETCH_ASSOC)){
      //print_r($result);
      //echo '<br>';
      $energy[$i] = $result['sensor_value'];
    }
  }
  /*** close the database connection ***/
  $dbh = null;
  print_r($energy);
  echo $tmpr;
}
catch(PDOException $e)
  {
  echo $e->getMessage();
  }
?>
<html>
  <head>
    <title>Energy Usage</title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages: ["corechart"]});
      google.load('visualization', '1', {packages: ['gauge']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Temperature', <?php echo $tmpr ?>],
        ]);

        var options = {
          max: 35, min: 10
        };
      
        // Create and draw the visualization.
        new google.visualization.Gauge(document.getElementById('visualization')).
            draw(data,options);
      }
        

        google.setOnLoadCallback(drawVisualization);
      </script>
          <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sensor 0', 'Sensor 5','Sensor 7','Sensor 8'],
          ['',  <?php echo $energy[0] ?>,<?php echo $energy[5] ?>,<?php echo $energy[7] ?>,<?php echo $energy[8] ?>],
        ]);

        var options = {
          title: 'Electricity Usage',
          hAxis: {title: '', titleTextStyle: {color: 'red'}},
          vAxis: {title: 'Watts'}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <h1>Energy Usage</h1>

    Sensor 0: <?php echo $energy[0] ?> watts<br>
    Sensor 5: <?php echo $energy[5] ?> watts<br>
    Sensor 7: <?php echo $energy[7] ?> watts<br>
    Sensor 8: <?php echo $energy[8] ?> watts<br><br>
    Temperature:<?php echo $tmpr ?> &deg;C

<div id="visualization" style="width: 600px; height: 300px;"></div>
<div id="chart_div" style="width: 900px; height: 500px;"></div>

  </body>
</html>
