<?php

	session_start();
	include_once("includes/user_functions.php");
	include_once("includes/api.php");

	$error = "";
	
	//var_dump($_POST);
	
	if(!isset($_SESSION['user']) || !isset($_SESSION['pass']) || !validUser($_SESSION['user'], $_SESSION['pass'], true)){
		header("Location: https://profiles.ac3-servers.eu/login/");
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
	
	function getURL($url, $print = false){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result=curl_exec($ch);
		curl_close($ch);
		if($print) echo($result);
		return $result;
	}

	/*******************************************************/
	
	$error = "";
	//var_dump(hash_file('md5', "slave/slave.php"));

	if(isset($_POST['url']) && !empty($_POST['url'])){
		
		//var_dump($_POST);
		$url_regex = '/(((http|ftp|https):\/{2})+(([0-9a-z_-]+\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mn|mn|mo|mp|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|nom|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ra|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|arpa)(:[0-9]+)?((\/([~0-9a-zA-Z\#\+\%@\.\/_-]+))?(\?[0-9a-zA-Z\+\%@\/&\[\];=_-]+)?)?))\b/imuS';
		
		if(!confirmURL($_POST['g-recaptcha-response'])){
			$error="The captcha was incorrect!";
		}else if(!isset($_POST['url']) || empty($_POST['url'])){
			$error = "The URL you provided is empty?";
		}else if(!preg_match( $url_regex, $_POST['url'] )){
			$error = "Invalid URL provided.";
		}else if(getURL($_POST['url'] . "?mode=hello", false) != "hi_-_" . hash_file('md5', "slave/slave.php")){
			$error = "Invalid slave file.";
			//TODO Check a one of each feature that the file has with a random cached user & uuid.
		}else{
			$complete = addServer($_SESSION['UUID'], $_POST['url']);
			if($complete || $complete != "DUPE"){
				$error = "Your server was sucessfully added! Thanks!";
			} else {
				$error = "That server has already been added!";
			}			
		}
	}

	include_once("includes/util.php");
	
	$pageName = ucwords($_SESSION['user']);
	if(strtolower(substr($pageName, -1)) == "s"){
		$pageName = $pageName . "' Profile.";
	}else{
		$pageName = $pageName . "'s Profile";
	}
	
	function convertDate($date){	
		if($date == null) return "Never";	
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
				<h4 class="ctitle">Your Servers</h4>
				
				<table class="table table-striped table-condensed">
					<thead>
						<tr>
							<th>Select</th>
							<th>ID</th>
							<th>URL</th>
							<th>Enabled</th>
							<th>Indate</th>
							<th>Last Used</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$res = getServers($_SESSION['UUID']);
							while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
								?>
								
								<tr>
									<td><input type="checkbox" name="id" id="id-<?php echo($row['ID']); ?>" value="<?php echo($row['ID']); ?>"></td>
									<td><?php echo($row['ID']); ?></td>
									<td><?php echo($row['URL']); ?></td>
									<td><?php if(strtoupper($row['Enabled']) == strtoupper("true")){ ?> <span class="glyphicon glyphicon-thumbs-up"></span> <?php }else{ ?> <span class="glyphicon glyphicon-thumbs-down"></span> <?php } ?> </td>
									<td><?php if(strtoupper($row['Outdated']) == strtoupper("true")){ ?> <span class="glyphicon glyphicon-thumbs-up"></span> <?php }else{ ?> <span class="glyphicon glyphicon-thumbs-down"></span> <?php } ?> </td>
									<td><?php echo(convertDate($row['LastUse'])); ?></td>
								</tr>
								
								<?php
							}
						?>
					</tbody>
				</table>
				<hr/>
				<button id="AddServer" class="btn btn-block btn-info" data-toggle="modal" data-target="#AddServerModal" ><span class="glyphicon glyphicon-plus-sign"></span> Add A Server <span class="glyphicon glyphicon-plus-sign"></span></button>
				<br/>
				<a href="http://stash.ac3-servers.eu/projects/AC3/repos/profiles-slave/browse/README.MD" class="btn btn-block btn-warning"><span class="glyphicon glyphicon-download-alt"></span> Get The Slave Script <span class="glyphicon glyphicon-download-alt"></span></a>
				<br/>		
				<div class="hline"></div>
				<div class="spacing"></div>		
				
				<h4 class="ctitle">Your Profile</h4>
				<p>Just for you MCLive. I shall var_dump($_SESSION);.</p>
				<br/>
				<pre><?php var_dump($_SESSION); ?></pre>
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
	
	<!-- Modal -->
	<div class="modal fade" id="AddServerModal" tabindex="-1" role="dialog" aria-labelledby="AddServerModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="AddServerModalLabel">Modal title</h4>
				</div>
				<div class="modal-body">
					<form id="addServerForm" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
						<fieldset>

							<!-- Text input-->
							<div class="form-group">
								<label class="col-md-4 control-label" for="url">URL of the slave</label>  
								<div class="col-md-4">
									<input id="url" required name="url" type="text" placeholder="Slave URL" class="form-control input-md">
								</div>
							</div>

							<!-- Captcha -->
							<div class="form-group">
								<label class="col-md-4 control-label" for="captha">Captcha</label>  
								<div class="col-md-4">
									<div id="captha" class="g-recaptcha" data-sitekey="6LeH2PkSAAAAAEsEjZ79rT-O9M-7-6VOk4-QkcDK"></div>
								</div>
							</div>

						</fieldset>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" form="addServerForm" class="btn btn-primary">Add Server</button>
				</div>
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
