<?php
	
	session_start();

	include_once("../includes/util.php");
	
	$pageName = "About";
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="About the API.">
    <meta name="author" content="Cory Redmond">
    <link rel="shortcut icon" href="/assets/ico/favicon.ico">

    <title>Profiles | <?php echo(ucwords($pageName)); ?></title>

    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="/assets/js/modernizr.js"></script>
  </head>

  <body>

	<?php
		createNavBar($pageName);
	?>

	<div id="blue">
	    <div class="container">
			<div class="row">
				<h3><?php echo(ucwords($pageName)); ?></h3>
				<?php if(isset($error) && !empty($error)){ ?>
					<h2><small><strong><span style="text-color:red!important;"><?php echo($error); ?></span></strong></small></h2>
				<?php } ?>
			</div>
	    </div>
	</div>

	 <div class="container mtb">
	 	<div class="row">
	 	
	 		<div class="col-lg-8">
				<a name="Tutorials"><h3 class="ctitle">Tutorials</h3></a>
				<ul>
					<li><a href="cloudflare_cache.php">Cloudflare Caching</a></li>
					<li><a href="slave_install.php">Slave Installation</a></li>
					<li><a href="api.php">API Usage</a></li>
				</ul>
				<div class="hline"></div>
				<div class="spacing"></div>		
				
		 		<a name="HOW"><h3 class="ctitle">How it works?</h3></a>
		 		<p><csmall>Updated: January 22, 2015.</csmall> | <csmall2>By: Cory Redmond</csmall2></p>
		 		<p>First of all lets go over the problem. Mojang restricts the <i>number of requests</i> per <i>IP address</i> in a certain amount of time. Meaning if 5 requests are sent within 30 seconds, you're likley to be blocked for 2 minuites**. <strong>REALLY SUCKS.</strong></p>
		 		<p>So what we do? We evenly distribute the requests to all of our <i>slaves</i>. Meaning that the chances of the same IP addresses that get the requests are constantly changing, thus no more being blocked. <strong>HOWEVER</strong>, if there aren't enough slaves for the number of requests at the time, then we're back to that same problem.<br/>The slaves will be blocked and decrease over time, then the whole system locks up.</p>
		 		<p>In order to fix that problem, we've got a maximum request policy. However, it's not as brutal. <strong>IT MIGHT BE DYNAMIC!!!</strong> meaning it adjusts itself to the current load.</p>
		 		<p><strong>BUT, It's not just redistribution of the requests. NO!!</strong> Caching will be occouring aswell. Meaning if a request has been made previously, <small>currently set to 5 hours ago</small>, then it will pull from the cache instead of Mojang's servers. Even more decreasing the load and evenly spreading it.</p>
		 		<h4>What can you do?</h4>
		 		<p>There's 2 main ways currently being implemented.</p>
		 		<br/>
		 		<h5 style="color:black" ><Strong>Hosting as many slaves as you can.</Strong></h5>
		 		<p>The light weight slave, is a single PHP script and can be found in the <i>profile</i> tab when you're logged in. All you need is a webserver, with cURL and PHP installed.<br/>Then add the server in the <i>profile</i> tab.</p>
		 		<h5 style="color:black" ><strong>Contributing on GitHub.</strong></h5>
				<p>That's right! We're going full open source. What's to hide?<br/>You can fix a type-o, or add something that could make the site function way better. <strong>PULL REQUESTS ARE WELCOME</strong>.<br/><a><strong><strong>Reserved for GitHub Link</strong></strong></a></p>
				<div class="hline"></div>
				<div class="spacing"></div>
				
			</div>
	 		
	 		
	 		<div class="col-lg-4">
		 		<h4>HELP US!</h4>
		 		<div class="hline"></div>
		 			<p>
						<strong>We need you!</strong><br/>
						That's right, if you own a web server running PHP, and has cURL installed, you can help run this website!<br/>
						Only one directory and one file needs to be on your server.<br/>
						Interested? <a href="/register/">Register now!</a>
		 			</p>
		 			
		 		<div class="spacing"></div>
		 		
		 		<h4>Statistics</h4>
		 		
		 		<?php getStats(); ?>
		 		
	 		</div>
	 	</div>
	 </div>

	<?php
		getFooter();
	?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/retina-1.1.0.js"></script>
	<script src="/assets/js/jquery.hoverdir.js"></script>
	<script src="/assets/js/jquery.hoverex.min.js"></script>
	<script src="/assets/js/jquery.prettyPhoto.js"></script>
  	<script src="/assets/js/jquery.isotope.min.js"></script>
  	<script src="/assets/js/custom.js"></script>
  	<script src='https://www.google.com/recaptcha/api.js'></script>


  </body>
</html>
