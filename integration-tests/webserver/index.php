<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$results = [
    'uri'          => $request->getUri(),
    'body'         => $request->getContent(),
    'content-type' => $request->headers->get('Content-Type'),
];

echo json_encode($results);
