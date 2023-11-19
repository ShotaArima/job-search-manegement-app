<?php
    session_start();
    $user_id = $_SESSION['user_id'];

    if (isset($_GET['company_id'])) {
        $_SESSION['company_id'] = $_GET['company_id'];
    } else {
        // エラー処理またはデフォルトの値をセットする場合
        $_SESSION['company_id'] = null;
    }
    $company_id=$_SESSION['company_id'];

    include 'header.php';
    require_once 'dbconnect.php';

    echo 'No.' . htmlspecialchars($company_id, ENT_QUOTES, 'UTF-8') . 'さんのエントリー企業';

    try
    {
        $db = connect();
        $sql = 'SELECT * FROM detail WHERE user_id=? AND company_id=?';
        if($db)
        {
            $stmt = $db->prepare($sql);
            $stmt->execute(array($user_id, $company_id));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $db = null;

            if($result !== false && $result !== null)
            {
                // ユーザuser_idに紐づけられた会社情報を表示
                // (要修正)SQLでcompanyテーブルからidが一致するものを取り出し下の見出しに追加する
                echo '<h1>情報</h1>';
                // イベントの追加
                echo '<form action="page_update_company.php" method="POST">';
                echo '<button type="submit" class="" name="trans_update_company">イベント追加</button>';
                echo '<input type="hidden" name="user_user_id" value="'. htmlspecialchars($company_id, ENT_QUOTES, 'UTF-8') .'">';
                echo '</form>';

                foreach($result as $value)
                {
                    // 掲示板システムのような表示方法
                    echo '<hr>';
                    echo '<div class="post">';
                        echo ' "<p>"' . htmlspecialchars($value['id']) . '"</p>";';// 不必要な場合、削除
                        echo '<h5>: "' . htmlspecialchars($value['detail_subject']) . '"</h5>';// タイトル、見出し
                        echo '<p class="post-text">' . htmlspecialchars($value['detail_content']) . '</p>';// 中身の表示
                        echo '<div class="container">';
                            echo "<p class='post-time'>イベント日時: " . htmlspecialchars($value['detail_when']) . "</p>";
                            echo '<p class="importance">';
                            switch ($value['detail_importance'])
                            {
                                case 0:
                                    echo '';
                                    break;
                                case 1:
                                    echo '重要';
                                    break;
                                case 2:
                                    echo '締切日';
                                    break;
                                case 3:
                                    echo '面接日';
                                    break;
                            }
                            echo '</p>';
                        echo "</div>";
                        // 編集ボタン
                        echo '<a href="page_update_company.php" name="trans_company" value="">編集</a>';
                    echo "</div>";
                    echo "<hr>";
                }
            }
            else
            {
                $err_msg = 'データが入っていません。';
            }
        }
    }
    catch(PDOException $e)
    {
        echo 'エラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        exit;
    }

    include 'footer.php';
?>
