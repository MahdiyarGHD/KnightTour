<?php

function fillSlots( $y_size , $x_size , $slots, $MovedSlots) {
    
    for($x = 1; $x <= $x_size; $x++) {
        for ($y = 1; $y <= $y_size; $y++) {
            array_push($slots, 
            [$y,$x,"o "]
            );
        }
    }
    
    return $slots;
    
}

function refreshBoard($slots, $x_size , $board) {
    $board = "";
    for ($i = 0; $i < count($slots); $i++) { 
        $board = $board . $slots[$i][2];
        if (!is_float(($i+1) / $x_size )) {
            $board = $board . PHP_EOL;
        }
    }
    
    return $board;
}

function calculateKnightNth($knightPos,$y_size) {
    $knightNth = ( ($knightPos['y'] - 1 ) * $y_size ) + $knightPos['x'];
    return $knightNth;
}

function getClosestIndex($search, $arr) {
    $closest = null;
    $index = null;
    foreach ($arr as $i => $item) {
       if ($closest === null || abs($search - $closest) > abs($item - $search)) {
          $closest = $item;
          $index = $i;
       }
    }
    return $index;
}


function replaceable_echo($message, $force_clear_lines = NULL) {
    static $last_lines = 0;

    if(!is_null($force_clear_lines)) {
        $last_lines = $force_clear_lines;
    }

    $term_width = exec('tput cols', $toss, $status);
    if($status) {
        $term_width = 64; // Arbitrary fall-back term width.
    }

    $line_count = 0;
    foreach(explode("\n", $message) as $line) {
        $line_count += count(str_split($line, $term_width));
    }

    // Erasure MAGIC: Clear as many lines as the last output had.
    for($i = 0; $i < $last_lines; $i++) {
        // Return to the beginning of the line
        echo "\r";
        // Erase to the end of the line
        echo "\033[K";
        // Move cursor Up a line
        echo "\033[1A";
        // Return to the beginning of the line
        echo "\r";
        // Erase to the end of the line
        echo "\033[K";
        // Return to the beginning of the line
        echo "\r";
        // Can be consolodated into
        // echo "\r\033[K\033[1A\r\033[K\r";
    }

    $last_lines = $line_count;

    echo $message."\n";
}

function moveKnight($slots,$knightNth,$knightPos,$y_size) {
    $knightNth = calculateKnightNth($knightPos,$y_size);
    $slots[$knightNth - 1][2] = "♞ ";
    return $slots;
}

function replaceChessIcon($MovedSlots,$slots,$y_size) {    
    for ($i = 0 ; $i < count($MovedSlots); $i++) {
        $KnightNth = calculateKnightNth(['y' => $MovedSlots[$i]['y'] , 'x' => $MovedSlots[$i]['x']],$y_size);
        $slots[$KnightNth - 1][2] = "♘ " ;
    }
    return $slots;
}

function getClosest($search, $arr) {
    $closest = null;
    foreach ($arr as $item) {
       if ($closest === null || abs($search - $closest) > abs($item - $search)) {
          $closest = $item;
       }
    }
    return $closest;
}

function getMoves($pos, $x_size, $y_size, $MovedSlots) {
    $moveMethodes = [
        [
            'y' => $pos['y'] - 1,
            'x' => $pos['x'] - 2 
        ],
        [
            'y' => $pos['y'] - 2,
            'x' => $pos['x'] - 1 
        ],
        [
            'y' => $pos['y'] - 2,
            'x' => $pos['x'] + 1 
        ],
        [
            'y' => $pos['y'] - 1,
            'x' => $pos['x'] + 2 
        ],
        [
            'y' => $pos['y'] + 1,
            'x' => $pos['x'] + 2 
        ],
        [
            'y' => $pos['y'] + 2,
            'x' => $pos['x'] + 1 
        ],
        [
            'y' => $pos['y'] + 2,
            'x' => $pos['x'] - 1 
        ],
        [
            'y' => $pos['y'] + 1,
            'x' => $pos['x'] - 2 
        ],
    ];
    
    $OkayArrays = [] ;
    
    foreach ($moveMethodes as $i => $method) {
        if ($method['x'] <= $x_size && $method['x'] > 0 && $method['y'] <= $y_size && $method['y'] > 0) {
            $isMethodOk = true;
            foreach ($MovedSlots as $i => $value) {
                if ($value['y'] == $method['y'] && $value['x'] == $method['x']) {
                    $isMethodOk = false;
                }
            }      
            
            if ($isMethodOk) {
                array_push($OkayArrays, $method);
            }
        }
    }
    
    return $OkayArrays;
}

function setSlotMoves($y_size,$x_size,$MovedSlots,$slots) {
    foreach($slots as $index => $value) {
        $moves = getMoves(['x' => $value[1], 'y' => $value[0]], $x_size, $y_size, $MovedSlots);
        $slots[$index][3] = count($moves);
    }
    
    return $slots;
}



function calculateNextMove($x_size, $y_size, $knightPos, $MovedSlots , $slots) {
    $moveMethodes = [
        [
            'y' => $knightPos['y'] - 1,
            'x' => $knightPos['x'] - 2 
        ],
        [
            'y' => $knightPos['y'] - 2,
            'x' => $knightPos['x'] - 1 
        ],
        [
            'y' => $knightPos['y'] - 2,
            'x' => $knightPos['x'] + 1 
        ],
        [
            'y' => $knightPos['y'] - 1,
            'x' => $knightPos['x'] + 2 
        ],
        [
            'y' => $knightPos['y'] + 1,
            'x' => $knightPos['x'] + 2 
        ],
        [
            'y' => $knightPos['y'] + 2,
            'x' => $knightPos['x'] + 1 
        ],
        [
            'y' => $knightPos['y'] + 2,
            'x' => $knightPos['x'] - 1 
        ],
        [
            'y' => $knightPos['y'] + 1,
            'x' => $knightPos['x'] - 2 
        ],
    ];
    
    $OkayArrays = [] ;
    
    foreach ($moveMethodes as $i => $method) {
        if ($method['x'] <= $x_size && $method['x'] > 0 && $method['y'] <= $y_size && $method['y'] > 0) {
            $isMethodOk = true;
            foreach ($MovedSlots as $i => $value) {
                if ($value['y'] == $method['y'] && $value['x'] == $method['x']) {
                    $isMethodOk = false;
                }
            }      
            
            if ($isMethodOk) {
                array_push($OkayArrays, $method);
            }
        }
    }

    $ways = [];
    foreach ($OkayArrays as $index => $value) {
        $knightNth = calculateKnightNth(['x' => $value['x'], 'y' => $value['y']],$y_size);
        array_push($ways, ($slots[$knightNth - 1][3] == 0) ? 0 : $slots[$knightNth - 1][3]);
    }

    return $OkayArrays[getClosestIndex(0,$ways)] ?? "error";
}
