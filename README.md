brute-force-http
================
Labels
jamesjara, bruteforce, bruteforcehttp, Dictionary, php

Brute Force Http Form web logins
Brute forcing consists of systematically enumerating all possible candidates for the solution and checking whether each candidate satisfies the problem's statement. 

[http://www.youtube.com/watch?v=omrV1OIiWHc&feature=youtu.be](http://www.youtube.com/watch?v=omrV1OIiWHc&feature=youtu.be)

In web applications, we usually find a combination of user ID and password. Therefore, it's possible to carry out an attack to retrieve a valid user account and password, by trying to enumerate many (i.e., dictionary attack) or all the possible candidates. 

Discovery Authentication Methods:

Unless an entity decides to apply a sophisticated web authentication, the two most commonly seen methods are as follows:
    HTTP Authentication;
        Basic Access Authentication
        Digest Access Authentication 
    HTML Form-based Authentication; 

IN PHP.

Example:
```php
$partytime = new bruteForceHTTP();

$partytime->setDebug(true);

$partytime->setDictionary('dictionary.txt');

// Use this comodin {PASSWORD} 
$partytime->setAccessUrl('http://www.jamesjara.com/?myusername=jamesjara&mypassword={PASSWORD}&Submit=Login');

$partytime->setTimeOut(10);

//if this word match in the result, then you are in
$partytime->setBingoKeyword('Bienvenido');

$partytime->KillerTime();

```
