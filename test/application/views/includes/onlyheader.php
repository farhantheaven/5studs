
    <div class="navbar navbar-inverse navbar-fixed-top" >
      <div class="container-fluid" style="background-color:red" >

        <!-- Navigation Title -->
        <div class="navbar-header "  >
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#head">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button  style="border:2px solid">
          <a href="<?php echo base_url(); ?>index.php/Main/index" class="navbar-brand" style="color:white">TrendIndia</a>
        </div>

        <div class="collapse navbar-collapse">
			<ul class="nav navbar-nav head">
				<li class="" ><a href="<?php echo base_url(); ?>index.php/Main/index" style="color:white">HOME</a></li>
				<li style="color:white"><a href="<?php echo base_url(); ?>index.php/Main/about" style="color:white">ABOUT</a></li>
				<li><a href="<?php echo base_url(); ?>index.php/Main/contact" style="color:white">CONTACT US</a></li>
				 <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white">POPULER <b class="caret"></b></a>
          <ul class="dropdown-menu" style="background-color:black">
  					<li><a href="#">politics</a></li>
            <li><a href="#">bollywood</a></li>
            <li><a href="#">world</a></li>
            <li><a href="#">hollywood</a></li>
  					<li><a href="#">education</a></li>
            <li><a href="#">Iits/Nits</a></li>
            <li><a href="#">Other </a></li>
          </ul>
        </li>
			</ul>

			<ul class="nav navbar-nav navbar-right head" >

				
							<li><a href="#" role="button" data-toggle="modal" data-target="#login-modal" style="color:white">SIGN IN</a></li>



			</ul>
        </div>
      </div>
    </div>
