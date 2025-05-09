<?php
/**
 * PSR-4 style autoloader for the application
 */
class Autoloader {
    /**
     * Register the autoloader
     */
    public static function register() {
        spl_autoload_register(array('Autoloader', 'loadClass'));
    }
    
    /**
     * Load a class file
     * 
     * @param string $className The name of the class to load
     * @return bool Whether the class was loaded
     */
    public static function loadClass($className) {
        error_log("Autoloader attempting to load: $className");
        
        $directories = array(
            dirname(__DIR__) . '/controllers/',
            dirname(__DIR__) . '/models/',
            dirname(__DIR__) . '/core/',
            dirname(__DIR__) . '/utils/'
        );
        
        $simpleClassName = basename(str_replace('\\', '/', $className));
        
        foreach ($directories as $directory) {
            $file = $directory . $simpleClassName . '.php';
            
            error_log("Checking for file: $file");
            
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }
        
        error_log("Autoloader could not find class: $className");
        
        return false;
    }
}

