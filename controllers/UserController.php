<?php

class UserController
{
    public function actionRegister()
    {
        $name = '';
        $email = '';
        $password = '';
        $password_verify = '';
        $result = false;
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $name = $_POST['nickname'];
            $password = $_POST['password'];
            $password_verify = $_POST['password-conf'];

            $errors = false;

            if (!User::checkName($name)) {
                $errors[] = "Имя не должно быть короче 2-х символов";
            }

            if (!User::checkEmail($email)) {
                $errors[] = "Неправильный email";
            }

            if (!User::checkPassword($password)) {
                $errors[] = "Пароль не должен быть короче 6-ти символов";
            }

            if (!User::checkPassConf($password, $password_verify)) {
                $errors[] = "Пароли не совпадают";
            }

            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }

            if ($errors == false) {
                $result = User::register($name, $email, $password);
            }
        }

        require_once(ROOT . '/views/user/register.php');
        return true;
    }

    public function actionLogin()
    {
        $email = '';
        $password = '';

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            //Валидацияполей
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            //Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);
            $_SESSION['emailNow'] = $email;

            if ($userId == false) {
                //Если данные неправильные-показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт';
            } else {
                //проверяем добавлен ли OTP генератор
                if ($otpId = User::isOtp($email)) {
                    User::authFirst();
                    $otp = TOTP::generate(OtpGenerator::getUserKey($otpId),time());//генерация TOTP пароля
                    $_SESSION['otp'] = $otp;
                    Sockets::send(time());
                    //User::sendMail('romanalym@gmail.com', $otp);
                    header("Location: /user/otp/");
                    exit;
                } else { //если не добавлен
                    //Если данные правильные, запоминаем пользователя (сессия)
                    User::auth($userId);

                    //Перенаправляем пользователя в закрытую часть-кабинет
                    header("Location: /cabinet/");
                    exit;
                }
            }
        }

        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    public function actionOtp()
    {
        User::checkFirstStepTime();
        //echo $_SESSION['otpTime'];
        //echo '<br>';
        //echo time();
        //echo '<br>';
        //echo $_SESSION['otp'];
        //echo '<br>';
        //echo floor(time()/500);
        $otp = '';

        if (isset($_POST['submit'])) {
            $otp = $_POST['otp'];

            $errors = false;

            //Валидацияполей
            if (!User::checkPassword($otp)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            } else {
                //НАЧИНАЕТСЯ МАГИЯ ЧЕРТ ЕЁ ДЕРИ
                //TODO Отправка запроса, получение ответа, проверка пароля
                $otpId = User::isOtp($_SESSION['emailNow']); //получение ID генератора
                $test = TOTP::verify($otp, OtpGenerator::getUserKey($otpId));

                //$message=time()/60;
                //$test = TOTP::verifyTest($otp, OtpGenerator::getUserKey($otpId), $message);

                if ($test) {
                    unset($_SESSION['otpTime']);
                    User::auth(User::getIdByEmail($_SESSION['emailNow']));

                    //Перенаправляем пользователя в закрытую часть-кабинет
                    header("Location: /cabinet/");
                    exit;
                }
                else  $errors[] = 'Неправильный пароль';
            }
        }

        require_once(ROOT . '/views/user/otp.php');
        return true;
    }

    public function actionVerify($token)
    {
        if (User::verify($token)) {
            header('Refresh: 5; URL= /cabinet/');
            echo 'Вы подтвердили email. Через 5 секунд эт самое ага';
            exit;
        }
        require_once(ROOT . '/views/cabinet/index.php');
    }

    public function actionLogout()
    {
        unset($_SESSION['user']);
        header("Location: /");
        //return true;
    }
}