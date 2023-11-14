<?php
    require_once '../dbconnect.php';

    class UserLogic
    {
        /**
         * ユーザを登録する
         * @param array $userData
         * @return bool $result
         */
        public static function createUser($userData)
        {
            $result=false;
            $sql='INSERT INTO users (name, email, password) VALUES (?, ?, ?)';

            // ユーザデータを配列に入れる
            $arr=[];
            $arr[] = $userData['username']; //name
            $arr[] = $userData['email'];//email
            $arr[] = password_hash($userData['pasword'], PASSWORD_DEFAULT);//password、ハッシュ化する

            try
            {
                $stmt = connect()->prepare($sql);
                $result = $stmt->execute($arr);
                return $result;
            }
            catch(\Exception $e)
            {
                return $result;
            }
        }
        /**
         * ログイン処理
         * @param string $email
         * @param string $password
         * @return array|bool $user|false
         */
        public static function login($email, $password)
        {
            //結果
            $result = false;
            //ユーザをemailから検索して取得
            $user = self::getUserByEmail($email);

            // パスワードの照会
            if(password_verify($password, $user['password']))
            {
                //ログイン成功時
                $_SESSION['login_user'] = $user;
                $result = true;
                return result;
            };
        }
    }

    /**
         * emailからユーザを取得
         * @param string $email
         * @return bool $result
         */
        public static function login($email)
        {
            //SQLの準備
            //SQlの実行
            //SQLの結果を返す
            $sql='SELECT * FROM  users WHERE email==?';

            // emailを配列に入れる
            $arr=[];
            $arr[] = $email;
            try
            {
                $stmt = connect()->prepare($sql);
                $stmt->execute($arr);
                $user = $stmt->fetch();
                return $user;
            }
            catch(\Exception $e)
            {
                return false;
            }

        }
?>