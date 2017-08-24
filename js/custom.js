
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