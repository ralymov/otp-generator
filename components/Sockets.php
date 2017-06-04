<?php

class Sockets
{
    public static function send($message)
    {
        /* Получаем порт сервиса WWW. */
        $service_port = getservbyname('www', 'tcp');

        /* Получаем  IP адрес целевого хоста. */
        $hostname = $_SERVER['REMOTE_ADDR'];
        $hostname = 'localhost';
        $address = gethostbyname($hostname);
        /* Создаём  TCP/IP сокет. */
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $errors = false;
        if ($socket === false) {
            $errors[] = 'Ошибка создания сокета';
        }
        $result = socket_connect($socket, $address, 11000);
        if ($result === false) {
            $errors[] = 'Ошибка соединения с сокетом';
        }

        $in = 'TEST';
        $out = '';

        socket_write($socket, $message, strlen($message));//отправка сообщения на сокет

        /*
        //читаем ответы
        while ($out = socket_read($socket, 2048)) {
            //echo $out;
        }
        */

        //закрываем сокет
        socket_close($socket);
    }
}