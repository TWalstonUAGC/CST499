<?php
define('APP_ROOT', dirname(__FILE__));

function initSession() {
    if (!isset($_SESSION)) {
        ini_set('session.use_only_cookies', '1');
        session_start();
    }
}
initSession();
spl_autoload_register(function($className) {
    $directories = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
        __DIR__ . '/core/',
        __DIR__ . '/utils/'
    ];
    
    $simpleClassName = basename(str_replace('\\', '/', $className));
    
    foreach ($directories as $directory) {
        $file = $directory . $simpleClassName . '.php';
        
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    return false;
});

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database/database.php';