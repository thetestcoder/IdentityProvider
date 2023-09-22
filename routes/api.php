<?php


Route::prefix('v1')->group(function ($route) {
    require 'api/v1/auth.php';
});
