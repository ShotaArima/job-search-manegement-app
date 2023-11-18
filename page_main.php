<?php
        $id = $_POST['user_id'];

    include 'header.php';
    require_once 'dbconnect.php';

    try
    {
        $db = connect();
        $sql = 'select * from company where user_id=?';
        if($db)
        {
            $stmt = $db->prepare($sql);
            $stmt->execute(array($id));
            $result = $stmt->fetchAll();
            $stmt = null;
            $db = null;

            if($result !== false && $result !== null)
            {
                // // ログイン成功時、user_idをhiddenフィールドに追加
                // $user_id = $result['user_id'];
                // echo '<form id="loginForm" action="page_main.php" method="POST">';
                // echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
                // echo '</form>';
                // echo '<script>document.getElementById("loginForm").submit();</script>';
                // exit;

                // ユーザidに紐づけられた会社情報を表示
                echo '<h1>会社情報</h1>';
                echo '<table border="1">';
                echo '<tr>';
                echo '<th>会社名</th>';
                echo '<th>状態</th>';
                echo '</tr>';
                foreach($result as $value)
                {
                    echo '<tr>';
                    echo '<td>' . $key . '</td>';
                    echo '<td>' . $result['company_name'] . '</td>';
                    switch($result['company_status'])
                    {
                        case 0:
                            echo '<td>落選</td>'
                            break;
                        case 1:
                            echo '<td>エントリー前</td>';
                            break;
                        case 2:
                            echo '<td>ES提出前</td>'
                            break;
                        case 3:
                            echo '<td>面談前<td>'
                            break;
                        case 4:
                            echo '<td>内定</td>'
                            break;
                    }

                    echo '<td>' . $result['company_status'] . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            else
            {
                $err_msg = 'データが入っていません。';
            }
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
        exit;
    }


    echo $id . 'さん、こんにちは！';
    include 'footer.php';
?>
