<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//front
Route::get('/', ['as' => 'home', 'uses' => 'IndexController@index']);
Route::post('/filter', 'IndexController@filter');
Route::get('/articles', ['as' => 'articles', 'uses'=> 'CategoryController@articles']);
Route::get('/news', ['as' => 'news', 'uses' => 'CategoryController@news']);
Route::get('/articles/{slug}', 'CategoryController@article');
Route::get('/news/{slug}', 'CategoryController@singlenews');
Route::post('/subscribe', 'IndexController@subscribe');
Route::get('/company/{slug}', 'CompanyController@index');
Route::get('/company/{slug}/comments', 'CompanyController@comments');
Route::post('/company/comments/add', 'CompanyController@addcomment');
Route::post('/company/comments/voice', 'CompanyController@voice');
Route::get('/company/{slug}/news', 'CompanyController@news');
Route::get('/catalog', ['as' => 'catalog', 'uses' => 'CatalogController@index']);
Route::get('/catalog/cash', 'CatalogController@cash');
Route::get('/catalog/online', 'CatalogController@online');
Route::post('/initcity', 'IndexController@initcity');
Route::post('/takecity', 'IndexController@takecity');
Route::post('/cashsorting', 'CatalogController@cashsorting');
Route::post('/catalogsorting', 'CatalogController@catalogsorting');
Route::post('/onlinesorting', 'CatalogController@onlinesorting');
Route::get('/catalog/city', ['as' => 'city', 'uses' =>'CatalogController@city']);
Route::get('/company/city/{slug}', 'CatalogController@currentcity');
Route::post('/citysorting', 'CatalogController@citysorting');
Route::get('/catalog/letters/{letter?}', 'CatalogController@letters');
Route::post('/sendrequest', 'IndexController@sendrequest');
Route::post('/sendmessage', 'IndexController@sendmessage');
Route::get('/reviews', 'CompanyController@reviews');
Route::get('catalog/filter', 'CatalogController@filter');
Route::get('/reestr', 'PageController@index');
Route::get('/history', 'PageController@history');
Route::get('/create', 'IndexController@users');



// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
//manager
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
	Route::get('/', function(){
		return redirect('/admin/articles');
	});

	Route::get('/articles', 'ArticleController@index');
	Route::get('/articles/create', 'ArticleController@create');
	Route::post('/articles/create', 'ArticleController@store');
	Route::get('/article/{id}', 'ArticleController@show');
	Route::get('/article/edit/{id}', 'ArticleController@edit');
	Route::put('/articles/update', 'ArticleController@update');
	Route::delete('/articles/destroy', 'ArticleController@destroy');

	Route::get('/news', 'NewsController@index');
	Route::get('/news/create', 'NewsController@create');
	Route::post('/news/create', 'NewsController@store');
	Route::get('/news/{id}', 'NewsController@show');
	Route::get('/news/edit/{id}', 'NewsController@edit');
	Route::put('/news/update', 'NewsController@update');
	Route::delete('/news/destroy', 'NewsController@destroy');

	Route::get('/mfo', 'MfoController@index');
	Route::get('/mfo/create', 'MfoController@create');
	Route::post('/mfo/create', 'MfoController@store');
	Route::get('/mfo/{id}', 'MfoController@show');
	Route::get('/mfo/edit/{id}', 'MfoController@edit');
	Route::put('/mfo/update', 'MfoController@update');
	Route::delete('/mfo/destroy', 'MfoController@destroy');

	Route::get('/comments', 'CommentController@index');
	Route::get('/comments/create', 'CommentController@create');
	Route::post('/comments/create', 'CommentController@store');
	Route::get('/comments/{id}', 'CommentController@show');
	Route::get('/comments/edit/{id}', 'CommentController@edit');
	Route::put('/comments/update', 'CommentController@update');
	Route::delete('/comments/destroy', 'CommentController@destroy');
	Route::post('/comments/filter', 'CommentController@filter');

	Route::get('/answers', 'AnswerController@index');
	Route::get('/answers/create', 'AnswerController@create');
	Route::post('/answers/create', 'AnswerController@store');
	Route::get('/answers/{id}', 'AnswerController@show');
	Route::get('/answers/edit/{id}', 'AnswerController@edit');
	Route::put('/answers/update', 'AnswerController@update');
	Route::delete('/answers/destroy', 'AnswerController@destroy');

	Route::get('/cities', 'CityController@index');
	Route::get('/cities/create', 'CityController@create');
	Route::post('/cities/create', 'CityController@store');
	Route::get('/cities/{id}', 'CityController@show');
	Route::get('/cities/edit/{id}', 'CityController@edit');
	Route::put('/cities/update', 'CityController@update');
	Route::delete('/cities/destroy', 'CityController@destroy');
	Route::post('/cities/import', 'CityController@import');

	Route::get('/methods', 'MethodController@index');
	Route::get('/methods/create', 'MethodController@create');
	Route::post('/methods/create', 'MethodController@store');
	Route::get('/methods/{id}', 'MethodController@show');
	Route::get('/methods/edit/{id}', 'MethodController@edit');
	Route::put('/methods/update', 'MethodController@update');
	Route::delete('/methods/destroy', 'MethodController@destroy');
	Route::post('/methods/import', 'MethodController@import');

	Route::get('/borrows', 'BorrowController@index');
	Route::get('/borrows/create', 'BorrowController@create');
	Route::post('/borrows/create', 'BorrowController@store');
	Route::get('/borrows/{id}', 'BorrowController@show');
	Route::get('/borrows/edit/{id}', 'BorrowController@edit');
	Route::put('/borrows/update', 'BorrowController@update');
	Route::delete('/borrows/destroy', 'BorrowController@destroy');
	Route::post('/borrows/import', 'BorrowController@import');

	Route::get('/setts', 'SettController@index');
	Route::get('/setts/create', 'SettController@create');
	Route::post('/setts/create', 'SettController@store');
	Route::get('/setts/{id}', 'SettController@show');
	Route::get('/setts/edit/{id}', 'SettController@edit');
	Route::put('/setts/update', 'SettController@update');
	Route::delete('/setts/destroy', 'SettController@destroy');
	Route::post('/setts/import', 'SettController@import');

	Route::get('/orders', 'OrderController@index');
	Route::get('/orders/create', 'OrderController@create');
	Route::post('/orders/create', 'OrderController@store');
	Route::get('/orders/{id}', 'OrderController@show');
	Route::get('/orders/edit/{id}', 'OrderController@edit');
	Route::put('/orders/update', 'OrderController@update');
	Route::delete('/orders/destroy', 'OrderController@destroy');

	Route::get('/histories', 'HistoryController@index');
	Route::get('/histories/create', 'HistoryController@create');
	Route::post('/histories/create', 'HistoryController@store');
	Route::get('/histories/edit/{id}', 'HistoryController@edit');
	Route::put('/histories/update', 'HistoryController@update');
	Route::delete('/histories/destroy', 'HistoryController@destroy');

	Route::get('/users', 'UserController@index');
	Route::get('/users/create', 'UserController@create');
	Route::post('/users/create', 'UserController@store');
	Route::get('/users/edit/{id}', 'UserController@edit');
	Route::put('/users/update', 'UserController@update');
	Route::delete('/users/destroy', 'UserController@destroy');
});
