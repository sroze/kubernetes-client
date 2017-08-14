<?php

namespace Kubernetes\Client\Adapter\Http;

function certificate_file_path_from_contents(string $certificateContents)
{
    $file = tempnam(sys_get_temp_dir(), 'certificate');
    file_put_contents($file, $certificateContents);

    register_shutdown_function(function() use($file) {
        unlink($file);
    });

    return $file;
}
