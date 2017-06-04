<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">

    <title>Программно-аппаратный генератор одноразовых паролей</title>
    <meta name="description"
          content='Данный электронный ресурс представляет собой дипломную работу студента Астраханского государственного техниеского университета факультета "Информационная безопасность компьютерных систем" группы ДИБББ-41. Демонстрирует аутентификацию пользователей с помощью одноразовых паролей, генерируемых программно-аппаратным генератором на базе микроконтроллера Arduino'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords"
          content="Алымов, Роман, диплом, информационная, безопасность, ДИБББ-41, АГТУ, ВУЗ, университет, проект, работа, программно-аппаратный, генератор">

    <meta name="author" content="Алымов Роман">
    <meta name="copyright" content="Дипломная работа Алымов Романа"/>

    <link rel="shortcut icon" href="/template/img/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/template/img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/template/img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/template/img/favicon/apple-touch-icon-114x114.png">

    <link rel="stylesheet" href="/template/css/fonts.min.css">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#000">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#000">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">

    <!-- непонятное говно<style>
        body {
            opacity: 0;
            overflow-x: hidden;
        }
        
        html {
            background-color: #fff;
        }

    </style> !-->
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <!-- header -->
            <header class="main-header">
                <div class="top-header">
                    <div class="col-md-1 col-md-offset-2">
                        <img class='logo' src="/template/img/logo2.png" alt="логотип">
                    </div>
                    <div class="col-md-7">
                        <nav class="main-nav">
                            <ul>
                                <li><a href="/">Главная</a></li>
                                <li><a href="#">Справка</a></li>
                                <li><a href="#">Контакты</a></li>
                                <?php if (User::isGuest()): ?>
                                    <li><a href="/user/login">Войти</a></li>
                                    <li><a href="/user/register">Регистрация</a></li>
                                    <?php else: ?>
                                    <li><a href="/cabinet">Welcome home</a></li>
                                    <li><a href="/user/logout">Выйти</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-7">
                        <a class='profile' href="/cabinet"></a>
                        <form action="" method="post" class="search">
                            <input type="search" name="" placeholder="поиск" class="input"/>
                            <input type="submit" name="" value="" class="submit"/>
                        </form>

                    </div>
                </div>

            </header>