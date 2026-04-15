<?php

declare(strict_types=1);

if ($argc < 3) {
    fwrite(STDERR, "Usage: php tools/check-coverage.php <cobertura-xml> <min-percent>\n");
    exit(1);
}

$coverageFile = $argv[1];
$minPercent = (float) $argv[2];

if (! is_file($coverageFile)) {
    fwrite(STDERR, "Coverage file not found: {$coverageFile}\n");
    exit(1);
}

$xml = simplexml_load_file($coverageFile);

if ($xml === false) {
    fwrite(STDERR, "Unable to parse coverage file: {$coverageFile}\n");
    exit(1);
}

$lineRate = (float) ($xml['line-rate'] ?? 0.0);
$coveragePercent = $lineRate * 100;

printf("Line coverage: %.2f%% (required: %.2f%%)\n", $coveragePercent, $minPercent);

if ($coveragePercent < $minPercent) {
    fwrite(STDERR, "Coverage gate failed.\n");
    exit(1);
}

exit(0);
