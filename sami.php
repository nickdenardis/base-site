<?php
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;
use Sami\Parser\Filter\TrueFilter;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('node_modules')
    ->exclude('vendor')
    ->exclude('tests')
    ->exclude('sami-cache')
    ->in($dir = './')
;

/*
$versions = GitVersionCollection::create($dir)
    ->addFromTags('v2.*')
    ->add('2.5.0', '2.5.0 branch')
    ->add('master', 'master branch')
;
*/

$sami = new Sami($iterator, array(
    'title'                => 'Base API',
    // 'versions'             => $versions,
    'build_dir'            => __DIR__.'/api',
    'cache_dir'            => __DIR__.'/sami-cache/',
));

// document all methods and properties
$sami['filter'] = function () {
    return new TrueFilter();
};

return $sami;
