<?php

function get_stock_name($ticker) {
  $url = "http://finance.yahoo.com/d/quotes.csv?s=$ticker&f=n";
  $content = file_get_contents($url);
  $content = str_replace('"', "", $content);
  $content = str_replace('\'', "\'", $content);
  // cuts of string at period..not sure if needed
  $content = trim($content);
  return ($content);
}

function get_stock_exchange($ticker){
  $url = "http://finance.yahoo.com/d/quotes.csv?s=$ticker&f=x";
  $content = file_get_contents($url);
  $content = str_replace('"', "", $content);
  $content = str_replace('\'', "\'", $content);
  // cuts of string at period..not sure if needed
  $content = trim($content);
  return ($content);
}

function your_quick_fix() { ?>
<h1 class="page-header" style="margin-top:20px;">Your Quick Fix</h1>
<!-- Your Quick Fix -->

<div class="table-responsive">
  <table class="table table-striped">
  <thead>
    <tr>
      <td>Name</td>
      <td>Ticker</td>
      <td>Next Increase</td>
      <td>Percent of Next Increase</td>
      <td>Average Increase Percent</td>
      <td>Next Decrease</td>
      <td>Percent of Next Decrease</td>
      <td>Average Decrease Percent</td>
      <td>Buy Value</td>
      <td>Sell Value</td>
    </tr>
  </thead>
  <tbody>
    <?php

      $num_rows = mysql_query("SELECT * FROM _analysis");
      $num_rows = mysql_num_rows($num_rows);

      $used_ids = array();

      // check if somehow id has already been used
      for ($i = 0; (count($used_ids)) < 10; $i++) {
        // generate new random number
        $random_num = rand(1, $num_rows);

        // generate random numbers until we get
        // one that isn't already stored
        while(in_array($random_num, $used_ids) && !empty($used_ids)) {
          $random_num = rand(1, $num_rows);
        }

        $used_ids[$i] = $random_num;

        $sql = "SELECT * FROM _analysis WHERE id='$used_ids[$i]' LIMIT 1";
        $result = mysql_query($sql);

        while($tab = mysql_fetch_array($result, MYSQL_ASSOC)) {
          echo ("<tr><td><a href='pages/viewStock.php?ticker=" . $tab['ticker'] . "'>" . $tab['name'] . "</a></td>");
          echo ("<td>" . $tab['ticker'] . "</td>");
          echo ("<td>" . $tab['next_increase'] . "</td>");
          echo ("<td>" . $tab['pct_of_next_increase'] . "</td>");
          echo ("<td>" . $tab['avg_increase_percent'] . "</td>");
          echo ("<td>" . $tab['next_decrease'] . "</td>");
          echo ("<td>" . $tab['pct_of_next_decrease'] . "</td>");
          echo ("<td>" . $tab['avg_decrease_percent'] . "</td>");
          echo ("<td>" . $tab['buy_value'] . "</td>");
          echo ("<td>" . $tab['sell_value'] . "</td></tr>");
        }
      }
    ?>
    </tbody>
  </table>  
  <div id="marquee_ticker" class="col col-xs-12 marquee">
  <div>
      <span style="display:inline-block;">
        <?php 
        $num_rows = mysql_query("SELECT * FROM _analysis");
        $num_rows = mysql_num_rows($num_rows);
        $used_ids2 = array();

          // check if somehow id has already been used
          for ($i = 0; (count($used_ids2)) < 10; $i++) {
            // generate new random number
            $random_num = rand(1, $num_rows);

            // generate random numbers until we get
            // one that isn't already stored
            while(in_array($random_num, $used_ids2) && !empty($used_ids2)) {
              $random_num = rand(1, $num_rows);
            }

            $used_ids2[$i] = $random_num;

            $sql = "SELECT * FROM _analysis WHERE id='$used_ids2[$i]' LIMIT 1";
            $result = mysql_query($sql);
            while($tab2 = mysql_fetch_array($result, MYSQL_ASSOC)) {
              echo ("<a href='pages/viewStock.php?ticker=" . $tab2['ticker'] . "' style='color:#FFF !important;padding-left:20px;'>" . $tab2['ticker'] . " (BV: ");
              echo ($tab2['buy_value']);
              echo (" SV: " . $tab2['sell_value']  . ")</a>");
              //echo ($tab2['ticker'] . " ");
              //echo ($tab2['buy_value']);
            }

          }
         ?>
       </span>

   </div> 
   </div>
</div>  
</div>

<?php } function page_header() { // Start layout aimed functions here ?>

<!DOCTYPE HTML>
  <html lang=en>
  <head>
    <link rel="stylesheet" href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo ''; } else { echo '../'; } ?>style.css">
    <title>InvestmentChief - Your Helpful Financial _analysis Online Tool</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo ''; } else { echo '../'; } ?>css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo ''; } else { echo '../'; } ?>dist/chartist.min.js"></script>
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>
      <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="margin-top:-5px;">
              <img src=<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'logo.png'; } else { echo '../logo.png'; } ?> 
              width="35px" height="35px" style="display:inline-block; margin-right:7px; margin-top:-1px;">
              InvestmentChief
            </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <form action="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; }  ?>viewStock.php" class="navbar-form navbar-right" method="GET">
              <input type="text" type="text" class="form-control" name="ticker" placeholder="Quick Ticker Search" />
            </form>
          </div>
        </div>
      </nav> 

<?php } function page_menu() { ?>

  <!-- Start Menu -->
  <div class="col-sm-2 sidebar" id="sideLeft">

    <!-- FIRST SECTION MENU -->
    <ul class="nav nav-sidebar">
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo ''; } else { echo '../'; } ?>index.php">Dashboard
        <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'viewStock.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>viewStock.php">View Stock 
        <?php if(basename($_SERVER['PHP_SELF']) == 'viewStock.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
    </ul>

    <!-- SECOND SECTION MENU -->
    <ul class="nav nav-sidebar">
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'bestBuyValue.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>bestBuyValue.php">Best Buy Value
        <?php if(basename($_SERVER['PHP_SELF']) == 'bestBuyValue.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'bestSellValue.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>bestSellValue.php">Best Sell Value
        <?php if(basename($_SERVER['PHP_SELF']) == 'bestSellValue.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
    </ul>

    <!-- THIRD SECTION MENU -->
    <ul class="nav nav-sidebar">
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'about.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>about.php">About
        <?php if(basename($_SERVER['PHP_SELF']) == 'about.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'premium.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>premium.php">Premium
        <?php if(basename($_SERVER['PHP_SELF']) == 'premium.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
      <li <?php if(basename($_SERVER['PHP_SELF']) == 'contact.php') { echo 'class="active"'; } ?>>
        <a href="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'pages/'; } else { echo ''; } ?>contact.php">Contact
        <?php if(basename($_SERVER['PHP_SELF']) == 'contact.php') { echo '<span class="sr-only">(current)</span>'; } ?></a>
      </li>
    </ul>

  </div>
  <!-- End Menu -->

  <!-- Starting Content Contain -->
  <div class="col-sm-10 main" style="">
    <div class="row">

<?php } function cant_find_ticker() { // cant find your searched ticker ?>

  <div class="panel panel-default" style="width:50%;margin:0 auto;margin-top:15%">
    <h3 class="panel-heading" style="margin-top:0px;">What stock were you lookin' for?</h3>
    <div class="panel-body">
    <p class="bg-warning" style="padding:10px;color: #B3A973;border: 1px solid #E8E1BA;">Hmmm... we couldn't quite seem to find what you were looking for :/</p>
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

<?php } 