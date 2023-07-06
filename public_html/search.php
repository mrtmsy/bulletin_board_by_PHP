<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>result</title>
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>検索結果</h1>
    </div>

    <div id="content">
      <?php

      require("./dbconnect.php");

      if (isset($_GET["name"]) && $_GET["name"] != "") {
        $name = $_GET["name"];
      } else {
        $name = "%";
      }

      if (isset($_GET["keyword"]) && $_GET["keyword"] != "") {
        $keyword = $_GET["keyword"];
      } else {
        $keyword = "%";
      }

      $sql = "SELECT members.name, posts.message FROM members, posts WHERE members.id=posts.member_id and (members.name like :name)and (posts.message like :keyword)";

      try {

        $stmh = $pdo->prepare($sql);

        $stmh->bindvalue(":name", "%{$name}%", PDO::PARAM_STR);
        $stmh->bindvalue(":keyword", "%{$keyword}%", PDO::PARAM_STR);
        $stmh->execute();

        $count = $stmh->rowCount();

        print "検索結果は{$count}件です。<br><br>";
      } catch (PDOException $Exception) {
        die("DB検索エラー:" . $Exception->getMessage());
      }


      ?>

      <table border='1' cellpadding='2' cellspacing='0'>
        <h2>検索結果　一覧</h2>

        <tr>
          <th>名前</th>
          <th>メッセージ</th>
          <?php
          $result = $stmh->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result as $row) {
            print "<tr><td>";
            print htmlspecialchars($row["name"], ENT_QUOTES);
            print "</td><td>";
            print htmlspecialchars($row["message"], ENT_QUOTES);
            print "</td></td>";
          }?>
      </table>
      <a href="./index.php">投稿/一覧画面へ</a><br>
      <a href="./search_form.html">もう一度検索</a>
    </div>
</body>

</html>