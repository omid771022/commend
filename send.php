#!/usr/bin/env php

<?php

$args = array_slice($argv, 1);

switch ($argv[0] ?? null) {

    case 'salam':
        echo "salam true";
        break;

    case 'bye':
        echo "bye true";
        break;


    case 'good':
        echo "good true";
        break;
    default:
        echo 'not commend';
}

