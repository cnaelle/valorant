<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4d2809573b0d95c71d4097991e1ab39d
{
    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'enshrined\\svgSanitize\\' => 22,
        ),
        'B' => 
        array (
            'Benjaminzekavica\\EasySvg\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'enshrined\\svgSanitize\\' => 
        array (
            0 => __DIR__ . '/..' . '/enshrined/svg-sanitize/src',
        ),
        'Benjaminzekavica\\EasySvg\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit4d2809573b0d95c71d4097991e1ab39d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4d2809573b0d95c71d4097991e1ab39d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4d2809573b0d95c71d4097991e1ab39d::$classMap;

        }, null, ClassLoader::class);
    }
}
