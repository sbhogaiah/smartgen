<?php

   	$db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);

   	if(!$db){
	    die("Connection failed: ".mysqli_connect_error());
	} 