<?php
$base = __DIR__ . '/../app/';

$folders = [
    'lib',
    'model',
    'routes',
    'entity'
];

foreach($folders as $f)
{
    foreach (glob($base . "$f/*.php") as $filename)
    {
        require $filename;
    }
}

