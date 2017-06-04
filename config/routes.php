<?php

return array(
    'product/([0-9]+)' => 'product/view/$1', //actionView в ProductController

    'catalog' => 'catalog/index', //actionIndex в CatalogController
    'category/([0-9]+)' => 'catalog/category/$1', //actionCategory в CatalogController

    'user/register'=>'user/register',
    'user/login' => 'user/login',
    'user/otp' => 'user/otp',
    'user/verify/([a-zA-z0-9]+)' => 'user/verify/$1',
    'user/logout' => 'user/logout',

    'cabinet' => 'cabinet/index',
    'cabinet/edit' => 'cabinet/edit',
    'cabinet/otpadd' => 'cabinet/otpadd',

    '' => 'site/index', //actionIndex в SiteController

    /*
    'news/([a-z]+)/([0-9]+)' => 'news/view/$1/$2',
    'product' => 'product/index', //actionList в ProductController
    'page/([-_a-z0-9]+)' => 'page/show/$1',
    'users/([-_a-z0-9]+)' => 'users/show/$1',
    */
);
