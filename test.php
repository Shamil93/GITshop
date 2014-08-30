<?php

//include('utility/handleData.php');
//$pass  = strtolower(handleData($_POST['pass']));
$pass = 123;
$pass  = strrev(md5($pass));
//$pass  = '9nm2rv8q'.$pass.'2yo6z';

$pass_admin  = 'mb03foo51'.$pass.'qj2jjdp9';
echo $pass_admin;

?>