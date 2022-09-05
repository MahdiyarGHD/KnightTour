<?php

include_once "funcs.php";

$y_size = 12;
$x_size = 12;
$knightPos = [
    'y' => 1,
    'x' => 1
];
$knightNth = 0;
$slots_count = $y_size * $x_size ;

$MovedSlots = [
    [
        'y' => 1,
        'x' => 1
    ],

];
$slots = [];
$board = "";

$start_time = microtime(true);

$slots = fillSlots($y_size,$x_size, $slots, $MovedSlots);

$slots = moveKnight($slots, $knightNth, $knightPos, $y_size);

$slots = setSlotMoves($y_size,$x_size,$MovedSlots,$slots);

$board = refreshBoard($slots,$x_size,$board);
replaceable_echo($board);

for ($i = 0; $i < $y_size * $x_size ; $i++) { 
    if (calculateNextMove($x_size, $y_size, $knightPos, $MovedSlots, $slots) != "error") {
        $slots = setSlotMoves($y_size,$x_size,$MovedSlots,$slots);
        $knightPos = calculateNextMove($x_size, $y_size, $knightPos, $MovedSlots, $slots);
        $slots = replaceChessIcon($MovedSlots, $slots, $y_size);
        $slots = moveKnight($slots, $knightNth, $knightPos, $y_size);
        array_push($MovedSlots, ['x' => $knightPos['x'], 'y' => $knightPos['y']]);
        $board = refreshBoard($slots,$x_size,$board);
        replaceable_echo($board);
    } else {
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);
        
        echo "Execution time : ". round($execution_time * 10) / 10 ." sec" . PHP_EOL . PHP_EOL;
        die();
    }
}



// calculateNextMove($x_size, $y_size, $knightPos, $MovedSlots, $slots);

// echo json_encode($slots, JSON_PRETTY_PRINT);