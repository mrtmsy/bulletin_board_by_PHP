<?php
session_start();
require('./dbconnect.php');

if(isset($_SESSION['id'])){
  $id = $_REQUEST['id'];

  $messages = $pdo->prepare('SELECT * FROM posts WHERE id=?');
  $messages->bindParam(1, $id, PDO::PARAM_INT);
  $messages->execute(array($id));
  $message = $messages->fetch();

  if($message['member_id'] == $_SESSION['id']){
      $del = $pdo->prepare('DELETE FROM posts WHERE id=?');
    $del->bindParam(2, $id, PDO::PARAM_INT);
    $del->execute(array($id));
  }
}

header('Location: index.php');
exit();
?>
