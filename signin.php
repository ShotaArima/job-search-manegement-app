<?php
    require_once 'dbconnect.php';

    if(isset($_POST['signin']))
    {
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'];

        // フォームバリデーション
        if(empty($username) || empty($password))
        {
            echo "ユーザ名とパスワードは必須項目です。";
            exit;
        }

        // 正規表現を使用して半角英数字のみを確認
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username) || !preg_match('/^[a-zA-Z0-9]+$/', $password))
        {
            echo "ユーザ名とパスワードは半角英数字のみ使用できます。";
            exit;
        }

        try
        {
            // ユーザ名の一意性を確認
            $db = connect();
            $check_username_sql = 'SELECT COUNT(*) FROM user WHERE user_name = ?';
            $stmt_check_username = $db->prepare($check_username_sql);
            $stmt_check_username->execute([$username]);
            $username_exists = $stmt_check_username->fetchColumn();

            if ($username_exists) {
                echo "ユーザ名は既に使用されています。別のユーザ名を選択してください。";
                exit;
            }

            // パスワードのハッシュ化
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO user (user_name, user_pass) VALUES (?, ?)';
            if($db)
            {
                $stmt = $db->prepare($sql);
                $stmt->execute([$username, $hashed_password]);
                $stmt = null;
                $db = null;

                header('Location: index.php');
                exit;
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
        <title>新規登録画面</title>
    </head>
    <body>
        <h1>新規登録</h1>
        <form action="" method="POST">
            ユーザ名<input type="text" name="username" value=""><br>
            パスワード<input type="password" name="password" value=""><br>
            <input type="submit" name="signin" value="新規登録">
        </form>
        <a href="index.php" name="trans_index">戻る</a>
    </body>
</html>