<?php
    // セッションスタート
    session_start();

    // セッションによる情報の引継ぎ
    $user_id = $_SESSION['user_id'];
    $company_id = $_SESSION['company_id'];

    // データベース接続
    require_once 'dbconnect.php';

    if(isset($_SESSION['user_id']))
    {
        $id = $_SESSION['user_id'];

        try
        {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trans_confirm_company']))
            {
                $db = connect();
                $sql = 'INSERT INTO detail (user_id, company_id, detail_when, detail_subject, detail_content) VALUES (?, ?, ?, ?, ?)';

                if($db)
                {
                    $stmt = $db->prepare($sql);

                    // 変数の設定
                    $detail_when = $_POST['detail_when'];
                    $detail_subject = $_POST['detail_subject'];
                    $detail_content = $_POST['detail_content'];

                    $stmt->execute(array($user_id, $company_id, $detail_when, $detail_subject, $detail_content));
                    $stmt = null;
                    $db = null;

                    // // セッション破棄
                    // session_destroy();

                    // セッション変数の削除
                    unset($_SESSION['company_id']);

                    header('Location: page_company.php?company_id='.$company_id);
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
        <title>イベント追加画面</title>
    </head>
    <body>
        <h1>イベント追加</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            イベント名<input type="text" name="detail_subject" value=""><br>
            イベント内容<input type="text" name="detail_content" value=""><br>
            日時<input type="date" name="detail_when" value=""><br>
            <button type="submit" name="trans_confirm_company" value="">イベント追加</button>
        </form>
        <!-- フォームを使用してデータを送信 -->
        <form id="backForm" action="page_company.php" method="GET">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="company_id" value="<?php echo $company_id; ?>">
            <!-- 他の必要な隠し要素があればここに追加 -->
            <a id="back" href="javascript:void(0);" onclick="document.getElementById('backForm').submit();">戻る</a>
        </form>
    </body>
</html>