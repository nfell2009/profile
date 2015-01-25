<?php
    
    include_once("general.php");
    //SELECT * FROM `Blog` ORDER BY `Date` DESC
    
    function getBlogData($from = 0, $limit = 3){
        // Default database connect //
        $msconf = getDatabaseCredentials();
		$dbcon = mysqli_connect(
			$msconf['host'],
			$msconf['user'],
			$msconf['pass'],
			$msconf['db']
		);
		if (mysqli_connect_errno($dbcon)) {
    		echo "Failed to connect to MySQL: " . mysqli_connect_errno($dbcon) . " : " . mysqli_connect_error();
    		return false;
		}
		
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Users` (`Username` varchar(16) NOT NULL, `Name` varchar(60) NOT NULL, `PassHash` varchar(256) NOT NULL, `APIKey` varchar(256) NULL, `Permission` varchar(2) NOT NULL DEFAULT \'NN\', UNIQUE KEY `Username` (`Username`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Blog` (`PUID` varchar(200) NOT NULL,`Post` varchar(10000) NOT NULL,`Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `Author` varchar(16) NOT NULL, `Title` varchar(60) NOT NULL, UNIQUE KEY `PUID` (`PUID`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('INSERT INTO `Users` (`Username`, `Name`, `PassHash`, `Permission`) VALUES (\'ace\', \'Cory Redmond\', \'2y11$WULjGCfjZEvtGEXfZkL3G.uzF3fRlJPGVsR.jCGguRhKIuph28572\', \'YY\');');
		// Default database connect //
		
		$stm = $dbcon->prepare("SELECT * FROM `Blog` ORDER BY `Date` DESC LIMIT ? , ?;");
		$stm->bind_param("ii", $from, $limit);
		$stm->execute();
		$result = $stm->get_result();
		return $result;
    }
    
    function getTotalPosts(){
    	// Default database connect //
        $msconf = getDatabaseCredentials();
		$dbcon = mysqli_connect(
			$msconf['host'],
			$msconf['user'],
			$msconf['pass'],
			$msconf['db']
		);
		if (mysqli_connect_errno($dbcon)) {
    	echo "Failed to connect to MySQL: " . mysqli_connect_errno($dbcon) . " : " . mysqli_connect_error();
    	die();
		}
		
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Users` (`Username` varchar(16) NOT NULL, `Name` varchar(60) NOT NULL, `PassHash` varchar(256) NOT NULL, `APIKey` varchar(256) NULL, `Permission` varchar(2) NOT NULL DEFAULT \'NN\', UNIQUE KEY `Username` (`Username`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Blog` (`PUID` varchar(200) NOT NULL,`Post` varchar(10000) NOT NULL,`Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `Author` varchar(16) NOT NULL, `Title` varchar(60) NOT NULL, UNIQUE KEY `PUID` (`PUID`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('INSERT INTO `Users` (`Username`, `Name`, `PassHash`, `Permission`) VALUES (\'ace\', \'Cory Redmond\', \'2y11$WULjGCfjZEvtGEXfZkL3G.uzF3fRlJPGVsR.jCGguRhKIuph28572\', \'YY\');');
		// Default database connect //
		
		$result = $dbcon->query("SELECT COUNT(*) AS id FROM `Blog`");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		return $row['id'];
		
    }
    
    function newBlog($author = "Nobody", $title = "Blog Post.", $text){
        
        $uid = uniqid("", true);
        
        // Default database connect //
        $msconf = getDatabaseCredentials();
		$dbcon = mysqli_connect(
			$msconf['host'],
			$msconf['user'],
			$msconf['pass'],
			$msconf['db']
		);
		if (mysqli_connect_errno($dbcon)) {
    	echo "Failed to connect to MySQL: " . mysqli_connect_errno($dbcon) . " : " . mysqli_connect_error();
    	die();
		}
		
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Users` (`Username` varchar(16) NOT NULL, `Name` varchar(60) NOT NULL, `PassHash` varchar(256) NOT NULL, `APIKey` varchar(256) NULL, `Permission` varchar(2) NOT NULL DEFAULT \'NN\', UNIQUE KEY `Username` (`Username`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('CREATE TABLE IF NOT EXISTS `Blog` (`PUID` varchar(200) NOT NULL,`Post` varchar(10000) NOT NULL,`Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `Author` varchar(16) NOT NULL, `Title` varchar(60) NOT NULL, UNIQUE KEY `PUID` (`PUID`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$dbcon->query('INSERT INTO `Users` (`Username`, `Name`, `PassHash`, `Permission`) VALUES (\'ace\', \'Cory Redmond\', \'2y11$WULjGCfjZEvtGEXfZkL3G.uzF3fRlJPGVsR.jCGguRhKIuph28572\', \'YY\');');
		// Default database connect //
        
        $stm = $dbcon->prepare("INSERT INTO `ace`.`Blog` (`PUID`, `Post`, `Date`, `Author`, `Title`) VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?);");
		$stm->bind_param("ssss", $uid, $text, $author, $title);
		return $stm->execute();
        
    }
    
