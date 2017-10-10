<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3dd043774e9e333b68ca3e1b0ee557be
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twig\\' => 5,
        ),
        'F' => 
        array (
            'Fxp\\Composer\\AssetPlugin\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twig\\' => 
        array (
            0 => __DIR__ . '/..' . '/twig/twig/src',
        ),
        'Fxp\\Composer\\AssetPlugin\\' => 
        array (
            0 => __DIR__ . '/..' . '/fxp/composer-asset-plugin',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3dd043774e9e333b68ca3e1b0ee557be::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3dd043774e9e333b68ca3e1b0ee557be::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit3dd043774e9e333b68ca3e1b0ee557be::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}