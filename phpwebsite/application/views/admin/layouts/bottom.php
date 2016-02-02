		</div>
      </div>
    </div>
	<footer>
      <p>
        © 模板样式 2013
      </p>
    </footer>

<div style="position: fixed; z-index: 2147483647;" title="Scroll to top" id="scrollUp">回到<br>顶部</div>

	<script type="text/javascript">
      var st2=document.documentElement.scrollTop;
	  if (st2>200) {
		$("#scrollUp").show();
	  }else{
		$("#scrollUp").hide();
	  }


	  $(window).scroll(function(){
		  var st2=document.documentElement.scrollTop;
		  if (st2>200) {
			  
			  $("#scrollUp").fadeIn();
			  $("#scrollUp").fadeIn("slow");
			  $("#scrollUp").fadeIn(3000);
		  }else{
			  $("#scrollUp").fadeOut();
			  $("#scrollUp").fadeOut("slow");
			  $("#scrollUp").fadeOut(3000);
		  }
	  });
	  $("#scrollUp").click(function(){
		  $('html').animate({scrollTop:0},500);
	  });
	</script>

	<script src="<?php echo base_url();?>style/index/js/tiny-scrollbar.js"></script>
	<script type="text/javascript">
      
      //Animated Pie Charts
      function pie_chart() {
        
        $(function () {
          //create instance
          $('.chart5').easyPieChart({
            animate: 3000,
            barColor: '#F63131',
            trackColor: '#dddddd',
            scaleColor: '#F63131',
            size: 140,
            lineWidth: 6,
          });
          //update instance after 5 sec
          setTimeout(function () {
            $('.chart5').data('easyPieChart').update(30);
          }, 9000);
          setTimeout(function () {
            $('.chart5').data('easyPieChart').update(87);
          }, 19000);
          setTimeout(function () {
            $('.chart5').data('easyPieChart').update(28);
          }, 27000);
          setTimeout(function () {
            $('.chart5').data('easyPieChart').update(69);
          }, 39000);
          setTimeout(function () {
            $('.chart5').data('easyPieChart').update(99);
          }, 47000);
        });
      }
      //Tiny Scrollbar
      $('#scrollbar').tinyscrollbar();
      $('#scrollbar-one').tinyscrollbar();
      $('#scrollbar-two').tinyscrollbar();
      $('#scrollbar-three').tinyscrollbar();


    </script>
	<script src="<?php echo base_url();?>style/admin/js/lee/lee.js"></script>
	<script src="<?php echo base_url();?>style/admin/js/jquery.dataTables.js"></script>
</body>
</html>