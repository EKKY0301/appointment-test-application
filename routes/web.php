<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\View\View;

Route::get(uri: '/', action: function () {
    return view(view: 'welcome');
});




