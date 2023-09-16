<?php


Route::prefix('v1')->group(function ($route) {
    require_once 'api/v1/auth.php';
});
