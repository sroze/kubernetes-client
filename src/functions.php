<?php

namespace Kubernetes\Client;

function file_path_from_contents(string $certificateContents)
{
    $file = tempnam(sys_get_temp_dir(), 'fileFromContents');
    file_put_contents($file, $certificateContents);

    register_shutdown_function(function() use($file) {
        unlink($file);
    });

    return $file;
}
