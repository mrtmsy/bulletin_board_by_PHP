<?php
  session_start();
  require('./dbconnect.php');

  if(!empty($_POST)){
    if(isset($_POST['email']) && isset($_POST['password']) && $_POST['email']!=='' && $_POST['password']!==''){
      $login = $pdo->prepare('SELECT * FROM members WHERE email=? AND password=?');
      $login->bindvalue(1,$_POST['email'],PDO::PARAM_STR);
      $login->bindvalue(2,$_POST['password'],PDO::PARAM_STR);
      $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
      ));
      $member = $login->fetch();

      if($member){
        $_SESSION['id'] = $member['id'];
        $_SESSION['time'] = time();

        if($_POST['save']==='on'){
          setcookie('email', $_POST['email'], time()+60*60*24*14);
        }

        header('Location: index.php');
        exit();
      }else{ 
        $error['login'] = 'failed';
      }
    }else{
      $error['login'] = 'blank';
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>メールアドレスとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="join/">入会手続きをする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" 
          value="<?php if(isset($_POST['email'])) print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>" />

          <?php if(isset($error) && ($error['login'])==='blank'): ?>
          <p class="error">* メールアドレスとパスワードをご記入ください。</p>
          <?php endif; ?>
          <?php if(isset($error) && ($error['login'])==='failed'): ?>
          <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255"
          value="<?php if(isset($_POST['password'])) print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
</div>
</body>

 
</html>
