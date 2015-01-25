<?php
    
    include_once("general.php");
    
    function hashPass($user, $pass){
		require("password.php");
		return password_hash($pass, PASSWORD_BCRYPT, [
			'cost' => 11,
			'salt' => "$user Hello fancy world. My name is cory!"
		]);
	}

	function addAPIKey($user){
		$key = substr(preg_replace("/[^a-zA-Z0-9]+/", "", hashPass($user, @date('d/m/Y H:i') . $user . microtime() . time())), 8, -1);
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
		
		$preparedStm = $dbcon->prepare("UPDATE  `Users` SET  `APIKey` = ? WHERE  `Users`.`Username` =  ?;");
		$preparedStm->bind_param("ss", $key, $user);
		$preparedStm->execute();
		
		//var_dump($preparedStm);
		
		return $key;
       
	}

    function addUser($user, $email, $pass, $hashed = false){
       
        //Hash the password if not hashed.
        if(!$hashed){
            $hashedPass = hashPass($user, $pass);
        }else{
            $hashedPass = $pass;
        }
       
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
		
		$ver = substr(preg_replace("/[^a-zA-Z0-9]+/", "", hashPass($user, @date('d/m/Y H:i') . $user . microtime() . time())), -10);
		
		$preparedStm = $dbcon->prepare("INSERT INTO `ace`.`Users` (`Username`, `Email`, `PassHash`, `Verified`, `UUID`) VALUES (?, ?, ?, ?, ?);");
		$preparedStm->bind_param("sssss", $user, $email, $hashedPass, $ver, $ver);
		
		if(!$preparedStm->execute()){
		    $errNo = $preparedStm->errno;
		    if($errNo == 1062) return "DUPE";
		}else{
			@include_once("../includes/util.php");
			@include_once("util.php");
			@include_once("includes/util.php");
			return sendMail($user, $email, "https://profiles.ac3-servers.eu/verify/?c=$ver");
		}
		return true;
       
    }
    
    function removeUser($user){
        
    }
    
    function verify($uuid){
		
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
		
		$preparedStm = $dbcon->prepare("UPDATE `Users` SET `Verified`='Y' WHERE `Verified` = ?;");
		$preparedStm->bind_param("s", $uuid);
		$preparedStm->execute();
		
		$aff = mysqli_stmt_affected_rows($preparedStm);
		
		if($aff > 0) return true;
		return false;
		
	}
    
    function validUser($user, $pass, $hashed = false){
		
		@include_once("phpfastcache/phpfastcache.php");
		@include_once(realpath("../phpfastcache/phpfastcache.php"));
		$cache = phpFastCache();
        
        //Hash the password if not hashed.
        if(!$hashed){
            $hashedPass = hashPass($user, $pass);
        }else{
            $hashedPass = $pass;
        }
        
        $userData = $cache->get("user_data_" . $user);
        
        if($userData == null){
			
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
			$preparedStm = $dbcon->prepare("SELECT * FROM `Users` WHERE `Username` = ? AND `PassHash` = ?;");
			$preparedStm->bind_param("ss", $user, $hashedPass);
			
			//Run the command and get the results.
			$preparedStm->execute();
			$preparedStm->bind_result($f_user, $f_UUID, $f_email, $f_pass, $f_key, $f_permissions, $f_verif);
			$preparedStm->fetch();
			
			//var_dump($preparedStm);
			echo("<!-- $f_verif -->");
						
			if($f_verif == null) return "That account doesn't exist.";
			
			if($f_verif != "Y") return "You are not verified. Please check your email inbox!";
			
			//Return true of false.
			if(((!empty($f_user)) && (!empty($f_pass)) &&($f_user == $user) && ($f_pass == $hashedPass))){
				$userData = array(
						"user" 			=> $f_user,
						"perm" 			=> $f_permissions,
						"hashedPass" 	=> $f_pass,
						"key"			=> $f_key,
						"UUID"			=> $f_UUID,
						"email"			=> $f_email
					);
					
					$cache->set("user_data_" . $f_user, $userData, 600);
			}else return false;
			
        }else{
			echo("<!-- Userdata cached! -->");
		}
        
        if($user == $userData['user'] && $hashedPass == $userData['hashedPass'])
			return $userData;
			
		return false;
		        
    }
    
    function getTotalUsers(){
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
		
		$result = $dbcon->query("SELECT COUNT(*) AS id FROM `Users`");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		return $row['id'];
		
    }
    
    function getTotalTables(){
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
		
		$stmnt = $dbcon->prepare("SELECT count(*) from information_schema.tables AS id WHERE table_schema = ?;");
		$stmnt->bind_param("s", $msconf['db']);
		$stmnt->execute();
		$stmnt->bind_result($id);
		$stmnt->fetch();
		
		return $id;
		
    }
    
    function getUserArray(){
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
		
		$result = $dbcon->query("SELECT * FROM `Users`");
		return $result;
    }
    
    function strToBool($str){
    	if(strtoupper($str) == strtoupper("Y")) return true;
    	return false;
    }
    
    function processPerms($permissionString){
    	$permArray = str_split($permissionString);
    	$rPermArray = array(
    			"blog" 		=> strToBool($permArray[0]),
    			"users" 	=> strToBool($permArray[1])
    		);
    	return $rPermArray;
    }
