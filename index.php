<?php
    require_once 'dbconnect.php';

    $err_msg = '';

    if(isset($_POST['login']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try
        {
            $db = connect();
            $sql = 'select * from user where user_name=? and user_pass=?';
            if($db)
            {
                $stmt = $db->prepare($sql);
                $stmt->execute(array($username, $password));
                $result = $stmt->fetch();
                $stmt = null;
                $db = null;

                if($result !== false && $result !== null)
                {
                    // ログイン成功時、user_idをhiddenフィールドに追加
                    $user_id = $result['user_id'];
                    echo '<form id="loginForm" action="page_main.php" method="POST">';
                    echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
                    echo '</form>';
                    echo '<script>document.getElementById("loginForm").submit();</script>';
                    exit;
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