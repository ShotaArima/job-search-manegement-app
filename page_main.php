<?php
    session_start();
    $id = $_SESSION['user_id'];

    include 'header.php';
    require_once 'dbconnect.php';

    echo 'No.' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . 'さん、こんにちは！';

    try
    {
        $db = connect();
        $sql = 'select * from company where user_id=?';
        if($db)
        {
            $stmt = $db->prepare($sql);
            $stmt->execute(array($id));
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = null;
            $db = null;

            if($result !== false && $result !== null)
            {
                // ユーザidに紐づけられた会社情報を表示
                echo '<h1>会社情報</h1>';
                // 企業の追加
                echo '<form action="page_company_add.php" method="POST">';
                echo '<button type="submit" class="" name="trans_company_add">企業追加</button>';
                echo '<input type="hidden" name="user_id" value="'. htmlspecialchars($id, ENT_QUOTES, 'UTF-8') .'">';
                echo '</form>';

                // テーブル表示
                echo '<table border="1">';
                    echo '<tr>';
                        echo '<th>No.</th>';
                        echo '<th>会社名</th>';
                        echo '<th>状態</th>';
                        echo '<th>リンク</th>';
                    echo '</tr>';
                    foreach($result as $value)
                    {
                        echo '<tr>';
                            echo '<td>' . htmlspecialchars($value['company_id'], ENT_QUOTES, 'UTF-8') . '</td>';
                            echo '<td>' . htmlspecialchars($value['company_name'], ENT_QUOTES, 'UTF-8') . '</td>';

                            // 就活の状態を表示
                            switch($value['company_status'])
                            {
                                case 0:
                                    echo '<td>落選</td>';
                                    break;
                                case 1:
                                    echo '<td>エントリー前</td>';
                                    break;
                                case 2:
                                    echo '<td>ES提出前</td>';
                                    break;
                                case 3:
                                    echo '<td>面談前<td>';
                                    break;
                                case 4:
                                    echo '<td>内定</td>';
                                    break;
                            }
                        // 各企業のぺージリンク
                        $company_id = htmlspecialchars($value['company_id'], ENT_QUOTES, 'UTF-8');
                        echo '<td>';
                        echo '<a href="page_company.php?company_id=' . $value['company_id'] . '" class="edit-link" name="trans_company" value="">編集</a>';
                        echo "</td>";
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
        echo 'エラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        exit;
    }

    include 'footer.php';
?>
<script>
    // 編集リンクがクリックされたときの処理
    document.querySelectorAll('.edit-link').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // リンクのデフォルトの動作をキャンセル

            // リンクのdata-company-id属性から会社IDを取得
            var companyId = link.getAttribute('data-company-id');

            // 会社IDをサーバーサイドに送信する（Ajaxを使用）
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'store_company_id.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                // サーバーサイドでの処理が完了した後に実行されるコード
                if (xhr.status === 200) {
                    // 成功した場合の処理（任意の処理を追加）
                    console.log('Company ID stored in session successfully.');
                } else {
                    // エラーが発生した場合の処理
                    console.error('Error storing Company ID in session.');
                }
            };
            xhr.send('company_id=' + companyId);
        });
    });
</script>