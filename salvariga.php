<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOTPATH', __DIR__);
require ROOTPATH."/class.Db.php";           //mette a disposizione la classe
require ROOTPATH."/pdo.instantiate.php";    //avviamo istanza
// require "json.post.decode.php";
require ROOTPATH."/class.JsonPOSTDecode.php";//decodifica json

$post = new jsonPOSTdecode();
$decoded = $post->getDecodedJson(); // il json che arriva è un array di oggetti alias un 
                                    // array bidimensionale quindi $decoded[0]['iscli']

if (stripos($decoded[0]['iscli'], "si")){ // dalla tabella HTML arriva una stringa
    $decoded[0]['iscli'] = 1; 
} else {
    $decoded[0]['iscli'] = 0; // tattico , se NON c'è scritto si Si sI SI ..
}

// https://stackoverflow.com/a/46013580/3446280 inserisci array associativo come chiavi valori

$stmt = $pdo->prepare("INSERT INTO righe (".implode(', ', array_keys($decoded[0])).") 
                       VALUES (:".implode(', :', array_keys($decoded[0])).")");
$stmt->execute($decoded[0]);

// riga salvata

// ora restituzione JSON della riga inserita

$query = "
    SELECT * , UNIX_TIMESTAMP(data) AS DATE 
    FROM righe 
    ORDER BY DATE DESC 
    LIMIT 10 
";

$stmt = $pdo->query($query);

$rows = $stmt->fetchAll();

$righe = $stmt->rowCount();

$rows[0]['riga'] = strval($righe);

// $decoded[0]['riga']="3"; debug

echo json_encode(
    json_encode($rows[0])
    //json_encode($decoded) // debug
  );