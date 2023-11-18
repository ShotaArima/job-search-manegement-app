<?php
    // sessionスタート
    session_start();

    // データベース接続
    require_once 'dbconnect.php';

    if(isset($_SESSION['user_id']))
    {
        $id = $_SESSION['user_id'];

        try
        {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['company_name']))
            {
                $db = connect();
                $sql = 'INSERT INTO company (user_id, company_name) VALUES (?, ?)';

                if($db)
                {
                    $stmt = $db->prepare($sql);
                    $companyname = $_POST['company_name'];
                    $stmt->execute(array($id,$companyname));
                    $stmt = null;
                    $db = null;

                    header('Location: page_main.php');
                    exit;
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            exit;
        }
    }
    else
    {
        // ユーザーIDがセッションにない場合の処理（未ログインなど）
        // ここに適切な処理を追加してください。
        header('Location: login.php'); // 例：ログイン画面にリダイレクト
        exit;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>企業登録画面</title>
    </head>
    <body>
        <h1>企業追加</h1>
        <form action="" method="POST">
            企業名<input type="text" name="company_name" value=""><br>
            <button type="submit" name="signin" value="">企業追加</button>
        </form>
        <a id="back" href="page_main.php" name="trans_back">戻る</a>
    </body>
</html>

<script>
    document.getElementById("back").onclick = function()
    {
        // フォームを生成し、hidden要素を設定
        var form = document.createElement('form');
        form.id = 'loginForm';
        form.action = 'page_main.php';
        form.method = 'POST';

        var hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'user_id';
        hiddenField.value = '<?php echo $user_id; ?>';

        form.appendChild(hiddenField);
        document.body.appendChild(form);

        // フォームを送信
        form.submit();
    }
</script>


