  <?php
  session_start();

  /**
   * チャットメッセージ出力
   */
  function outMessages()
  {
    // DB接続パラメータ
    // dbname, user, passswordを各自で設定した値に変更する
    $dns = 'mysql:dbname=test_db;host=localhost';
    $user = 'naz';
    $password = 'password';

    try {
      $dbh = new PDO($dns, $user, $password);
      // メッセージを取得するSQL
      $stmt = $dbh->query('SELECT login_table.id, messages_table.text FROM messages_table INNER JOIN login_table ON messages_table.user_id = login_table.id;');
      $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

      foreach ($messages as $message) {
        // メッセージに紐づいているユーザIDがログインユーザのIDと一致している場合
        if ($message['id'] == $_SESSION['id']) {
          // 自分のメッセージの場合、画面左側に表示する
          echo '<div class="message my-message">' . htmlspecialchars($message['text'], ENT_QUOTES) . '</div>';
        } else {
            echo '<div class="message other-message">' . htmlspecialchars($message['text'], ENT_QUOTES) . '</div>';
        }
      }
    } catch (PDOException $e) {
      // エラー処理
      print('Error:' . $e->getMessage());
      die();
    }
  }

  ?>

  <!DOCTYPE html>
  <html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Page</title>
    <style>
       .chat-container {
            width: 99%;
            height: 600px;;
            margin: 0 auto;
            justify-content: flex-end;
            overflow-y: auto;
            padding: 10px;
            /* background-color: #F0F8FF; */
        }
      .message {
            padding: 10px;
            margin: 1px;
            border-radius: 5px;
            clear: both;
            font-weight: bold;
        }

        .my-message {
            float: right;
            background-color:  #90EE90;
        }

        .other-message {
            float: left;
            background-color: #F0F8FF;
        }

        form {
          display: flex;
          align-items: center;
          justify-content: center;
          margin-top: 30px;
        }

        .input_text {
          height: 40px;
          border: 0.5px solid;
          font-size: 21px;
        }
    </style>
  </head>
  <body>
    <table width="100%">
      <tbody>
        <div class="chat-container">
        <?php outMessages(); ?>
    </div>
      </tbody>
    </table>
    
    <form action="messages_db.php" method="post">
      <input type="text" name="text" class="input_text" style="width: 76%;">
      <!-- <input type="hidden" name="user_id" value="5" style="width: 20%;"> -->
      <input type="submit" value="Send" style="height: 45px;border: none; width: 80px; background-color:#4B9CD3; color: white; font-size: 19px;">
      <button type="button" onclick="location.href='login.php'" style="height: 45px;border: none; width: 130px; background-color:#E52B50; color: white; font-size: 19px; margin-left: 10px">ログアウト</button>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          const chatContainer = document.querySelector('.chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        });
    </script>
  </body>
  </html>