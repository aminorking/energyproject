<?php
  include ("settings.php");

  //Page variables
  $page_title = "Home | Energy Project";
  $page_file = basename($_SERVER['PHP_SELF'],".php");




  try {
  $dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  /*** echo a message saying we have connected ***/
  echo 'Connected to database<br>';

  $sql = "SELECT * ,YEAR(date) AS year, month(date) -1 AS month, day(date) AS day FROM 33_bs66nj_daily_averages WHERE sensor_type = 1 ORDER BY `date`, sensor_id";
  $sth = $dbh->prepare($sql);
  $sth->execute();
  $result = $sth->fetchAll(PDO::FETCH_ASSOC);

  //print_r($result);

  /*** close the database connection ***/
  $dbh = null;
  }
  catch(PDOException $e)
  {
  echo $e->getMessage();
  }


  $chartrows = array();
  $numberdays = count($result)/4;
  //print($numberdays);

  for($i=0;$i<count($result);$i=$i+4){
    $chartrows[$i/4] = "[new Date(".$result[$i]['year'].", ".$result[$i]['month']." ,".$result[$i]['day']."), ".$result[$i]['AVG(sensor_value)']." , ".$result[$i+1]['AVG(sensor_value)']." , ".$result[$i+2]['AVG(sensor_value)']." , ".$result[$i+3]['AVG(sensor_value)']."],";
  }
  //print_r($chartrows);
  //print(count($chartrows));

?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php print $page_title; ?></title>

    <?php include "css.php"; ?>

    <script type='text/javascript' src='http://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {'packages':['annotatedtimeline']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Sensor 0');
        data.addColumn('number', 'Sensor 5');
        data.addColumn('number', 'Sensor 7');
        data.addColumn('number', 'Sensor 8');
        data.addRows([
          <?php
            for($i=0; $i<count($chartrows); $i++){
              echo $chartrows[$i];
            }
          ?>
          // [new Date(2008, 1 ,1), 30000,  40645,1,1],
          // [new Date(2008, 1 ,2), 14045,  20374,1,1],
          // [new Date(2008, 1 ,3), 55022,  50766,1,1],
          // [new Date(2008, 1 ,4), 75284,  14334,1,1],
          // [new Date(2008, 1 ,5), 41476,  66467,1,1],
          // [new Date(2008, 1 ,6), 33322,  39463,1,1]
        ]);

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
        chart.draw(data, {displayAnnotations: true});
      }
    </script>

    </head>
  <body>
    <?php include "navbar.php"; ?>

    <div id='chart_div' style='width: 960px; height: 600px; margin-left: auto; margin-right: auto; margin-top: 50px;'></div>

  </body>
</html>