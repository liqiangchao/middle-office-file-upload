<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1212b3d6f0074545ed7acad1f5617252
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lqc\\MiddleOfficeFileUpload\\' => 27,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lqc\\MiddleOfficeFileUpload\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1212b3d6f0074545ed7acad1f5617252::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1212b3d6f0074545ed7acad1f5617252::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1212b3d6f0074545ed7acad1f5617252::$classMap;

        }, null, ClassLoader::class);
    }
}
