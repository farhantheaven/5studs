<?php
date_default_timezone_set("Asia/Kolkata");
$session_username="";

function logged_in()
{
	return (isset($_SESSION['username']))?true:false;
}



function user_data($session_username)
{

	$conn = mysqli_connect('localhost','root','','panunproperties');

	$data = array();

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_get_args > 1)
	{
		unset($func_get_args[0]);

		$fields = '`'.implode('`,`',$func_get_args).'`';

		$result = (mysqli_query($conn,"SELECT $fields FROM users WHERE `username` = '$session_username' "));
		//$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM users WHERE user_id = $user_id "));
		$data = mysqli_fetch_array($result);
		return $data;
	}
	return false;
}
if(logged_in() === true)
	$session_username = $_SESSION['username'];

$user_data = array();
$user_data = user_data($session_username,'user_id','username','password','firstName','lastName','email','city','state','postalCode','country');



?>
