<?php
session_start();
require('./dbconnect.php');

if (!isset($_REQUEST['id'])) {
  header('Location:index.php');
  exit();
}

$posts = $pdo->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->bindParam(1, $_REQUEST['id'], PDO::PARAM_INT);
$posts->execute();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ひとこと掲示板</title>

  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>ひとこと掲示板</h1>
    </div>
    <div id="content">
      <p>&laquo;<a href="index.php">一覧に戻る</a></p>

      <?php if ($post = $posts->fetch()) : ?>
        <div class="msg">
          <img src="member_picture/<?php print(htmlspecialchars($post['picture'])); ?>" width="80" height="80" />
          <p><?php print(htmlspecialchars($post['message'])); ?><span class="name">（<?php print(htmlspecialchars($post['name'])); ?>）</span></p>
          <p class="day"><?php print(htmlspecialchars($post['created'])); ?></p>
        </div>
      <?php else : ?>
        <p>その投稿は削除されたか、URLが間違えています</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>