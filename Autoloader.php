<?php
define('ROOT_DIR', __DIR__);

spl_autoload_extensions(".php");

/*
 * Replace first folder from namespace with the actual folder
 *
 * example:
 * \bobyblog\Some\Path => \src\Some\Path
 *
 */

spl_autoload_register(function ($fullQualifiedClassName){
    $parts = explode('\\', $fullQualifiedClassName);
    // remove root folder
    unset($parts[0]);
    $className = implode("\\", $parts);
    $filePath = ROOT_DIR . "\\src\\$className.php";

    if(file_exists($filePath))
        include_once $filePath;
});

// Check and load .env config
try{
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR);
    $dotenv->load();
    $dotenv->required([
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
        'DB_DSN',
        'BASE_URL',
        'SECRET',
        'UPLOAD_DIR',
        'ALBUM_DIR'
    ])->notEmpty();
} catch(Dotenv\Exception\ValidationException $ex){
    echo $ex->getMessage();
    exit();
}

if(is_dir(ROOT_DIR.$_ENV['UPLOAD_DIR']))
    define('UPLOAD_DIR', ROOT_DIR.$_ENV['UPLOAD_DIR']);
else
    throw new Exception('UPLOAD_DIR '.ROOT_DIR.$_ENV['UPLOAD_DIR'].' does not exist');

if(is_dir(ROOT_DIR.$_ENV['ALBUM_DIR']))
    define('ALBUM_DIR', ROOT_DIR.$_ENV['ALBUM_DIR']);
else
    throw new Exception('ALBUM_DIR '.ROOT_DIR.$_ENV['ALBUM_DIR'].' does not exist');

$_ENV['IS_DEBUG'] = true;