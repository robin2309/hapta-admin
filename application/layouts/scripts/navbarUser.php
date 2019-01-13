<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
   <a class="testLinkLogoNavbar" href="http://www.haptalyon.fr" target="_blank">
				<div class="testLogoNavbar">Hapta</div>
				<div class="testSubNavbar"><?php echo strtoupper(Zend_Registry::get('City_Location')); ?></div>
		</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      	<li><a href="/"> Dashboard</a></li>
      
	  	<li>
	  	<a href="<?php echo $this->url(array('controller'=>'event','action'=>'index'),null, true); ?>"> Events</a>
	  	</li>
	  	
	  
	  	
	  	<li>
	  	<a href="<?php echo $this->url(array('controller'=>'artist','action'=>'index'),null, true); ?>"> Artistes</a>
	  	</li>
	  	
	  	<li>
	  	<a href="<?php echo $this->url(array('controller'=>'genre','action'=>'index'),null, true); ?>"> Genres</a>
	  	</li>
	  	
	  	<li>
	  	<a href="<?php echo $this->url(array('controller'=>'spot','action'=>'index'),null, true); ?>"> Spot</a>
	  	</li>
	  	
	   </ul>

   	 <?php
     if($hasIdentity){ 
	     $logoutUrl = $this->url(array('controller'=>'auth','action'=>'logout'), null, true);
	 ?>

 	<ul class="nav navbar-top-links navbar-right">
    	<p class="navbar-text">
    		<? echo $user->username ; ?>  /  <a href="<? echo $logoutUrl; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> 
    	</p>
    </ul>
    <? } ?>
    
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
