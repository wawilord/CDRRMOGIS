function drawHBarChart(target, data, params) {
  var $target = $(target);
  if ($target.length === 0) {
    return;
  }

  // default params
  var p = {
    containerClassName: 'bs-bar-chart',  // detect if the container already init.
                                         // override if you need another class name
    initAnimation: true,
    max: 0,
    xInc: 3,
    labelWidth: 3,                  // bootstrap grid col width (i.e. 3/12)
    rgbaFrom: [66, 139, 202, 1],    // ignore if colors is not null
    rgbaTo: [215, 230, 244, 1],     // ignore if colors is not null
    colors: null
  };

  // set input params
  for (var k in params) {
    p[k] = params[k];
  }

  var firstTime = !$target.hasClass(p.containerClassName);
  $target.addClass(p.containerClassName);

  // sort the data and remove extra items
 /*
  data = data.sort(function(a, b) {
    return a.count==b.count?(b.name<a.name?-1:1):(b.count-a.count);
  });
  if (p.max > 0) {
    data = $.grep(data, function(e, i) { return i < p.max && e.count > 0; });
  }
  */
  // find the max x-axis value, if the new value is greater than the chart
  // increase the chart's x-axis max value
  var maxCount = 0;
  var currXMax = $target.data('xaxis-max') || 0;
  for (var i=0 ; i<data.length ; i++) {
    maxCount = Math.max(maxCount, data[i].count);
  }
  if (maxCount > currXMax) {
    currXMax = (maxCount + p.xInc) * 1.0;
    $target.data('xaxis-max', currXMax);
  }
  for (var i=0 ; i<data.length ; i++) {
    var d = data[i];
    var name = d.name;
    var pct = d.count * 100 / currXMax;
    var color;
    if (p.colors) {
      color = p.colors[i % p.colors.length];
    }
    else {
      color = 'rgba(' +
        Math.round((p.rgbaFrom[0]+(p.rgbaFrom[0]>p.rgbaTo[0]?-1:1)*Math.abs(p.rgbaTo[0]-p.rgbaFrom[0])/(Math.max(p.max,data.length)-1)*i)) + ',' +
        Math.round((p.rgbaFrom[1]+(p.rgbaFrom[1]>p.rgbaTo[1]?-1:1)*Math.abs(p.rgbaTo[1]-p.rgbaFrom[1])/(Math.max(p.max,data.length)-1)*i)) + ',' +
        Math.round((p.rgbaFrom[2]+(p.rgbaFrom[2]>p.rgbaTo[2]?-1:1)*Math.abs(p.rgbaTo[2]-p.rgbaFrom[2])/(Math.max(p.max,data.length)-1)*i)) + ',' +
                   (p.rgbaFrom[3]+(p.rgbaFrom[3]>p.rgbaTo[3]?-1:1)*Math.abs(p.rgbaTo[3]-p.rgbaFrom[3])/(Math.max(p.max,data.length)-1)*i) + ')';
    }
    var $bar = $target.find('> div:nth-child(' + (i+1) + ')');
    if (firstTime || $bar.length === 0) {
      var $newbar = $('<div class="row" data-item-id="' + d.id +
                      '" style="margin-top:3px;"><div class="col-sm-' + p.labelWidth + ' chart-name">' + name +
                      '</div><div class="col-sm-' + (12 - p.labelWidth) + '"><div class="progress-bar"' +
                      ' data-percentage="' + pct + '" style="background-color:' + color +
                      ';width:' + (firstTime && p.initAnimation?0:pct) + '%;">&nbsp;</div></div></div>');
      $target.append($newbar);
      if (!firstTime) {
        $newbar.hide().fadeIn();
      }
    }
    else {
      $bar.find('.progress-bar').css({ 'width': pct+'%' });
      if ($bar.attr('data-item-id') != d.id) {
        $bar.attr('data-item-id', d.id).find('.chart-name').attr('data-anim-name', name).fadeOut(function() {
          $(this).html($(this).attr('data-anim-name')).removeAttr('data-anim-name').fadeIn();
        });
      }
    }
  }
  if (firstTime && p.initAnimation) {
    // animate
    window.setTimeout(function() {
      $target.find('.progress-bar').each(function() {
        $(this).css({'width':$(this).data('percentage') + '%'});
      });
    }, 0);
  }
  else {
    // removed extra bars (due to the count change to 0)
    var extraBarSel = '> div';
    if (data.length > 0) {
      // jQuery :gt(0) doesn't work so this 'if' is necessary
      extraBarSel += ':gt(' + (data.length - 1) + ')';
    }
    $target.find(extraBarSel).fadeOut(function() { $(this).remove(); });
  }
}