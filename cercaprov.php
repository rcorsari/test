<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOTPATH', __DIR__);
require ROOTPATH."/class.Db.php";           //mette a disposizione la classe

$dbh= new db("127.0.0.1", "provroot", "Mysql,97531", "province");

$pdo = $dbh->connect();


// require "json.post.decode.php";
require ROOTPATH."/class.JsonPOSTDecode.php";//decodifica json

$post = new jsonPOSTdecode();
$decoded = $post->getDecodedJson(); // il json che arriva è un array di oggetti alias un 
                                    // array bidimensionale quindi $decoded[0]['iscli']

// https://stackoverflow.com/a/46013580/3446280 inserisci array associativo come chiavi valori

$query = "
SELECT provincia, sigla 
FROM provReg
WHERE provincia LIKE \"%". $decoded['search'] ."%\" 
OR 
sigla LIKE \"%". $decoded['search'] ."%\"
;";

$stmt = $pdo->query($query);
$rows = $stmt->fetchAll();

// $decoded[0]['riga']="3"; debug

// echo $decoded['search'];

echo json_encode(
    $rows
    //json_encode($decoded) // debug
  );