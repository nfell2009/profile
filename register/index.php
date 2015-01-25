<?php

	session_start();
	include_once("../includes/user_functions.php");
	
	if(isset($_SESSION['user']) && isset($_SESSION['pass']) && validUser($_SESSION['user'], $_SESSION['pass'], true)){
		header("Location: https://profiles.ac3-servers.eu/api/");
	}

	function confirmURL($response){
		$url = "https://www.google.com/recaptcha/api/siteverify?secret=" . getCaptchaPrivateKey() . "&response=" . $response . "&remoteip=" .$_SERVER['REMOTE_ADDR'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result=curl_exec($ch);
		http_response_code(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		curl_close($ch);

		$res = json_decode($result, true);
		return $res['success'];
	}

	$error = "";

	if(isset($_POST['register']) && strtoupper($_POST['register']) == strtoupper("go")){
		
		//Register button pressed.
		$emailRegex = "^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^"; 
		
		if(!confirmURL($_POST['g-recaptcha-response'])){
			$error="The captcha was incorrect!";
		}else if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['confirm']) || !isset($_POST['Email'])){
			$error = "You're missing a field?";
		}else if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirm']) || empty($_POST['Email'])){
			$error = "You're missing a field?";
		}else if($_POST['password'] != $_POST['confirm']){
			$error = "The password and it's confirmation were not the same!";
		}else if(!preg_match( $emailRegex, $_POST['Email'] )){
			$error = "Invalid email provided.";
		}else{
		
			//User and pass to var.
			$user 		= $_POST['username'];
			$email		= $_POST['Email'];
			$pass 		= $_POST['password'];
			
			$complete = addUser($user, $email, $pass);
			if($complete != "DUPE"){
				addAPIKey($user);
				//$complete[] = "Your API key: " . htmlentities(addAPIKey($user)) . "<br/>This can be retrieved later.";
				
				header('Refresh: 15; URL=https://profiles.ac3-servers.eu/');
				echo("<body><h3>You will be redirected...</h3>");
				echo("<ul>");
				
				foreach($complete as $val) echo("<li>$val</li>");
				
				echo("</ul>");
				exit();
				return;
			} else {
				$error = "That username/email has already been used! Please try another.";
			}			
		}
		
	}

	/*******************************************************/

	include_once("../includes/util.php");
	
	$pageName = "Register";
	
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
					<form method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
						<fieldset>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="username">Username</label>
								<div class="col-md-4">
									<input id="username" name="username" type="text" placeholder="Username" value="<?php if(isset($_POST['username']))echo($_POST['username']); ?>" class="form-control input-md" required="">

								</div>
							</div>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="Email">Email</label>
								<div class="col-md-4">
									<input id="Email" name="Email" type="text" placeholder="Email" value="<?php if(isset($_POST['Email']))echo($_POST['Email']); ?>" class="form-control input-md" required="">

								</div>
							</div>
							
							<hr/>
							<div class="spacing"></div>	

							<!-- Password input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="password">Password</label>
								<div class="col-md-4">
									<input id="password" name="password" type="password" placeholder="Password" class="form-control input-md" required="">

								</div>
							</div>

							<!-- Password input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="confirm">Confirm</label>
								<div class="col-md-4">
									<input id="confirm" name="confirm" type="password" placeholder="Password" class="form-control input-md" required="">
								</div>
							</div>
							
							<hr/>
							<div class="spacing"></div>	

							<div class="form-group">
								<label class="col-md-4 control-label" for="captcha"></label>
								<div  class="col-md-4">
									<div class="g-recaptcha" data-sitekey="6LeH2PkSAAAAAEsEjZ79rT-O9M-7-6VOk4-QkcDK"></div>
								</div>
							</div>
							
							<hr/>
							<div class="spacing"></div>		

							<!-- Button -->
							<div class="form-group" style="text-align:right;">
								<label class="col-md-4 control-label" for="register"></label>
								<div class="col-md-4">
									<button id="register" onclick="if($('#password').val() != $('#confirm').val()){ alert('Password and confirm are not the same!'); return false; }" value="go" name="register" class="btn btn-warning">Register</button>
								</div>
							</div>

						</fieldset>
					</form>
				<p>
				<hr/>				
				<h4 class="ctitle">Already have an account?</h4>
				<p><a href="https://profiles.ac3-servers.eu/login/">Login here!</a><p>
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
