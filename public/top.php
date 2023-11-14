<?php
    require_once '../classes/UserLogic.php';

    //セッション処理
    session_start();

    // エラーメッセージ
    $err=[];

    // バリデーション
    if(!$email = filter_input(INPUT_POST, 'email'))
    {
        $err['email'] = 'メールアドレスを記入してください';
    }
    if(!$password = filter_input(INPUT_POST, 'password'))
    {
        $err['password'] = 'パスワードを記入してください';
    };

    if(count($err)>0)
    {
        //エラーがあった場合は戻す
        $_SESSION = $err;
        header('Location: login.php');
        return;
    }
    // ログイン成功時の処理
    $result = UserLogic::login($email, $password);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ユーザ登録完了画面</title>
    </head>
    <body>
        // ユーザ登録判定
        <?php if (count($err)>0) : ?>
        <?php foreach($err as $e) : ?>
            <p><?php echo $e ?>
        <?php endforeach ?>
        <?php else : ?>
            <p>ユーザ登録が完了しました</p>
        <?php endif ?>

        <a href="./login.php">戻る</a>
    </body>
</html>