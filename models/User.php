<?php

class User
{
    public static function register($name, $email, $password, $usertype = 'user')
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO users (nickname, email, password, usertype, token)' .
            'VALUES (:name, :email, :password, :usertype, :token)';
        $password = User::hashPassword($password); //хэшируем пароль
        $token = User::generateToken($name, $password); //генерируем токен

        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':usertype', $usertype, PDO::PARAM_STR);
        $result->bindParam(':token', $token, PDO::PARAM_STR);

        if ($result->execute())
            return User::sendMail($email, $token); //если запрос к БД выполнился-отправляем письмо подтверждения
    }

    /**
     * Пройден первый этап аутентификации
     * добавляет время жизни первой аутентификации в сессию
     */
    public static function authFirst()
    {
        $_SESSION['otpTime'] = time() + 120;
    }

    /**
     * Проверяет, не истекло ли время жизни первого этапа аутентификации
     * @return bool
     */
    public static function checkFirstStepTime()
    {
        if ($_SESSION['otpTime'] > time()) return true;
        header("Location: /user/login");
        exit;
    }

    public static function auth($userId)
    {
        $_SESSION['user'] = $userId;
    }

    public static function edit($userId, $name, $surname, $nickname, $birthdate)
    {
        $db = Db::getConnection();

        $sql = 'UPDATE users 
                SET name = :name, surname = :surname, nickname = :nickname, birthdate = :birthdate
                WHERE id = :id';

        //$birthdate = strval($birthdate);
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':surname', $surname, PDO::PARAM_STR);
        $result->bindParam(':nickname', $nickname, PDO::PARAM_STR);
        $result->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);

        return $result->execute();
    }

    public static function verify($token)
    {
        $db = Db::getConnection();

        $sql = "UPDATE users 
                SET token = 'NULL', verified = '1'
                WHERE token = :token";

        $result = $db->prepare($sql);
        $result->bindParam(':token', $token, PDO::PARAM_STR);

        return $result->execute();
    }

    public static function getUserById($id)
    {
        if ($id) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM users WHERE id= :id';

            $result = $db->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);

            //Указываем, что хотим получить данные в виде массива
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();

            return $result->fetch();
        }
    }

    public static function getIdByEmail($email)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetch();
        return $user['id'];
    }

    public static function addOtp($otpId, $userId)
    {
        $db = Db::getConnection();

        $sql = "UPDATE users 
                SET otp_id = :otp_id
                WHERE id = :userId";

        $result = $db->prepare($sql);
        $result->bindParam(':otp_id', $otpId, PDO::PARAM_STR);
        $result->bindParam(':userId', $userId, PDO::PARAM_STR);

        return $result->execute();
    }

    public static function sendMail($email, $token)
    {
        $headers = 'From: me@alymov-diplom.ru' . "\r\n" .
            'Reply-To: me@alymov-diplom.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        return mail($email, 'Подтверждение регистрации', 'alymov-diplom.ru/user/verify/' . $token,
            $headers);//отправляем сообщение
    }

    /**
     * Хэширование пароля
     * @param $password
     * @return bool
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Генерирование токена для подтверждения почты
     * @param $login
     * @param $password
     * @return bool
     */
    public static function generateToken($login, $password)
    {
        return md5(rand(0, PHP_INT_MAX));
    }

    /**
     * Проверяет, есть ли в сессии идентификатор пользователя
     * и возвращает его
     * @return mixed
     */
    public static function checkLogged()
    {
        //Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        header("Location: /user/login");
    }

    /**
     * Возвращает флаг наличия пользователя в сессии
     * @return bool
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет есть ли у пользователя генератор паролей
     * @param $email
     * @return bool
     */
    public static function isOtp($email)
    {
        $db = Db::getConnection();

        $sql = 'SELECT otp_id FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchColumn();
    }


    #region Функции проверки данных

    /**
     * Проверяет имя (не меньше 2 символов)
     * @param $name
     * @return bool
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет пароль (неменьше 6 символов)
     * @param $password
     * @return bool
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет дату
     * @param $birthdate
     * @return bool
     */
    public static function checkDate($birthdate)
    {
        if (strtotime($birthdate)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param $email
     * @return bool
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет есть ли уже такой email в базе данных
     * @param $email
     * @return bool
     */
    public static function checkEmailExists($email)
    {
        $db = Db::getConnection();

        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Проверяет подтверждение пароля
     * @param $password1
     * @param $password2
     * @return bool
     */
    public static function checkPassConf($password1, $password2)
    {
        if (strcmp($password1, $password2) == 0)
            return true;
        return false;
    }

    /**
     * Проверяет существует ли пользователь с заданными $email и $password
     * @param $email
     * @param $password
     * @return bool
     */
    public static function checkUserData($email, $password)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM users WHERE email = :email';

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetch();
        if (password_verify($password, $user['password'])) {
            return $user['id'];
        }
        return false;
    }

    #endregion
}