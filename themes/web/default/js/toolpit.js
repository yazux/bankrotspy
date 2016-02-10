document.onmousemove = moveTip;
		function moveTip(e) {
		  floatTipStyle = document.getElementById("floatTip").style;
		  w = 0;

		  if (document.all)  { 
			x = event.clientX + document.body.scrollLeft; 
			y = event.clientY + document.body.scrollTop;

		  } else   { 
			x = e.pageX;
			y = e.pageY;
		  }

          console.log(document.body.clientWidth);
		  if ((x + w + 10) < document.body.clientWidth) { 
			floatTipStyle.left = x + 'px';
		  } else { 
			floatTipStyle.left = x - w + 'px';
		  }

		  floatTipStyle.top = y + 20 + 'px';
		}

		function toolTip(msg) {
		  var floatTipStyle = document.getElementById("floatTip").style;
		  if (msg) {

			document.getElementById("TipContainer").innerHTML = msg;

			//floatTipStyle.display = "block";
        $("#floatTip").clearQueue();
        $("#floatTip").stop();
        $('#floatTip').fadeOut(0);

        $('#floatTip').fadeIn(100);
		  } else { 

			//floatTipStyle.display = "none";
        $('#floatTip').fadeOut(100);
		  }

      var height = $("#floatTip").height();
      height = height + 45;
      $('#floatTip').css({'margin-top' : '-' + height + 'px'});
		}