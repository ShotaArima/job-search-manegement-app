<?php
    session_start();
    require_once '../functions.php';
    require_once '../classes/UserLogic.php';

    $result = UserLogic::checkLogin();
    if($result)
    {
        header('Location: my_page.php');
        return;
    }

    $login_err = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : null;
    unset($_SESSION['login_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録画面</title>
</head>
<body>
    <h2>ユーザ登録フォーム</h2>
    <?php if(isset($login_err)): ?>
        <p><?php echo $login_err; ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <p>
            <label for="username">ユーザ名：</label>
            <input type="text" name="username" id="username">
            <label for="email">メールアドレス：</label>
            <input type="email" name="email" id="email">
            <label for="password">パスワード：</label>
            <input type="password" name="password" id="password">
            <label for="password_conf">パスワード確認：</label>
            <input type="password" name="password_conf" id="password_comf">
        </p>
        <input typr="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
        <p>
            <input type="submit" value="新規登録">
        </p>
    </form>
</body>
</html>