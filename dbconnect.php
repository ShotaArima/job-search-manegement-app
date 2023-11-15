<?php
    require_once 'env.php';
    ini_set('display_errors',true);

    function connect()
    {
        $host= DB_HOST;
        $user= DB_USER;
        $pass= DB_PASS;
        $db= DB_NAME;

        $dsn= "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try{
            $pdo=new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        }
        catch(PDOException $e){
            echo '接続失敗です'.$e->getMessage();
            exit;
        }

    }
?>