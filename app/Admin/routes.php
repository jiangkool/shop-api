<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('article_category', 'ArticleCategoryController');
    $router->resource('articles', 'ArticleController');
    $router->resource('category', 'CategoryController');
    $router->resource('brands', 'BrandController');
    $router->resource('goods', 'GoodsController');

    $router->get('/api/brands','GoodsController@getBrands');

    $router->resource('order', 'OrderController');

    $router->post('order/{order}/refund','OrderController@refundConfirmation')->name('admin.orders.handle_refund');

    $router->resource('coupon', 'CouponController');
    $router->resource('comments', 'CommentController');
});
