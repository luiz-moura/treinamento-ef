<?php
require "helper.php";

function start() {
  $option = readline("Deseja jogar ou ver as pontuações? (play/scores): ");
  $optionLower = strtolower($option);

  if ($optionLower == "play" || $optionLower == "p") {
    game();
  } elseif ($optionLower == "scores" || $optionLower == "s") {
    score();
  }
}

function score() {
  $content = read("data.txt");
  $scores = json_decode($content, true);

  if (empty($scores)) {
    print("+-------------------------------------------+\n");
    print(str_pad("| Não foram registradas pontuações!", 47) . "|\n");
    print("+-------------------------------------------+\n");
    return false;
  }

  usort($scores, "desc");
  $position = 1;

  print("+-------------------------------------------+\n");
  print("|                  RANKING                  |\n");
  print("+----+-----------------+---------+----------+\n");
  print("|    | Nome            | Pontos  | Tempo    |\n");
  print("+----+-----------------+---------+----------+\n");
  foreach($scores as $score) {
    print("| " . str_pad($position, 3) . "| ");
    print(str_pad(substr($score["name"], 0, 15), 16) . "| ");
    print(str_pad($score["points"], 8) . "| ");
    print(str_pad(convertSecondsToHours($score["time"]), 9) . "|\n");
    $position++;
  }
  print("+----+-----------------+---------+----------+\n");

  start();
}

function game() {
  $play = true;

  $content = read("data.txt");
  $contentDecoded = json_decode($content, true);
  $scores = !empty($contentDecoded) ? $contentDecoded : [];

  $inputName = readline("Digite seu nome: \n");
  $name = strtoupper($inputName);

  do {
    $drawnNumber = random_int(0, 100);
    $attempts = 0;
    $points = 10;

    usort($scores, "asc");
  
    $timeStart = microtime(true);

    while (true) {
      $inputNumber = readline("Digite um número: \n");
      $attempts++;

      if ($inputNumber > $drawnNumber) {
        print("Muito alto\n");
        $points--;
      } elseif ($inputNumber < $drawnNumber) {
        print("Muito baixo\n");
        $points--;
      } else {
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        $seconds = ceil($time);

        if ($seconds >= 15) {
          --$points;
        } elseif ($seconds >= 30) {
          $points-=2;
        } elseif ($seconds >= 45) {
          $points-=3;
        } elseif ($seconds > 60) {
          $points-=4;
        }

        print("+-------------------------------------------+\n");
        print(str_pad("| ACERTOU!!", 44) . "|\n");
        print("+-------------------------------------------+\n");
        print(str_pad("| Você chutou {$attempts} vezes!", 45) . "|\n");
        print(str_pad("| Pontuação $points", 46) . "|\n");
        print(str_pad("| Duração da partida $seconds segundos", 46) . "|\n");
        
        if (!empty($scores)) {
          if ($points > end($scores)["points"]) {
            print("| " . str_pad("Maior pontuação da maquina!", 44) . "|\n");
          } else {
            $myScores = filterScoresByName($scores, $name);
            if (!empty($myScores)) {
              usort($myScores, "desc");
              if ($points > $myScores[0]["points"]) {
                print("| " . str_pad("você atingiu a sua maior pontuação", 45) . "|\n");
              }
            }
          }
        }

        print("+-------------------------------------------+\n");

        $score = ["name" => $name, "points" => $points, "time" => $seconds];
        $scores[] = $score;

        if (!empty($myScores)) {
          if (verifyToSave($myScores, $score)) {
            $index = 0; 
            foreach ($scores as $item) {
              if ($item["name"] == $score["name"] && 
                $item["points"] == $score["points"] && 
                $item["time"] != $score["time"]) {
                  unset($scores[$index]);
                }
              $index++;
            }
            
            write("data.txt", json_encode($scores));
          }
        } else {
          write("data.txt", json_encode($scores));
        }

        $playAgain = readline("Você deseja jogar novamente? (s ou n):\n");
  
        if (strtolower($playAgain) == "n") {
          $play = false;
          start();
        }
      
        break;
      }
    }
  } while ($play);  
}

function filterScoresByName(array $scores, string $name) {
  $myScores = array();
  foreach($scores as $score) {
    if ($score["name"] == $name) {
      $myScores[] = $score;
    }
  }
  return $myScores;
}

function verifyToSave($myScores, $scoreCurrent) {
  $points = array_column($myScores, "points");

  // !in_array($scoreCurrent["points"], $points)
  if($scoreCurrent["points"] > max($points)) {
    return true;
  }

  $keyEqualScore = array_search($scoreCurrent["points"], array_column($myScores, "points"));

  print($myScores[$keyEqualScore]["time"]);

  if (!empty($keyEqualScore)) {
    if ($scoreCurrent["time"] < $myScores[$keyEqualScore]["time"]) {
      return true;
    }
  }
  
  return false;
}

start();