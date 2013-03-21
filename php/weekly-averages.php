<?php
  include ("settings.php");

  //Page variables
  $page_title = "Home | Energy Project";
  $page_file = basename($_SERVER['PHP_SELF'],".php");




  try {
  $dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
  /*** echo a message saying we have connected ***/
  echo 'Connected to database<br>';

  $sql = "SELECT * FROM 33_bs66nj_week_averages WHERE sensor_type = 1 ORDER BY week, sensor_id";
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
  $startweek = $result[0]['week'];
  $last = end($result);
  $stopweek = $last['week'];
  $current_result = $result[0];

  for($i=$startweek; $i<=$stopweek;$i++){
    //echo $i*4;
    //echo ($i - $startweek);

    $chartrows[$i - $startweek] = "[" .$i." , "
                                      .$result[($i - $startweek)*4 + 0]['sensor_value_avg'].", "
                                      .$result[($i - $startweek)*4 + 1]['sensor_value_avg'].", "
                                      .$result[($i - $startweek)*4 + 2]['sensor_value_avg'].", "
                                      .$result[($i - $startweek)*4 + 3]['sensor_value_avg']."],";
  }

  //print_r($chartrows);


?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php print $page_title; ?></title>

    <?php include "css.php"; ?>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Week', 'Sensor 0' ,'Sensor 5', 'Sensor 7' , 'Sensor 8'],
          <?php
            for($i=0; $i<count($chartrows); $i++){
              echo $chartrows[$i];
            }
          ?>
          // ['7', 900,      400,200,500],
          // ['8',  1000,      400,200,600],
          // ['9',  1170,      460,200,700],
          // ['10',  660,       1120,200,800],
          // ['11',  1030,      540,200,900],
          // ['12',  1030,      540,200,900],
        ]);

        var options = {
          title: 'Weekly Averages'
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <?php include "navbar.php"; ?>
    <div id="chart_div" style="width: 960px; height: 600px; margin-left: auto; margin-right: auto"></div>
<!--     <?php echo $startweek ?>
    <?php echo $stopweek ?> -->
  </body>
</html>
