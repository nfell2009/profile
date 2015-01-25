<?php

	include_once("general.php");
	
	function getStatistics($uuid = "ALL"){

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
		
		
		//Prepare the statment.
		$preparedStm = $dbcon->prepare("SELECT * FROM  `Requests` WHERE  `UUID` = ?;");
		$preparedStm->bind_param("s", $uuid);
		
		//Run the command and get the results.
		$preparedStm->execute();
        $preparedStm->bind_result($r_id, $r_un2p, $r_u2nh, $r_un2u, $r_u2p);
        $preparedStm->fetch();
        
        return array( 
			"un2p" =>$r_un2p, 
			"u2nh" =>$r_u2nh,
			"un2u" =>$r_un2u,
			"u2p"  =>$r_u2p
		);
        
	}
		
	function getServers($uuid){

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
		
		
		//Prepare the statment.
		$preparedStm = $dbcon->prepare("SELECT * FROM `Servers` WHERE  `Owner` =  ?;");
		$preparedStm->bind_param("s", $uuid);
		
		//Run the command and get the results.
		$preparedStm->execute();
        return $preparedStm->get_result();
        
	}
	
    function addServer($uuid, $url){
       
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
		
		$preparedStm = $dbcon->prepare("INSERT INTO `ace`.`Servers` (`URL`, `Owner`, `Enabled`, `Outdated`) VALUES (?, ?, 'false', 'false');");
		$preparedStm->bind_param("ss", $url, $uuid);
	
		if(!$preparedStm->execute()){
		    $errNo = $preparedStm->errno;
		    if($errNo == 1062) return "DUPE";
		}
		return true;
       
    }
