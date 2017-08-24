<?php include('../includes/connection.php'); ?>
<?php include('../includes/functions.php'); ?>

<!-- Import Page Header -->
<?php page_header(); ?>
<!-- Start Container -->
<div class="container-fluid">
  <div class="row">
    <!-- Import the Menu -->
    <?php page_menu(); ?>

    <!-- Start Main Content -->
          <?php
          // MUST PREVENT SQL INJECTION
            if (isset($_GET['ticker'])) {
              $ticker = htmlspecialchars($_GET['ticker']);
              $sql_analysis = "SELECT * FROM _analysis WHERE ticker='$ticker'";
              $result = (mysql_query($sql_analysis)) or die(mysql_error());
              if (mysql_num_rows($result) > 1 || mysql_num_rows($result) < 1) {
                // More than one of stock ticker or no stock ticker in DB
                cant_find_ticker();
              } else { 

              $sql_id = "SELECT * FROM _info WHERE ticker='$ticker' LIMIT 1";
              $info_result = mysql_fetch_assoc(mysql_query($sql_id)) or die("eror here" . mysql_error());
              $info_result_final = $info_result['id'];
              $name = $info_result['name'];
              $stock_exchange = $info_result['stock_exchange'];
              $sql_data = "SELECT * FROM _data WHERE stock_id = '$info_result_final'";
              $result2 = mysql_fetch_assoc(mysql_query($sql_data)) or die(mysql_error()); ?>
              <div class="col-md-5">
                <h1><i><?php echo(get_stock_name($ticker)); ?></i></h1>
                <h3><?php echo($stock_exchange) . ": " . ($ticker) . " " . date("F j, Y"); ?></h3>
              </div>
              <div class="col col-md-7">
                <div id="chart1" class="ct-chart" style="width:100%; height:200px;"></div>
              </div>
              <div class="col col-md-12">
                <h2>A deeper analysis of <i><?php echo(strtoupper($ticker)); ?></i> stock</h2><br/>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <tr>
                    <td>Ticker</td><td>Next Increase</td><td>Percent of Next Increase</td>
                    <td>Avg Percent Increase</td> <td>Next Decrease</td><td>Percent of Next Decrease</td> 
                    <td>Avg Next Decrease</td><td>Buy Value</td><td>Sell Value</td>
                    </tr>
                    <?php 
                    while($row = mysql_fetch_array($result, MYSQL_NUM)) {
                      echo "<tr><td>" . $row[1] . "</td>" .
                           "<td>" . $row[3] . "</td>" .
                           "<td>" . $row[4] . "</td>" . 
                           "<td>" . $row[5] . "</td>" . 
                           "<td>" . $row[6] . "</td>" . 
                           "<td>" . $row[7] . "</td>" . 
                           "<td>" . $row[8] . "</td>" . 
                           "<td>" . $row[9] . "</td>" .
                           "<td>" . $row[10] . "</td></tr>";
                    } ?>
                    </tr>
                  </table>

                <h2>The last month of <i><?php echo(strtoupper($ticker)); ?></i> stock</h2><br/>
                <?php

                  $sql_id = "SELECT id FROM _info WHERE ticker = '$ticker'";
                  $stock_id = mysql_fetch_assoc(mysql_query($sql_id)) or die(mysql_error());
                  $stock_id = $stock_id['id'];

                  $sql_data = "SELECT * FROM _data WHERE stock_id = '$stock_id' ORDER BY date DESC LIMIT 15";
                  $result2 = mysql_query($sql_data) or die(mysql_error());

                  if (mysql_num_rows($result2) < 1) { 
                    cant_find_ticker();
                  } else { ?>
                  <table class="table table-striped">
                    <tr style="font-weight:bold;">
                    <td>Date</td><td>Open</td><td>High</td>
                    <td>Low</td> <td>Close</td><td>Volume</td> 
                    <td>Amount Change</td><td>Percent Change</td>
                    </tr>
                    <?php 
                    $i = 0;
                    while($rowj = mysql_fetch_array($result2, MYSQL_NUM)) {
                      if ($rowj[8] > 1.5) { $success = " class='success'"; } else if ($rowj[8] < -1.5) { $success=" class='danger'"; } 
                      else { $success = ""; }
                      if ($i < 5) {
                        $date[$i] = $rowj[2];
                        $open[$i] = $rowj[1];
                        $close[$i] = $rowj[4];
                      }
                      echo "<tr" . $success . "><td>" . $rowj[2] . "</td>" .
                           "<td>" . $rowj[3] . "</td>" . 
                           "<td>" . $rowj[4] . "</td>" . 
                           "<td>" . $rowj[5] . "</td>" . 
                           "<td>" . $rowj[6] . "</td>" . 
                           "<td>" . $rowj[7] . "</td>" . 
                           "<td>" . $rowj[8] . "</td>" . 
                           "<td>" . $rowj[9] . "</td></tr>";
                      $i++; ?>
                    <?php } ?>
                    </tr>
                  </table>
            <?php 
                  } 
                } 
              } else { 
            ?>

              <div class="panel panel-default" style="width:50%;margin:0 auto;margin-top:15%">
                <h3 class="panel-heading" style="margin-top:0px;">What stock were you lookin' for?</h3>
                <div class="panel-body">
                  <form action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <div class="row">
                      <div class="col-lg-12">
                        <span id="box">
                          <input type="text" autocomplete="off" class="form-control" style="border-radius:0px;" name="ticker" id="search_box" placeholder="Search!" autofocus="autofocus" />
                        </span>
                        <div id="search_result"></div>
                        <div style="text-align:center;margin-top:10px;">
                          <span class="col col-xs-12">
                            <button class="btn btn-primary btn-sm" type="submit">Ticker Search</button>
                            <button class="btn btn-success btn-sm" type="button">I'm Feelin' Lucky</button>
                          </span>
                        </div>
                      </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->

                    <div class="row">

                    </div>

                  </form>
                </div>
              </div>

            <?php } ?>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../custom.js"></script>
    <script type="text/javascript">

        var ticker = "<?php echo(strtoupper($ticker)); ?>";
        var a = "<?php echo($close[0]); ?>";
        var b = "<?php echo($close[1]); ?>";    
        var c = "<?php echo($close[2]); ?>";
        var d = "<?php echo($close[3]); ?>";   
        var e = "<?php echo($close[4]); ?>";
        var values = [a, b, c, d, e];

        var ad = "<?php echo($date[0]); ?>";
        var bd = "<?php echo($date[1]); ?>";    
        var cd = "<?php echo($date[2]); ?>";
        var dd = "<?php echo($date[3]); ?>";   
        var ed = "<?php echo($date[4]); ?>"; 

        var length = values.length - 1;
        do {
          var swapped = false;
          for(var i = 0; i < length; ++i) {
            if (values[i] > values[i+1]) {
              var temp = values[i];
              values[i] = values[i+1];
              values[i+1] = temp;
              swapped = true;
            }
          }
        }
        while(swapped == true)

        last = values[length];
        first = values[0];

      new Chartist.Line('.ct-chart', {
        labels: [ad, bd, cd, dd],
        series: [
            {
              name: ticker,
              data: [a, b, c, d, e]
            }
          ]
        }, {
        high: last,
        low: first,
        showArea: true
      });

var $chart = $('.ct-chart');

var $toolTip = $chart
  .append('<div class="tooltip"></div>')
  .find('.tooltip')
  .hide();

$chart.on('mouseenter', '.ct-point', function() {
  var $point = $(this),
    value = $point.attr('ct:value'),
    seriesName = $point.parent().attr('ct:series-name');
  $toolTip.html(seriesName + '<br>' + value).show();
});

$chart.on('mouseleave', '.ct-point', function() {
  $toolTip.hide();
});

$chart.on('mousemove', function(event) {
  $toolTip.css({
    left: (event.offsetX || event.originalEvent.layerX) - $toolTip.width() / 2 - 10,
    top: (event.offsetY || event.originalEvent.layerY) - $toolTip.height() - 40
  });
});

    </script>
  </body>
</html>