<?php
  require('./dbconnect.php');

  session_start();
  if(isset($_SESSION['id']) && $_SESSION['time']+3600 > time()){
    $_SESSION['time'] = time();

    $members = $pdo->prepare('SELECT * FROM members WHERE id=?');
    $members->bindParam(1,$_SESSION['id'],PDO::PARAM_INT);
    $members->execute();
    $member = $members->fetch();
  }else{
    header('Location:login.php');
    exit();
  }

  if(!empty($_POST)){
    if($_POST['message']!=''){
      $message = $pdo->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');
      $message->bindParam(1, $member['id'], PDO::PARAM_INT);
      $message->bindParam(2, $_POST['message'], PDO::PARAM_INT);
      $message->bindParam(3, $_POST['reply_message_id'], PDO::PARAM_INT);
      $message->execute();

      header('Location:index.php');
      exit();
    }
  }
if(isset($_REQUEST['page'])){
$page =$_REQUEST['page'];
}else{
    $page=1;
  }
  $page=max($page,1);

  $counts = $pdo->query('SELECT COUNT(*) AS cnt FROM posts');
  $cnt = $counts->fetch();
  $maxPage = ceil($cnt['cnt']/5);
  $page = min($page, $maxPage);

  $start = ($page-1)*5;

  $posts = $pdo->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
  $posts->bindParam(1, $start, PDO::PARAM_INT);
  $posts->execute();

  if(isset($_REQUEST['res'])){
    //返信の処理
    $response = $pdo->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
    $response->bindParam(1, $_REQUEST['res'], PDO::PARAM_INT);
    $response->execute();

    $table = $response->fetch();
    $message = '@'.$table['name'].' '.$table['message'];

  }
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
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <div style="text-align: right"><a href="search_form.html">検索</a></div>                                      
    <form action="" method="post">
      <dl>
        <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
        <dd>
<textarea name="message" cols="50" rows="5"><?php if(isset($message))print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

<?php foreach($posts as $post): ?>
    <div class="msg">
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'],ENT_QUOTES)); ?>" />
    <p><small><?php print(htmlspecialchars($post['message'],ENT_QUOTES)); ?><span class="name">　(<?php print(htmlspecialchars($post['name'],ENT_QUOTES)); ?>）</span></small>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'],ENT_QUOTES)); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?php if(!isset($_post['id'])) print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'],ENT_QUOTES)); ?></a>
    
<?php if(isset($post['reply_message_id']) && $post['reply_message_id'] > 0):?>
<a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'])); ?>">
返信元のメッセージ</a>
<?php endif; ?>
    
<?php if($_SESSION['id'] == $post['member_id']): ?>
[<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>"
style="color: #F33;">削除</a>]
<?php endif; ?>
    </p>
    </div>
<?php endforeach;?>

<ul class="paging">
<?php if($page>1): ?>
<li><a href="index.php?page=<?php print($page-1); ?>">前のページへ</a></li>
<?php else: ?>
<li>前のページへ</li>
<?php endif; ?>

<?php if($page<$maxPage):;?>
<li><a href="index.php?page=<?php print($page+1); ?>">次のページへ</a></li>
<?php else: ?>
<li>次のページへ</li>
<?php endif; ?>
</ul>
  </div>
</div>
</body>
      
</html>
