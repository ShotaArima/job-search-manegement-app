<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <style>
            .post {
                border: 1px solid #ccc;
                padding: 10px;
                margin: 10px;
                background-color: #f5f5f5;
            }

            .post-text {
                font-size: 16px;
            }

            .post-time {
                font-style: italic;
                color: #888;
                /* border: 1px solid #ccc; */
                padding: 10px;
                margin: 5px;
            }

            .likes {
                color: green;
                /* border: 1px solid #ccc; */
                padding: 10px;
                margin: 5px;
            }

            .container {
                display: flex;
                justify-content: space-between; /* 両端に合わせる */
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                background-color: #333;
                color: #fff;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 100;
            }

            .footer
            {
                background-color: #f5f5f5;
            }

            body {
                margin-top: 100px;
            }

        </style>
        <title>page_main</title>
    </head>
    <body>
        <div class="header">
            <div style="display: flex; align-items: center;">
                <h1 style="margin-right: 10px;" href="page_main.php">career secretary</h1>
                <a style="text-decoration: none; color: #bbbbbb; align-self: flex-end;">就活管理アプリ</a>
            </div>

            <form method="post" action="page_entrance.php">
                <button type="submit" class="btn btn-primarybtn btn-outline-light" name="btn-logout" value="btn-logout">ログアウト</button>
                <input type="hidden" name="transition" value="trans_main"></input>
            </form>
        </div>