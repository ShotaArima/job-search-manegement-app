<?php
    // sesisonスタート
    session_start();

    // データベース接続
    require_once 'dbconnect.php';

    $err_msg = '';

    if(isset($_POST['login']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try
        {
            $db = connect();
            $sql = 'SELECT user_id, user_pass FROM user WHERE user_name = :username';

            if($db)
            {
                $stmt = $db->prepare($sql);

                // パラメータをバインド
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);

                $stmt->execute();
                $result = $stmt->fetch();
                $stmt = null;
                $db = null;

                if($result !== false && $result !== null)
                {
                    if (password_verify($password, $result['user_pass']))
                    {
                        // ログイン成功時、user_idをセッションに保存
                        $_SESSION['user_id'] = $result['user_id'];
                        header('Location: page_main.php');
                        exit;
                    }
                    else
                    {
                        $err_msg = 'ユーザ名又はパスワードが間違っています。';
                    }
                }
                else
                {
                    $err_msg = 'ユーザ名またはパスワードが間違っています。';
                }
            }

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ログイン画面</title>
    </head>
    <body>
        <h1>ログインフォーム</h1>
        <form action="" method="POST">
            <?php if($err_msg !== null && $err_msg !== '')  echo $err_msg.'<br>'; ?>
            ユーザ名<input type="text" name="username" value=""><br>
            パスワード<input type="password" name="password" value=""><br>
            <input type="submit" name="login" value="ログイン">
        </form>
        <a href="signin.php" name="trans_add">新規登録はこちら</a>
</html>