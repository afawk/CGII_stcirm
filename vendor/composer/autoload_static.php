<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit018bd675682ad8d01ff23820c7b73b7a
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'ImageSegmentation\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ImageSegmentation\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/ImageSegmentation',
        ),
    );

    public static $classMap = array (
        'ImageSegmentation\\Image\\Image' => __DIR__ . '/../..' . '/src/ImageSegmentation/Image.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit018bd675682ad8d01ff23820c7b73b7a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit018bd675682ad8d01ff23820c7b73b7a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit018bd675682ad8d01ff23820c7b73b7a::$classMap;

        }, null, ClassLoader::class);
    }
}
