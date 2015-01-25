<?php
	
	session_start();
	include_once("../includes/user_functions.php");
	include_once("../includes/util.php");
	
	$error = "";
	
	function calcTime($diff){
		if( 1 > $diff ){
		   return "now";
		} else {
		   $w = $diff / 86400 / 7;
		   $d = $diff / 86400 % 7;
		   $h = $diff / 3600 % 24;
		   $m = $diff / 60 % 60; 
		   $s = $diff % 60;

		   return "{$h} hours, {$m} mins, and {$s} seconds";
		}
	}
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['pass']) || !validUser($_SESSION['user'], $_SESSION['pass'], true)){
		header("Location: https://profiles.ac3-servers.eu/login/");
	}
	
	$cache = phpFastCache();
	$reset = $cache->get("reset_" . $_SESSION['user']);
	
	$enabled = true;
	
	if(!isset($reset) || empty($reset) || $reset == null) $enabled = true;
	else if(($reset - time()) < 1) $enabled = true;
	else $enabled = false;
	
	if(isset($_POST['reset'])){
		
		@include_once("../phpfastcache/phpfastcache.php");
		if($reset == null){
			@include_once("../includes/user_functions.php");
			$key = addAPIKey($_SESSION['user']);
			$_SESSION['key'] = $key;
			$reset = time() + 21600;
			$enabled = false;
			$cache->set("reset_" . $_SESSION['user'], $reset, 21600);
			@apiKeyChange($_SESSION['user'], $_SESSION['email'], $key);
		}else{
			$error = "You can't reset your key for " . calcTime($reset - time()) . ".";
		}
		
	}
	
	$pageName = "API";
	
	function convertDate($date){		
		$time = @strtotime($date);
		return @date('d/m/Y H:i', $time);
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="API">
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
		 		<h3 class="ctitle">Your API key.</h3>
		 		<blockquote><?php if(isset($_SESSION['key'])) echo($_SESSION['key']); ?></blockquote>
		 		<p><a href="https://profiles.ac3-servers.eu/about.php">How to use your key.</a></p>
		 		<form method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" >
					<button name="reset" action="submit" onclick="if(!confirm('Are you sure you want to reset your API key?\nEverything using it will stop working!')) return false;" class="btn btn-block btn-primary <?php if(!$enabled) echo("disabled"); ?> btn-danger"><span class="glyphicon glyphicon-retweet"></span> <?php if(!$enabled) echo("You can't reset your key for " . calcTime($reset - time()) . "."); else echo("Recreate API Key"); ?></button>
				</form>
		 		<br/>
		 		<p>If you're here and need the key for a plugin/program, copy this key to your config!</p>
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
		 		<h4>Your Statistics</h4>
		 		<?php getStats($_SESSION['UUID']); ?>
		 		
		 		<div class="spacing"></div>
		 		<h4>Global Statistics</h4>
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
