<?php
  $pdo = new PDO("mysql:host=localhost; port=3306; dbname=engine; charset=utf8", "test_engine", "Passwd@11");
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $salt = "7Xyt@IohtGdr002P";
?>
