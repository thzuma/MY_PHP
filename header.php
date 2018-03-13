<?php
	$page = $_SESSION['page'];
	$class = array("undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc","undefinedc");
	switch($page){
		case "home.php": $class[0] = "active";
		break;
		
		case "about.php": $class[1] = "active";
		break;
		
		case "sermons.php": $class[2] = "active";
		break;
		
		case "events.php": $class[3] = "active";
		break;
		
		case "news.php": $class[4] = "active";
		break;
		
		case "blogs.php": $class[5] = "active";
		break;
		
		case "gallary.php": $class[6] = "active";
		break;
		
		case "team.php": $class[7] = "active";
		break;
		
		case "more.php": $class[8] = "active";
		break;
		
		case "contact.php": $class[9] = "active";
		break;
		
		case "announcements.php": $class[10] = "active";
		break;
		
		case "login.php": $class[11] = "active";
		break;
		
	}
	
	echo ' <header id="header"> 
    <!--Head Topbar Start-->
    <section class="head-topbar">
      <div class="container holder">
        <div class="left"> <strong class="ph"><i class="fa fa-phone"></i>(033) 456 7980</strong> <a href="mailto:" class="email"><i class="fa fa-envelope"></i>info@prayer.com</a> </div>
        <div class="right">
          <ul class="topbar-social">
            <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
            <li><a href="#"><i class="fa fa-linkedin-square"></i></a></li>
            <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
            <li><a href="#"><i class="fa fa-tumblr-square"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
            <li><a href="#"><i class="fa fa-flickr"></i></a></li>
          </ul>
          <a id="active-btn" href="#" class="btn-search"><i class="fa fa-search"></i></a> <a href="#" class="btn-login"><i class="fa fa-user"></i></a> </div>
        <form action="#" id="search-box-form" class="search-box">
          <input type="text" class="topbar-search-input" placeholder="Search for...">
          <button value="" class="topbar-btn-search"><i class="fa fa-search"></i></button>
          <a href="#" class="crose"><i class="fa fa-times"></i></a>
        </form>
      </div>
    </section>
    <!--Head Topbar End--> 
    
    <!--Logo Row Star-->
    <section class="logo-row">
      <div class="container"> <strong class="logo"><a href="index.php"><img src="images/logo/AFM.jpg" alt="logo" width="50px" height="50p"></a></strong>
        <div class="event-timer"> <strong class="title">Next Event:</strong>
            <div class="countdown countdown-container "
     
     data-border-color="rgba(255, 255, 255,1)">
			<div class="clock">
              <div class="clock-item clock-days countdown-time-value">
                <div class="wrap">
                  <div class="inner">
                    <div id="canvas-days" class="clock-canvas"></div>
                    <div class="text">
                      <p class="valc">'; echo date("d"); echo '</p>
                      <p class="type-days type-time">DAYS</p>
                    </div>
                  </div>
                </div>
                <span class="colun-1">:</span> </div>
              <div class="clock-item clock-hours countdown-time-value">
                <div class="wrap">
                  <div class="inner">
                    <div id="canvas-hours" class="clock-canvas"></div>
                    <div class="text">
                      <p class="valc">'; echo date("h"); echo '</p>
                      <p class="type-hours type-time">HRS</p>
                    </div>
                  </div>
                </div>
                <span class="colun-2">:</span> </div>
              <div class="clock-item clock-minutes countdown-time-value">
                <div class="wrap">
                  <div class="inner">
                    <div id="canvas-minutes" class="clock-canvas"></div>
                    <div class="text">
                      <p class="valc">'; echo date("m"); echo '</p>
                      <p class="type-minutes type-time">MNTS</p>
                    </div>
                  </div>
                </div>
                <span class="colun-3">:</span> </div>
              <div class="clock-item clock-seconds countdown-time-value">
                <div class="wrap">
                  <div class="inner">
                    <div id="canvas-seconds" class="clock-canvas"></div>
                    <div class="text">
                      <p class="valc">'; echo date("s"); echo '</p>
                      <p class="type-seconds type-time">SECS</p>
                    </div>
                  </div>
                </div>
              </div>
			</div>
          </div>
        </div>
      </div>
	  <br/>
	  ';
	  if(isset($_COOKIE["name"])){
	  $name = $_COOKIE["name"];
	  $surname = $_COOKIE["surname"];
	  
	  echo '<h4 style="color: white; text-align: right; font-style: italic;">';
	  echo"$name $surname";
	  }
	  echo'&nbsp&nbsp&nbsp;&nbsp&nbsp&nbsp;</h4>
      <br/>
	</section>
    <!--Logo Row End--> 
    
    <!--Navigation Row Start-->
    <section class="navigation-row">
      <div class="containerr">
        <div role="navigation" class="navbar-inverse">
          <div class="containerr">
            <div class="navbar-header">
              <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            </div>
            <div class="collapse navbar-collapse">
              <nav>
                <ul id="nav">
                  <li class='.$class[0].'><a href="index.php">Home</a></li>
                  
				  <li class='.$class[1].'><a href="about.php">About</a></li>
                  
				  <li class='.$class[2].'><a href="sermon.php">Sermons</a></li>
                  
				  <li class='.$class[3].'><a href="#">Events</a>
                    <ul>
                      <li><a href="events-listing.php">Events Listing</a></li>
                      <li><a href="event-calendar.php">Event Calendar</a></li>
                    </ul>
                  </li>
				  
				  <li class='.$class[10].'><a href="announcements.php">Announcements</a>
                  </li>
                  
				  <li class='.$class[4].'><a href="#">News</a>
                    <ul>
                      <li><a href="news-details.php">News</a></li>
                      <li><a href="news-listing.php">News Listing</a></li>
                    </ul>
                  </li>
                  
				  <li class='.$class[5].'><a href="blog-medium.php">Blog</a></li></li>
                  
				  <li class='.$class[6].'><a href="gallery-3-columns.php">Gallery</a><li>
                  
				  <li class='.$class[7].'><a href="team.php">Team</a></li>
                  
				  <li class='.$class[8].'><a href="prayer-wall.php">Prayer Wall</a>
                  </li>
                  
				  <li class='.$class[9].'><a href="#">Contact</a>
                    <ul>
                      <li><a href="contact.php">Contact</a></li>
                      <li><a href="church-location.php">Church Location</a></li>
                    </ul>
                  </li>
				  <li class='.$class[11].'><a href="login.php">login</a></li>
				  <li class='.$class[11].'><a href="admin/login.php">admin</a></li>
				</ul>
              </nav>
            </div>
            <!--/.nav-collapse --> 
          </div>
        </div>
      </div>
    </section>
    <!--Navigation Row End--> 
  </header>';
?>