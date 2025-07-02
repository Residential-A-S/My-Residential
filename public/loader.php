<?php

// autoload.php

/**
 * A very simple PSR-4 autoloader
 */

spl_autoload_register(function (string $class) {
    // 1) Namespace prefix → base directory map
    $prefixes = [
        "models\\" => "includes/models/",
        "traits\\" => "includes/traits/",
        "abstracts\\" => "includes/abstracts/",
        "core\\" => "includes/core/",
        "interfaces\\" => "includes/interfaces/",
        "templates\\pages\\" => "templates/pages/",
        "templates\\widgets\\" => "templates/widgets/"
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);

        $file = $baseDir
                . str_replace('\\', '/', $relativeClass)
                . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$files = [
    "config.php",
];
foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
    }
}