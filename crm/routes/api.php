<?php

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
JsonApiRoute::server('v1')
    ->prefix('v1')
    ->namespace('App\Http\Controllers\Api\V1')
    ->resources(static function ($server) {
        $server->resource('carriers')
            ->parameter('id')
            ->relationships(static function ($relationships) {
                $relationships->hasMany('deliveries');
            });

        $server->resource('deliveries')
            ->parameter('id')
            ->relationships(static function ($relationships) {
                $relationships->hasOne('carrier');
            });
    });
