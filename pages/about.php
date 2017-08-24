<?php include('../includes/connection.php'); ?>
<?php include('../includes/functions.php'); ?>

<!-- Import Page Header -->
<?php page_header(); ?>
<!-- Start Container -->
<div class="container-fluid">
  <div class="row">
    <!-- Import the Menu -->
    <?php page_menu(); ?>
            <div class="col-sm-8 col-sm-offset-2">
              <h1 class="page-header">About</h1>
              <p style="font-size:18px;">Investment Chief is a stock information and analysis website geared towards helping you gain a financial edge on other investors and helping 
              investors supplement their financial knowledge with real stock data and analysis. InvestmentChief gets its financial
              data from <a href="http://finance.yahoo.com" target="_blank">Yahoo Finance.</a>
              </p>
              <h1 class="page-header">Disclaimer</h1>
              <p style="font-size:18px;">Investment Chief is a stock information and analysis website geared to helping you gain a financial edge on other investors. InvestmentChief gets its financial
              data from <a href="http://finance.yahoo.com" target="_blank">Yahoo Finance</a> and cannot be held accountable for incorrect financial information. InvestmentChief
              also cannot be held accountable for any investment losses. 
              <br/><br/>The information on this website is simply intended to supplement your knowledge and help each investor identify 
              stock market patterns. InvestmentChief is in no way shape or form intended to be used as a stock market predictor and therefore InvestmentChief cannot promise positive returns. 
              Use this tool wisely! 
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../js/bootstrap.min.js"></script>
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
    </script>
  </body>
</html>