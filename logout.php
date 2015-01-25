<?php
    session_start();
    session_unset(); 
    session_destroy(); 
    
    header("Location: https://profiles.ac3-servers.eu");
?>
