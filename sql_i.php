<?php
  $name = "鈴木'OR'1' = '1";

  $dsn = 'mysql:host=localhost;dbname=tennis;charset=utf8';
  $user = 'tennisuser';
  $password = 'password';

  try {
    $db = new PDO($dsn,$user, $password);

    $records = $db->query("SELECT * FROM users WHERE name= '$name'");
    foreach ($records as $row) {
      echo '<p>ID:'.$row['id'].'</p>';
      echo '<p>name:'.$row['name'].'</p>';
      echo '<p>password:'.$row['password'].'</p>';
      echo '<hr>';
    }
  } catch (PDOExceptoin $e ){
    exit('エラー:'.$e->getMessage());
  }

?>