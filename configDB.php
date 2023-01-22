<?php
    $dsn = 'mysql:host=localhost;dbname=todo-level';
    $pdo = new PDO($dsn,'root', '');
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
?>

