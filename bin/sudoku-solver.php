#!/usr/bin/env php
<?php
set_include_path(__DIR__ . '/../src/');
require_once 'Sudoku.php';
require_once 'Solver.php';
require_once 'Verifier.php';

if ($argc >= 2) {
    $file = $argv[1];
} else {
    $file = __DIR__ . '/../examples/simple-2.txt';
}
$input = file_get_contents($file);

$data = array_fill(0, 9, array_fill(0, 9, ''));
foreach (explode("\n", $input) as $lnum => $line) {
    $lparts = str_split(rtrim($line), 1);
    foreach ($lparts as $cnum => $c) {
        if (is_numeric($c)) {
            $data[$lnum][$cnum] = (int) $c;
        }
    }
}

$sd = new Sudoku();
$sd->data = $data;

$solver = new Solver();
$solver->solve($sd);

echo $sd;

$verifier = new Verifier();
try {
    $verifier->verify($sd);
    echo "✔ solved\n";
    exit(0);
} catch (Exception $e) {
    echo "✘ not solved: " . $e->getMessage() . "\n";
    exit(1);
}
?>
