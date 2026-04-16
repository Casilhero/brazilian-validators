<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Casilhero\BrazilianValidators\Validators\Suframa;

$values = [
    '550309012', '100698107', '111279100', '100955100', '101040105',
    '101362102', '100695108', '101160100', '600215105', '111266106',
    '100170102', '101416105', '101200102', '110344103', '111273102',
    '100480101', '100628109', '100394108', '101289103', '101139101',
    '100880100', '100826105', '110410106', '100764100', '110425103',
    '100965105',
];

$failed = [];

foreach ($values as $v) {
    if (! Suframa::isValid($v)) {
        $failed[] = $v;
    }
}

if (empty($failed)) {
    echo 'Todos os 26 valores são válidos.' . PHP_EOL;
} else {
    echo 'Falhou: ' . implode(', ', $failed) . PHP_EOL;
}
