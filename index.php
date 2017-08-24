<?php include('includes/connection.php'); ?>
<?php include('includes/functions.php'); ?>

<!-- Import Page Header -->
<?php page_header(); ?>
<!-- Start Container -->
<div class="container-fluid" style="padding:0px 30px 0px 30px;margin-top:-20px;">
  <div class="row">
    <!-- Import the Menu -->
    <?php page_menu(); ?>

      <?php your_quick_fix(); ?>

      <!-- What's Hot -->
      <div class="col-md-6">
        <h1 class="page-header">Buy, Buy, Buy!</h1>
          <?php 
            $sql = "SELECT * FROM _analysis ORDER BY buy_value DESC LIMIT 1";
            $result = mysql_query($sql) or die(mysql_error());
            $row = mysql_fetch_array($result, MYSQL_ASSOC);
            $tick = strtoupper($row["ticker"]); $name = $row['name']; $buy_value = $row["buy_value"];
            echo ("<h3 style='text-align:center;'>" . $name . " (Buy Value: " . $buy_value . ")</h3>");

            $id_sql = "SELECT id FROM _info WHERE ticker='$tick'";
            $id_result = mysql_query($id_sql);
            
            $id_row = mysql_fetch_array($id_result);
            $global_id = $id_row['id'];

            $sql2 = "SELECT date, open, close, amount_change, percent_change FROM _data WHERE stock_id='$global_id' ORDER BY id DESC LIMIT 5";
            $result2 = mysql_query($sql2);
          ?>
          <div id="chart1" class="ct-chart" style="width:100%; height:200px;"></div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead><tr><td>Date</td><td>Open</td><td>Close</td><td>Amount Change</td><td>Percent Change</td></tr></thead>
              <?php
                $i = 0;
                while($rowj = mysql_fetch_array($result2, MYSQL_NUM)) {
                  $old_date = $rowj[0]; $old_date_ts = strtotime($old_date); $new_date = date('F jS, Y', $old_date_ts);
                  echo "<tr><td>" . $new_date . "</td><td>" . $rowj[1] . "</td><td>" . $rowj[2] . "</td>"
                      ."<td>" . $rowj[3] . "</td><td>" . $rowj[4] . "</td></tr>";
                  $date[$i] = $rowj[0];
                  $open[$i] = $rowj[1];
                  $close[$i] = $rowj[2];
                  $i++;
                }
              ?>
            </table>
          </div>
      </div>

      <!-- What's Cold -->
      <div class="col-md-6">
        <h1 class="page-header">Sell, Sell, Sell!</h1>
          <?php
            $sql = "SELECT * FROM _analysis ORDER BY sell_value DESC LIMIT 1";
            $analysis_query = mysql_query($sql) or die(mysql_error());
            $analysis_row = mysql_fetch_array($analysis_query, MYSQL_ASSOC);
            $tick = $analysis_row["ticker"]; $name = $analysis_row['name']; $sell_value = $analysis_row["sell_value"];
            
            echo ("<h3 style='text-align:center;'>" . $name . " (Sell Value: " . $sell_value . ")</h3>");
            $id_sql = "SELECT id FROM _info WHERE ticker='$tick'";
            $id_result = mysql_query($id_sql);
            
            $id_row = mysql_fetch_array($id_result);
            $global_id = $id_row['id'];

            $sql2 = "SELECT date, open, close, amount_change, percent_change FROM _data WHERE stock_id='$global_id' ORDER BY id DESC LIMIT 5";
            $result2 = mysql_query($sql2);
          ?>
          <div id="chart2" class="ct-chart" style="width:100%; height:200px;"></div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead><tr><td>Date</td><td>Open</td><td>Close</td><td>Amount Change</td><td>Percent Change</td></tr></thead>
              <?php
                $i = 0;
                while($rowj = mysql_fetch_array($result2)) {
                  $old_date = $rowj[0]; $old_date_ts = strtotime($old_date); $new_date = date('F jS, Y', $old_date_ts);
                  echo "<tr><td>" . $new_date . "</td><td>" . $rowj[1] . "</td><td>" . $rowj[2] . "</td>"
                     . "<td>" . $rowj[3] . "</td><td>" . $rowj[4] . "</td></tr>";
                  $date2[$i] = $rowj[0];
                  $open2[$i] = $rowj[1];
                  $close2[$i] = $rowj[2];
                  $i++;
                }
              ?>
            </table>
          </div>
      </div>

  </div> <!-- end row -->
</div> <!-- end container fluid -->
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">

        var a = "<?php echo($open[0]); ?>";
        var b = "<?php echo($open[1]); ?>";    
        var c = "<?php echo($open[2]); ?>";
        var d = "<?php echo($open[3]); ?>";   
        var e = "<?php echo($open[4]); ?>";

        var a2 = "<?php echo($close[0]); ?>";
        var b2 = "<?php echo($close[1]); ?>";    
        var c2 = "<?php echo($close[2]); ?>";
        var d2 = "<?php echo($close[3]); ?>";   
        var e2 = "<?php echo($close[4]); ?>";

        var ad = "<?php echo($date[0]); ?>";
        var bd = "<?php echo($date[1]); ?>";    
        var cd = "<?php echo($date[2]); ?>";
        var dd = "<?php echo($date[3]); ?>";   
        var ed = "<?php echo($date[4]); ?>";   

        var chart = new Chartist.Line('#chart1', {
          labels: [ad, bd, cd, dd, ed],
          series: [
            [a, b, c, d, e],
            [a2, b2, c2, d2, e2]
          ]
        }, {
          fullWidth: true,
        });

        chart.on('draw', function(data) {
          if(data.type === 'line' || data.type === 'area') {
            data.element.animate({
              d: {
                begin: 2000 * data.index,
                dur: 2000,
                from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                to: data.path.clone().stringify(),
                easing: Chartist.Svg.Easing.easeOutQuint
              }
            });
          }
        });

        var v = "<?php echo($open2[0]); ?>";
        var w = "<?php echo($open2[1]); ?>";    
        var x = "<?php echo($open2[2]); ?>";
        var y = "<?php echo($open2[3]); ?>";   
        var z = "<?php echo($open2[4]); ?>";     

        var v2 = "<?php echo($close2[0]); ?>";
        var w2 = "<?php echo($close2[1]); ?>";    
        var x2 = "<?php echo($close2[2]); ?>";
        var y2 = "<?php echo($close2[3]); ?>";   
        var z2 = "<?php echo($close2[4]); ?>";

        var vd = "<?php echo($date2[0]); ?>";
        var wd = "<?php echo($date2[1]); ?>";    
        var xd = "<?php echo($date2[2]); ?>";
        var yd = "<?php echo($date2[3]); ?>";   
        var zd = "<?php echo($date2[4]); ?>";   

        var chart2 = new Chartist.Line('#chart2', {
          labels: [vd, wd, xd, yd, zd],
          series: [
            [v, w, x, y, z], 
            [v2, w2, x2, y2, z2]
          ]
        }, {
          fullWidth: true,
        });

        chart2.on('draw', function(data) {
          if(data.type === 'line' || data.type === 'area') {
            data.element.animate({
              d: {
                begin: 2000 * data.index,
                dur: 2000,
                from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                to: data.path.clone().stringify(),
                easing: Chartist.Svg.Easing.easeOutQuint
              }
            });
          }
        });
    </script>
  </body>
</html>