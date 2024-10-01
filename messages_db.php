<?php
session_start();

$dns = 'mysql:dbname=test_db;host=localhost';
$user = 'naz';
$password = 'password';

try {
    $dbh = new PDO($dns, $user, $password);
    // メッセージを追加するSQL
    $stmt = $dbh->prepare("INSERT INTO `messages_table` (`id`, `text`, `user_id`) VALUES (NULL, :text, :user_id)");
    // ユーザー名、パスワードをSQL文に設定
    $stmt->bindValue(':text', $_POST['text'], PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_STR);
    $stmt->execute();

    // チャットページへリダイレクト
    header('Location: chat.php'); // chat.phpに置き換える
    exit;

} catch (PDOException $e) {
    // エラー処理
    print('Error:' . $e->getMessage());
    die();
}