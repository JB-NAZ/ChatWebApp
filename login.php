<?php
session_start();

// POSTリクエストの場合ユーザチェックを行う
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isCheckOK()) {
    // チェック成功の場合
    // チャットページへリダイレクトする
    header('Location:chat.php');
  } else {
    // チェック失敗の場合
    // ログインページへリダイレクトする
    header('Location:login.php');
  }
  exit();
}   

/**
 * ユーザーチェック処理
 */
function isCheckOK()
{
  // 返却用チェック結果、初期値はNG
  $result = false;

  // DB接続パラメータ
  // dbname, user, passswordを各自で設定した値に変更する
  $dns = 'mysql:dbname=test_db;host=localhost';
  $user = 'naz';
  $password = 'password';


  try {
    $dbh = new PDO($dns, $user, $password);
    // ユーザー名とパスワードが一致するレコード件数を取得するSQL
    $stmt = $dbh->prepare('SELECT * FROM `login_table` WHERE `name` = :name AND `password` = :password;');
    // ユーザー名、パスワードをSQL文に設定
    $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
    $stmt->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
    $stmt->execute();

    // 1件だけ取得できた場合ユーザチェックOKとする
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows)) {

      // チャットページで利用するためセッションにユーザーIDを設定
      $_SESSION['id'] = $rows[0]['id'];
      $result = true;
    }

  } catch (PDOException $e) {
    // エラー処理
    print('Error:' . $e->getMessage());
    die();
  }

  return $result;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Page</title>

<style>

    form {
        display: flex;
        flex-direction: column;
        padding: 10% 30%;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 20px;
    }

    input {
        height: 60px;
        border: solid 1px black;
        font-size: 1.4rem;
    }

    .submit {
        margin-top: 30px;
        height: 60px;
        width: 30%;
        border: 1px white solid;
        background-color: #4B9CD3;
        margin-left: 70%;
        border-radius: 10px;
        color: white;
    }

    


</style>
</head>
<body>

<form action="login.php" method="post">

    <label for="user_id">USER ID</label>
    <input type="text" id="user_id" name="name" placeholder="ユーザー名" style="margin-bottom: 10px;">

    <label for="password">PASSWORD</label>
    <input type="password" id="password" name="password" placeholder="パスワード">
    <input type="submit" value="ログイン" class="submit">


</form>
    
</body>
</html>