<?php

	session_start();
	include_once("../includes/user_functions.php");

	$error = "";
	
	//var_dump($_POST);
	
	if(isset($_SESSION['user']) && isset($_SESSION['pass']) && validUser($_SESSION['user'], $_SESSION['pass'], true)){
		header("Location: https://profiles.ac3-servers.eu/api/");
		return;
	}

	if(isset($_POST['login']) && strtoupper($_POST['login']) == strtoupper("go")){
		
		//Login button pressed.
		
		if(!isset($_POST['user']) || !isset($_POST['password'])){
			$error = "You're missing a field?";
		}
		
		if(empty($_POST['user']) || empty($_POST['password'])){
			$error = "You're missing a field?";
		}
		
		//User and pass to var.
		$user = $_POST['user'];
		$pass = $_POST['password'];
		
		//Hash password and start session if valid.
		$hashedPass = hashPass($user, $pass);
		if(!($userArr = validUser($user, $hashedPass, true))){
			$error = "Your password was incorrect!";
		}else{
			if(!is_array($userArr)){
				 $error = $userArr;
			}else{
				$_SESSION['user'] = $userArr['user'];
				$_SESSION['pass'] = $userArr['hashedPass'];
				$_SESSION['UUID'] = $userArr['UUID'];
				$_SESSION['key'] = $userArr['key'];
				$_SESSION['permissions'] = $userArr['perm'];
				$_SESSION['email'] = $userArr['email'];
				header("Location: https://profiles.ac3-servers.eu/api/");
				return;
			}
		}
	}

	/*******************************************************/

	include_once("../includes/util.php");
	
	$pageName = "Login";
	
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
    <meta name="description" content="Login pls.">
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
				<h3 class="ctitle">Login</h3>
				<p>
					<form class="form-horizontal" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
						<fieldset>
							
							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="user">Username/Email</label>
								<div class="col-md-4">
									<input id="user" name="user" type="text" placeholder="User/Email" class="form-control input-md" required="">

								</div>
							</div>

							<!-- Password input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="password">Password</label>
								<div class="col-md-4">
									<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="">

								</div>
							</div>

							<!-- Multiple Radios -->
							<div class="form-group" style="display: none;">
								<label class="col-md-4 control-label" for="savePass">Save Password</label>
								<div class="col-md-4">
									<div class="radio">
										<label for="savePass-0">
											<input type="radio" name="savePass" id="savePass-0" value="yes">
											<strong>&#x2713;</strong>
										</label>
									</div>
									<div class="radio">
										<label for="savePass-1">
											<input type="radio" name="savePass" id="savePass-1" value="no" checked="checked">
											<strong>&#x2717;</strong>
										</label>
									</div>
								</div>
							</div>

							<!-- Button -->
							<div class="form-group" style="text-align:right;">
								<label class="col-md-4 control-label" for="login"></label>
								<div class="col-md-4">
									<button id="login" action="submit" name="login" value="go" class="btn btn-success">Login</button>
								</div>
							</div>

						</fieldset>
					</form>
				<p>
				<hr/>				
				<h4 class="ctitle">Don't have an account?</h4>
				<p><a href="https://profiles.ac3-servers.eu/register/">Register here!</a><p>
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


  </body>
</html>
