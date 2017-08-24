<?php include('../includes/connection.php'); ?>
<?php include('../includes/functions.php'); ?>

<!-- Import Page Header -->
<?php page_header(); ?>
<!-- Start Container -->
<div class="container-fluid" style="padding:0px 30px 0px 30px;margin-top:-20px;">
  <div class="row">
    <!-- Import the Menu -->
    <?php page_menu(); ?>

            <div class="col-md-qw">
              <h1 class="page-header">Best Buy Value (As of <?php echo date('l jS \of F Y'); ?>)</h1>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead><tr><td>Ticker</td><td>Name</td><td>Next Day Increase</td><td>Percent of Next Increase</td><td>Average Increase Percent</td><td>Buy Value</td></tr></thead>
                  <?php 
                    $sql = "SELECT * FROM _analysis ORDER BY buy_value DESC LIMIT 10";
                    $result = mysql_query($sql) or die(mysql_error());
                    $i = 0;
                    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                      $name[$i] = $row['name'];
                      $pct_of_next_increase[$i] = $row['pct_of_next_increase'];
                      $avg_increase_percent[$i] = $row['avg_increase_percent'];
                      $next_increase[$i] = $row['next_increase'];
                      $buy_value = $row['buy_value'];

                      if ($i == 0) {
                        echo "<h3>" . $row['ticker'] . " - " . $row['buy_value'] . "</h3>";
                        $tick = $row["ticker"]; $buy_value = $row["buy_value"];
                        echo "<tr><td>" . $row['ticker'] . "</td><td>"  . $row['name'] . "</td><td>"  . $row['next_increase'] . "</td><td>"  . $row['pct_of_next_increase'] . "</td><td>" . $row['avg_increase_percent'] . "</td><td>" . $row['buy_value'] . "</td></tr>";
                      ?>
                      </table></div>
                      <div id="chart1" class="ct-chart" style="width:100%; height:200px;"></div>
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead><tr><td>Ticker</td><td>Name</td><td>Next Day Increase</td><td>Percent of Next Increase</td><td>Average Increase Percent</td><td>Buy Value</td></tr></thead>
                          
                <?php } else { 
                            $tick = $row["ticker"]; $buy_value = $row["buy_value"];
                            echo "<tr><td>" . $row['ticker'] . "</td><td>"  . $row['name'] . "</td><td>"  . $row['next_increase'] . "</td><td>"  . $row['pct_of_next_increase'] . "</td><td>" . $row['avg_increase_percent'] . "</td><td>" . $row['buy_value'] . "</td></tr>";
                      }
                    $i++;
                    }
                  ?>

            </div>
  
          </div>
        </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">

        var namea = "<?php echo($name[0]); ?>";
        var nameb = "<?php echo($name[1]); ?>";    
        var namec = "<?php echo($name[2]); ?>";
        var named = "<?php echo($name[3]); ?>";   
        var namee = "<?php echo($name[4]); ?>";   

        var a = "<?php echo($next_increase[0]); ?>";
        var b = "<?php echo($next_increase[1]); ?>";    
        var c = "<?php echo($next_increase[2]); ?>";
        var d = "<?php echo($next_increase[3]); ?>";   
        var e = "<?php echo($next_increase[4]); ?>";

        var a2 = "<?php echo($pct_of_next_increase[0]); ?>";
        var b2 = "<?php echo($pct_of_next_increase[1]); ?>";    
        var c2 = "<?php echo($pct_of_next_increase[2]); ?>";
        var d2 = "<?php echo($pct_of_next_increase[3]); ?>";   
        var e2 = "<?php echo($pct_of_next_increase[4]); ?>";

        var a3 = "<?php echo($avg_increase_percent[0]); ?>" * 50;
        var b3 = "<?php echo($avg_increase_percent[1]); ?>" * 50;    
        var c3 = "<?php echo($avg_increase_percent[2]); ?>" * 50;
        var d3 = "<?php echo($avg_increase_percent[3]); ?>" * 50;   
        var e3 = "<?php echo($avg_increase_percent[4]); ?>" * 50;

        var a4 = "<?php echo($buy_value[0]); ?>" * 5;
        var b4 = "<?php echo($buy_value[1]); ?>" * 5;    
        var c4 = "<?php echo($buy_value[2]); ?>" * 5;
        var d4 = "<?php echo($buy_value[3]); ?>" * 5;   
        var e4 = "<?php echo($buy_value[4]); ?>" * 5;

new Chartist.Bar('.ct-chart', {
      labels: [namea, nameb, namec, named, namee],
  series: 
      [[a, b, c, d, e],
      [a2, b2, c2, d2, e2],
      [a3, b3, c3, d3, e3],
      [a4, b4, c4, d4, e4]]
}, {
  // Default mobile configuration
  stackBars: true,
  axisX: {
    labelInterpolationFnc: function(value) {
      return value.split(/\s+/).map(function(word) {
        return word[0];
      }).join('');
    }
  },
  axisY: {
    offset: 20
  }
}, [
  // Options override for media > 400px
  ['screen and (min-width: 400px)', {
    reverseData: true,
    horizontalBars: true,
    axisX: {
      labelInterpolationFnc: Chartist.noop
    },
    axisY: {
      offset: 60
    }
  }],
  // Options override for media > 800px
  ['screen and (min-width: 800px)', {
    stackBars: false,
    seriesBarDistance: 10
  }],
  // Options override for media > 1000px
  ['screen and (min-width: 1000px)', {
    reverseData: false,
    horizontalBars: false,
    seriesBarDistance: 15
  }]
]);
    </script>
  </body>
</html>