<?php
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Главная', route('home'));
});

// Home > About
Breadcrumbs::register('articles', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Полезные статьи');
});

Breadcrumbs::register('article', function($breadcrumbs, $article)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Полезные статьи', route('articles'));
    $breadcrumbs->push($article->title);
});

Breadcrumbs::register('news', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Новости');
});

Breadcrumbs::register('singlenews', function($breadcrumbs, $news)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Новости', route('news'));
    $breadcrumbs->push($news->title);
});

// Home > Cash
Breadcrumbs::register('cash', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Займы наличными');
});

// Home > Online
Breadcrumbs::register('online', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Займы онлайн');
});

// Home > Catalog
Breadcrumbs::register('catalog', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Каталог МФО');
});

// Home > Catalog
Breadcrumbs::register('company', function($breadcrumbs, $mfo)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Каталог МФО', route('catalog'));
    $breadcrumbs->push($mfo->title);
});

// Home > City
Breadcrumbs::register('city', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Займы по городам');
});

// Home > City > Single city
Breadcrumbs::register('singlecity', function($breadcrumbs, $city)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Займы по городам', route('city'));
    $breadcrumbs->push($city->title);
});

// Home > Reviews
Breadcrumbs::register('reviews', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Отзывы');
});

Breadcrumbs::register('sett', function($breadcrumbs, $sett)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Займы '.$sett->title);
});

Breadcrumbs::register('reestr', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Реестр МФО');
});

