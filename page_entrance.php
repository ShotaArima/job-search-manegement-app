<?php

global $tablename, $link;
connectSQL('job_search', 'user');

$passlist = array('hogehoge' => 'hogepass', 'hoge2' => 'hoge2pass');


if (!array_key_exists('user', $_POST)) {
    echoAuthPage("ログイン");
    exit;
}

$user = $_POST['user'];
$pass = $_POST['pass'];

if (!array_key_exists($user, $passlist) || $passlist[$user] != $pass) {
    echoAuthPage("パスワードが違います");
    exit;
}

echoHelloPage($user);

function echoAuthPage($msg)
{
    echo <<<EOT
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8" />
        <title>ページタイトル</title>
    </head>
    <body>
        $msg
        <form method="POST" action="page_entrance.php">
            username <input type="text" name="user" value=""><br>
            password <input type="password" name="pass" value=""><br>
            <button type="submit" name="login" value="login">Login</button>
        </form>
    </body>
    </html>
EOT;
}

function echoHelloPage($who)
{
    include 'head.php';

    connectSQL('task9', 'bbs');

    $selectQuery = "SELECT * FROM `$tablename`";
    $result = mysqli_query($link, $selectQuery);

    if ($result) {
        // Display results
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<hr>";
            echo "<div class='post'>";
            echo "<p>" . htmlspecialchars($row['id']) . "</p>";
            echo "<h5>投稿者: " . htmlspecialchars($row['name']) . "</h5>";
            echo "<p class='post-text'>" . htmlspecialchars($row['context']) . "</p>";
            echo '<div class="container">';
            echo "<p class='post-time'>投稿時間: " . htmlspecialchars($row['d_when']) . "</p>";
            echo "<p type='button' class='likes' data-post-id='{$row['id']}' data-likes='{$row['good']}'>いいね数: <span class='like-count'>" . htmlspecialchars($row['good']) . "</span></p>";
            echo "</div>";
            echo "</div>";
            echo "<hr>";
        }
    } else {
        echo "Error fetching records: " . mysqli_error($link);
    }

    mysqli_free_result($result);
    mysqli_close($link);

    include 'footer.php';
}

function connectSQL($database_name, $table_name)
{
    $hostname = '127.0.0.1';
    $username = 'root';
    $password = 'dbpass';
    $dbname = $database_name;
    global $tablename, $link;
    $tablename = $table_name;

    $link = mysqli_connect($hostname, $username, $password);

    if (!$link) {
        exit("Connect error!");
    }

    $result = mysqli_select_db($link, $dbname);

    if (!$result) {
        exit("Use error on table ($dbname)!");
    }
}
?>
