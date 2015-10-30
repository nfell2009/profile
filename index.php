<?php

	session_start();
	
	include_once("includes/util.php");
	require_once("phpfastcache/phpfastcache.php");
	
	$cache = phpFastCache();
	
	$pageName = "Home";
	
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
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <title>Profiles | <?php echo( ucwords( $pageName ) ); ?></title>

    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
        
    <script src="assets/js/modernizr.js"></script>
  </head>

  <body>

	<?php
		createNavBar($pageName);
	?>

	<div id="blue">
	    <div class="container">
			<div class="row">
				<h3><?php echo(ucwords($pageName)); ?></h3>
			</div>
	    </div>
	</div>

	 <div class="container mtb">
	 	<div class="row">
	 		<div class="col-lg-8"><h3 class="ctitle">Abandoned</h3><p>Yeah this is pretty much abandoned.. Sorry.</p></div>
			<div class="col-lg-8">
				<?php
					include("includes/blog.php");

					$blogContent = $cache->get("home_page_blog");
					
					if(!isset($blogContent) || empty($blogContent) || $blogContent == null){
						ob_start();
						
						$res = getBlogData();
						if(empty($res) || $res->num_rows < 1)
							echo("<h4>There's nothing here yet!</h4>");
						else{
							while($row = $res->fetch_assoc()){
								?>
								<h3 class="ctitle"><?php echo(ucwords($row['Title'])); ?></h3>
								<p><csmall><?php echo(ucwords(convertDate($row['Date']))); ?></csmall> | <csmall2>By: <?php echo(ucwords($row['Author'])); ?></csmall2></p>
								<p><?php 
									echo(str_replace(array("<strong/>", "<strong>"), "", $row['Post'])); 
								?></p>
								<div class="hline"></div>
								<div class="spacing"></div>
								<?php
							}
						}

						if(getTotalPosts() > 3) {
						?>
						<strong><a href="/blog">See more...</a></strong>
						<?php
						
						}
						
						$html = ob_get_contents();
						$cache->set("home_page_blog", $html, 3600);
						
						ob_end_clean();
						echo($html);
						
					}else{
						
						echo("<!-- Cached. -->");
						echo($blogContent);
						
					}
				?>
		 		
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

<!--		 		
		 		<h4>Recent Posts</h4>
		 		<div class="hline"></div>
					<ul class="popular-posts">
		                <li>
		                    <a href="#"><img src="assets/img/thumb01.jpg" alt="Popular Post"></a>
		                    <p><a href="#">Lorem ipsum dolor sit amet consectetur adipiscing elit</a></p>
		                    <em>Posted on 02/21/14</em>
		                </li>
		                <li>
		                    <a href="#"><img src="assets/img/thumb02.jpg" alt="Popular Post"></a>
		                    <p><a href="#">Lorem ipsum dolor sit amet consectetur adipiscing elit</a></p>
		                    <em>Posted on 03/01/14</em>
		                <li>
		                    <a href="#"><img src="assets/img/thumb03.jpg" alt="Popular Post"></a>
		                    <p><a href="#">Lorem ipsum dolor sit amet consectetur adipiscing elit</a></p>
		                    <em>Posted on 05/16/14</em>
		                </li>
		                <li>
		                    <a href="#"><img src="assets/img/thumb04.jpg" alt="Popular Post"></a>
		                    <p><a href="#">Lorem ipsum dolor sit amet consectetur adipiscing elit</a></p>
		                    <em>Posted on 05/16/14</em>
		                </li>
		            </ul>
		            		 		
				<div class="spacing"></div>

		 		<h4>Popular Tags</h4>
		 		<div class="hline"></div>
		 			<p>
		            	<a class="btn btn-theme" href="#" role="button">Design</a>
		            	<a class="btn btn-theme" href="#" role="button">Wordpress</a>
		            	<a class="btn btn-theme" href="#" role="button">Flat</a>
		            	<a class="btn btn-theme" href="#" role="button">Modern</a>
		            	<a class="btn btn-theme" href="#" role="button">Wallpaper</a>
		            	<a class="btn btn-theme" href="#" role="button">HTML5</a>
		            	<a class="btn btn-theme" href="#" role="button">Pre-processor</a>
		            	<a class="btn btn-theme" href="#" role="button">Developer</a>
		            	<a class="btn btn-theme" href="#" role="button">Windows</a>
		            	<a class="btn btn-theme" href="#" role="button">Phothosop</a>
		            	<a class="btn btn-theme" href="#" role="button">UX</a>
		            	<a class="btn btn-theme" href="#" role="button">Interface</a>		            	
		            	<a class="btn btn-theme" href="#" role="button">UI</a>		            	
		            	<a class="btn btn-theme" href="#" role="button">Blog</a>		            	
		 			</p>
-->
	 		</div>
	 	</div>
	 </div>

	<?php
		getFooter();
	?>

	 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/retina-1.1.0.js"></script>
	<script src="assets/js/jquery.hoverdir.js"></script>
	<script src="assets/js/jquery.hoverex.min.js"></script>
	<script src="assets/js/jquery.prettyPhoto.js"></script>
  	<script src="assets/js/jquery.isotope.min.js"></script>
  	<script src="assets/js/custom.js"></script>


  </body>
</html>
