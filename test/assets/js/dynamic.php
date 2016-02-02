<?php
header('Content-type:text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
echo '<response>';
  $q=$_GET['q'];
  $citiesArray=array('srinagar','delhi','udaipur','jaipur','kolkata','mumbai','lucknow','hyderabad','jammu');
   if(in_array($q,$citiesArray))
 echo $q;
elseif($q==" ")
echo 'Enter any location you Idiot';
else
echo 'we dont have';
echo '</response>';
?>