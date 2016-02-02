<?php
$username ="";
$password = "";
$errors = array();


if($_POST['username'] == ""){
  $errors[] =  "username is required";
}
if($_POST['password'] == ""){
  $errors[] = "password is required";
}


if(empty($errors) === false){
  foreach ($errors as $x)
  echo "<p style='color:red;'>".$x."</p>";
  die();
}

$username = $_POST['username'];
$password = $_POST['password'];


echo "You typed  $username and $password";

?>