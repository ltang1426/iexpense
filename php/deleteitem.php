<?php
	include_once "connectdb.php";

	session_start();

	if(!isset($_SESSION['userid'])){
		header("Location: login.php");
	}

	$id= $_GET['id'];

	$query= "DELETE FROM items WHERE id= ".$id." ";

	$result= mysqli_query($con, $query);
	if($result){
    }else{
		echo "Connection unsuccessful, please try again later.";
	}
?>