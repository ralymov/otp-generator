<?php

class CabinetController
{

    public function actionIndex()
    {
        $userId = User::checkLogged();

        $user = User::getUserById($userId);

        require_once(ROOT . '/views/cabinet/index.php');

        return true;
    }

    public function actionEdit()
    {
        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();

        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);

        // Заполняем переменные для полей формы
        $name = $user['name'];
        $surname = $user['surname'];
        $birthdate = $user['birthdate'];
        $nickname = $user['nickname'];

        // Флаг результата
        $result = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $birthdate = $_POST['birthdate'];
            $nickname = $_POST['nickname'];

            // Флаг ошибок
            $errors = false;

            // Валидируем значения
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkName($surname)) {
                $errors[] = 'Фамилия не должна быть короче 2-х символов';
            }
            if (!User::checkName($nickname)) {
                $errors[] = 'Ник не должен быть короче 2-х символов';
            }
            if (!User::checkDate($birthdate)) {
                $errors[] = 'Неверная дата';
            }
            if ($errors == false) {
                // Если ошибок нет, сохраняет изменения профиля
                $result = User::edit($userId, $name, $surname, $nickname, $birthdate);
            }
        }

        // Вызываем index
        header("Location: /cabinet");
    }

    public function actionOtpadd()
    {
        $userId = User::checkLogged();

        $user = User::getUserById($userId);

        // Флаг результата
        $result = false;

        // Обработка формы
        if (isset($_POST['addOtp'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования
            $serialNumber = $_POST['serial'];
            $manufactureDate = $_POST['manufactureDate'];

            // Флаг ошибок
            $errors = false;

            //Валидируем значения
            $otpId = OtpGenerator::findGenerator($serialNumber, $manufactureDate);
            if (!$otpId) {
                $errors[] = 'Такого генератора нет';
            }
            if ($errors == false) {
                // Если ошибок нет, сохраняет изменения профиля
                $result = User::addOtp($otpId, $userId);
            }
        }

        if ($result) {
            require_once(ROOT . '/views/cabinet/index.php');
            return true;
        } else {
            require_once(ROOT . '/views/cabinet/otp_add.php');
            return true;
        }

    }

}