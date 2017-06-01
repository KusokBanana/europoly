<?php

require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';

require_once 'core/roles.php';
require_once 'classes/Helper.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

Route::start();