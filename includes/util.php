<?php

	@session_start();

  function createNavBar($page){
	include_once("user_functions.php");
    $page = strtoupper($page);
    
    ?>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="https://profiles.ac3-servers.eu/">Profiles</a></a>
          </div>
          <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
              <li <?php if($page == "HOME") echo("class=\"active\""); ?>><a href="https://profiles.ac3-servers.eu/">HOME</a></li>
              <li <?php if($page == "ABOUT") echo("class=\"active\""); ?>><a href="https://profiles.ac3-servers.eu/about/">ABOUT</a></li>
              <li <?php if($page == "API") echo("class=\"active\""); ?>><a href="https://profiles.ac3-servers.eu/api/">API</a></li>
    <?php
    if(!(isset($_SESSION['user']) && isset($_SESSION['pass']) && validUser($_SESSION['user'], $_SESSION['pass'], true))){
      ?>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown">GUEST <b class="caret"></b></a>
				  <ul class="dropdown-menu">
					<li><a href="/login/">LOGIN</a></li>
					<li><a href="/register/">REGISTER</a></li>
					<li class="divider"></li>
					<li><a href="/contact/">CONTACT</a></li>
				  </ul>
				</li>
      <?php
    }else{
      ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo(htmlentities(ucwords($_SESSION['user']))); ?> <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="/profile.php">YOUR PROFILE</a></li>
                    <li class="divider"></li>
                    <li><a href="/contact/">CONTACT</a></li>
                    <li class="divider"></li>
                    <li><a href="/logout.php">LOG OUT</a></li>
                  </ul>
                </li>
      <?php
    }
    ?>

            </ul>
          </div>
        </div>
      </div>
    <?php
  }
  
  function getFooter(){
	  
	  ?>
		<div id="footerwrap">
			<div class="container">
				<div class="row">
					<div class="col-lg-7">
						<h4>About</h4>
						<div class="hline-w"></div>
							<p>We simply share requests that would normally go straight to mojang, through us, so that you don't have to have the bad requests. However, the bigger this service gets, the more <span style="font-style: italic;">slaves</span> we need. Consisting of one webserver, one file, and one directory, it's not at all big, and the requests are shared between all of our servers evenly!</p>
							<p>Interested? <a href="/register/">Register now!</a></p>
					</div>
					<div class="col-lg-4">
						<h4>Links</h4>
						<div class="hline-w"></div>
						<p>
							<a href="http://ac3-servers.eu/"><i class="fa fa-dribbble"></i></a>
							<a href="http://fbl.me/AC3D"><i class="fa fa-facebook"></i></a>
							<!--<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-instagram"></i></a>
							<a href="#"><i class="fa fa-tumblr"></i></a>-->
						</p>
						<div class="divider"></div>
						<h4>Information</h4>
						<div class="hline-w"></div>
						<p>
						Copyright Cory Redmond | &copy; <?php echo(@date("Y")); ?><br/>
						</p>
						<p><a href="https://dl.dropboxusercontent.com/u/105401917/BlackTie/solid.zip">Site template download</a></p>
					</div>
				</div>
			</div>
		</div>
	  <?php
	  
  }
  
  function getStats($uuid = "ALL"){
	
	@include_once("phpfastcache/phpfastcache.php");
	@include_once("../phpfastcache/phpfastcache.php");
	require_once("api.php");
		
	$cache = phpFastCache();
	//$cache->set("api_statistics_" . $uuid, null, 0);	
	$stats = $cache->get("api_statistics_" . $uuid);
	
	if($stats == null){
		$stats = getStatistics($uuid);
		$cache->set("api_statistics_" . $uuid, $stats, 1200);
	}else{
		echo("<!-- Cached results -->");
	}
	  
	  ?>
		
		<div class="hline"></div>
			<p><i class="fa fa-angle-right"></i> Total API calls<span class="badge badge-theme pull-right"><?php echo(array_sum ( $stats )); ?></span></p>
			<p><i class="fa fa-angle-right"></i> UN2P<span class="badge badge-theme pull-right"><?php if($stats["un2p"] == null) $stat = 0; else $stat = $stats["un2p"]; echo($stat); ?></span></p>
			<p><i class="fa fa-angle-right"></i> U2NH<span class="badge badge-theme pull-right"><?php if($stats["u2nh"] == null) $stat = 0; else $stat = $stats["u2nh"]; echo($stat); ?></span></p>
			<p><i class="fa fa-angle-right"></i> UN2U<span class="badge badge-theme pull-right"><?php if($stats["un2u"] == null) $stat = 0; else $stat = $stats["un2u"]; echo($stat); ?></span></p>
			<p><i class="fa fa-angle-right"></i> U2P<span class="badge badge-theme pull-right"><?php if($stats["u2p"] == null) $stat = 0; else $stat = $stats["u2p"]; echo($stat); ?></span></p>
		<div class="spacing"></div>
	  
	  <?php
  }


	function sendMail($username, $email, $var_link){
		
		@include_once '../PHPMailer/PHPMailerAutoload.php';
		@include_once 'PHPMailerAutoload.php';
		@include_once 'PHPMailer/PHPMailerAutoload.php';

		$results_messages = array();
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		class phpmailerAppException extends phpmailerException
		{
		}

		try {
			$to = $email;
			if (!PHPMailer::validateAddress($to)) {
				throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
			}

			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = "smtp.zoho.com";
			$mail->Port = "465";
			$mail->SMTPSecure = "ssl";
			$mail->SMTPAuth = true;
			$mail->Username = "no-reply@ac3-servers.eu";
			$mail->Password = "N68rEph12";
			$mail->addReplyTo("support@ac3-servers.eu", "Cory Redmond");
			$mail->From = "no-reply@ac3-servers.eu";
			$mail->FromName = "Cory Redmond";
			$mail->addAddress($to);
			$mail->Subject = "AC3-Servers Verification.";
			$username = htmlentities(ucwords($username));
			$body = 		"<h3>Please verify your email.</h3>\n\r";
			$body = $body . "<p>Hello $username!<br/>\n\r";
			$body = $body . "Please <a href=\"$var_link\">click here</a> to verify your email.<br/>Or if that doesn't work, try copying the address from below, and visiting it in your browser.</p>\n\r";
			$body = $body . "<small><small><pre>$var_link</pre></small></small>\n\r";
			$body = $body . "<hr/><br/>Thanks a lot, <a href=\"http://ac3-servers.eu/\">Cory Redmond</a>.";
			$mail->WordWrap = 78;
			$mail->msgHTML($body, dirname(__FILE__) , true); //Create message bodies and embed images
			//$mail->addAttachment('images/phpmailer_mini.png', 'phpmailer_mini.png'); // optional name
			//$mail->addAttachment('images/phpmailer.png', 'phpmailer.png'); // optional name
			try {
				$mail->send();
				$results_messages[] = "A confirmation link has been sent to your inbox!<br/>You will need to open this before you can use the API!<br/><small>Please check your spam box!</small>";
			}

			catch(phpmailerException $e) {
				throw new phpmailerAppException('Unable to send to: ' . $to . ': ' . $e->getMessage());
			}
		}
		catch(phpmailerAppException $e) {
			$results_messages[] = $e->errorMessage();
		}

		return $results_messages;
	}
	
	function apiKeyChange($username, $email, $newKey){
		
		@include_once '../PHPMailer/PHPMailerAutoload.php';
		@include_once 'PHPMailerAutoload.php';
		@include_once 'PHPMailer/PHPMailerAutoload.php';

		$results_messages = array();
		$mail = new PHPMailer(true);
		$mail->CharSet = 'utf-8';
		class phpmailerAppException extends phpmailerException
		{
		}

		try {
			$to = $email;
			if (!PHPMailer::validateAddress($to)) {
				throw new phpmailerAppException("Email address " . $to . " is invalid -- aborting!");
			}

			$mail->isSMTP();
			$mail->SMTPDebug = 0;
			$mail->Host = "smtp.zoho.com";
			$mail->Port = "465";
			$mail->SMTPSecure = "ssl";
			$mail->SMTPAuth = true;
			$mail->Username = "no-reply@ac3-servers.eu";
			$mail->Password = "N68rEph12";
			$mail->addReplyTo("support@ac3-servers.eu", "Cory Redmond");
			$mail->From = "no-reply@ac3-servers.eu";
			$mail->FromName = "Cory Redmond";
			$mail->addAddress($to);
			$mail->Subject = "Your API key was changed.";
			$username = htmlentities(ucwords($username));
			$body = 		"<h3>Your API key was changed.</h3>\n\r";
			$body = $body . "<p>Hello $username!<br/>\n\r";
			$body = $body . "<p>Just to inform you, your API key was changed. If you didn't do this. It might be wise to contact <a href=\"mailto:support@ac3-servers.eu\">support</a> about changing your password.</p>\n\r";
			$body = $body . "<p><br/>Your new key is as follows:<br/><blockquote>$newKey</blockquote></p>\n\r";
			$body = $body . "<hr/><br/>Thanks a lot, <a href=\"http://ac3-servers.eu/\">Cory Redmond</a>.";
			$mail->WordWrap = 78;
			$mail->msgHTML($body, dirname(__FILE__) , true); //Create message bodies and embed images
			//$mail->addAttachment('images/phpmailer_mini.png', 'phpmailer_mini.png'); // optional name
			//$mail->addAttachment('images/phpmailer.png', 'phpmailer.png'); // optional name
			try {
				$mail->send();
			}

			catch(phpmailerException $e) {
				//throw new phpmailerAppException('Unable to send to: ' . $to . ': ' . $e->getMessage());
			}
		}
		catch(phpmailerAppException $e) {
			//$results_messages[] = $e->errorMessage();
		}

		//return $results_messages;
	}

?>
