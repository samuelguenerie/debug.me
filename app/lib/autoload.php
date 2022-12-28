<?php

const ALIASES = [
    'Plugo' => 'lib',
    'App' => 'src'
];

spl_autoload_register(/** * @throws Exception */ function (string $class): void
{
    $namespaceParts = explode('\\', $class);

    if (in_array($namespaceParts[0], array_keys(ALIASES))) {
        $namespaceParts[0] = ALIASES[$namespaceParts[0]];
    } else {
        throw new Exception("Error in the namespace name: $namespaceParts[0]");
    }

    $filepath = dirname(__DIR__) . '/' . implode('/', $namespaceParts) . '.php';

    if (!file_exists($filepath)) {
        throw new Exception("File $filepath not found in $class class.");
    }

    require $filepath;
});
