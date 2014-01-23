<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = (__DIR__ . '/src'));

$versions = GitVersionCollection::create($dir)->add('master', 'master branch');

return new Sami($iterator, array(
    'theme'                => 'enhanced',
    'versions'             => $versions,
    'title'                => 'JennyRaider\ObjectBuilder',
    'build_dir'            => __DIR__.'/docs/code/%version%',
    'cache_dir'            => __DIR__.'/docs-cache/code/%version%',
    'default_opened_level' => 2,
));
