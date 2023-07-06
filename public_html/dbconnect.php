<?php
//データベースへの接続情報の設定
$db_user = "root";
$db_pass = "root";
$db_host = "localhost";
$db_name = "mini_bbs";

$dsn="mysql:host={$db_host};dbname={$db_name};charset=utf8";

//データベースへの接続処理
try{
        $pdo = new PDO($dsn,$db_user,$db_pass);

        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

} catch(PDOException $Exception){
        die("DB接続エラー:".$Exception->getMessage());

}
?>