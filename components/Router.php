<?php


class Router
{

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    // Метод получает URI. Несколько вариантов представлены для надёжности.
    function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        /*
        if (!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if (!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }*/
    }

    /**
     *
     */
    function run()
    {
        // Получаем URI.
        $uri = $this->getURI();

        $error404 = true;
        // Пытаемся применить к нему правила из конфигуации.
        foreach ($this->routes as $pattern => $route) {
            // Если правило совпало.
            if (preg_match("~^$pattern$~", $uri)) {
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$pattern~", $route, $uri);
                // Разбиваем внутренний путь на сегменты.
                $segments = explode('/', $internalRoute);
                // Первый сегмент — контроллер.
                $controller = ucfirst(array_shift($segments)) . 'Controller';
                // Второй — действие.
                $action = 'action' . ucfirst(array_shift($segments));
                // Остальные сегменты — параметры.
                $parameters = $segments;

                // Подключаем файл контроллера, если он имеется
                $controllerFile = ROOT . '/controllers/' . $controller . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                // Если не загружен нужный класс контроллера или в нём нет
                // нужного метода — 404
                if (!is_callable(array($controller, $action))) {
                    header("HTTP/1.0 404 Not Found");
                    return;
                }

                $controllerObject = new $controller;
                $result = call_user_func_array(array($controllerObject, $action), $parameters);
                $error404 = false;
                if ($result != null) {
                    break;
                }
            }
        }
        if ($error404) {
            header("HTTP/1.0 404 Not Found");
            return;
        }
    }
}