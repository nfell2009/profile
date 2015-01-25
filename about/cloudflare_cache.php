<?php
	
	session_start();

	include_once("../includes/util.php");
	
	$pageName = "Cloudflare Caching Tutorial";
	
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
		createNavBar("about");
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
				<a name="Tutorials"><h3 class="ctitle">How to do it.</h3></a>
				<p><csmall>Updated: January 25, 2015.</csmall> | <csmall2>By: Cory Redmond</csmall2></p>
				<ol>
					<li><p>Go to <a href="https://www.cloudflare.com/my-websites" target="_blank">Cloudflare's &quot;Your Websites&quot;</a></p><img src="img/cf_cache_1.png" style="border: 1px solid #999999; max-height:200px; max-width:100%; width:auto; height:auto;" /><br/><br/></li>
					<li><p>Edit your Page Rules.</p><img src="img/cf_cache_2.png" style="border: 1px solid #999999; max-height:200px; max-width:100%; width:auto; height:auto;" /><br/><br/></li>
					<li><p>Enter the URL of the slave script ending in <i>.php</i>.</p><img src="img/cf_cache_3.png" style="border: 1px solid #999999; max-height:200px; max-width:100%; width:auto; height:auto;" /><br/><br/></li>
					<li>Ensure the settings are as follows <small><small>(these are only applied to the following URL)</small></small>:
						<table class="table table-striped table-condensed">
						<tbody>
							<tr>
								<td>Forwarding</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Custom caching</td>
								<td><strong>Bypass Caching</strong></td>
							</tr>
							<tr>
								<td>Always Online</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Apps</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Performance</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Rocket Loader</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Security</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr>
								<td>Security Level</td>
								<td><strong>Essentially Off</strong></td>
							</tr>
							<tr>
								<td>Browser Integrity Check</td>
								<td><strong>OFF</strong></td>
							</tr>
							<tr><td>Anything not included can usually be whatever you want. I'll try and keep this up to date!</td></tr>
						</tbody>
						</table>
						<br/></li>
						<li><p>Don't forget that shiny <i>Add Rule</i> button.</p><img src="img/cf_cache_5.png" style="border: 1px solid #999999; max-height:200px; max-width:100%; width:auto; height:auto;" /><br/><br/></li>
				</ol>
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
