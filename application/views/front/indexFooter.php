
<footer class="container-fluid">
  <div class="container">
    <div class="row">
      <p>&copy;<?php echo date('Y'); ?> Dropneed, all rights reserved. Dropneed® is a registered trademarks of HIBRIUS, UAE.</p>
    </div>
  </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="<?php echo site_url(); ?>assets/front/js/bootstrap.min.js"></script> 
<script>
    function height1(){
        var he1= $(window).height(); 
        var wd=$(window).width();
        
        if(wd > 1024){
            
         cc= he1- 200;           
           $(".carousel-inner img").height(cc);
       }
       else{}
    }   
    
        $(document).ready(function(){
        
        height1();
        });
        
        $(window).resize(function(){
            height1();
        });
    </script>
</body>
</html>
