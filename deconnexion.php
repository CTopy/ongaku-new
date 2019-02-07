<head>
    <meta name="robots" content="noindex" />
</head>
<?php
    session_start();
    session_destroy();
    require("param.inc.php");
    $pdo=new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
    $pdo->query("SET NAMES utf8");
    $pdo->query("SET CHARACTER SET 'utf8'");
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    header('Location: index.php');

?>