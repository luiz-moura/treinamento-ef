<?php
require_once "class/Game.php";
require_once "class/Words.php";
require_once "class/Groups.php";

require_once "helper.php";

$json = file_get_contents("data.json");
$data = json_decode($json, true);

$groups = new Groups($data["groups"]);
$words = new Words($data["words"], $groups);

options($words);

function options(object $words) {
  Helper::clearCLI();

  print("+--------------------------------------------+\n");
  print("|                   OPCOES                   |\n");
  print("+---+----------------------------------------+\n");
  print(str_pad("| 1 | Jogar", 45) . "|\n");
  print(str_pad("| 2 | Cadastrar Palavra", 45) . "|\n");
  print(str_pad("| 3 | Cadastrar Grupo", 45) . "|\n");
  print(str_pad("| 4 | Listar Palavras", 45) . "|\n");
  print(str_pad("| 5 | Listar Grupos", 45) . "|\n");
  print(str_pad("| 6 | Sair do jogo", 45) . "|\n");
  print("+---+----------------------------------------+\n");
  $option = readline("Digite uma opção: ");

  Helper::clearCLI();

  switch ($option) {
    case PLAY:
      start($words);
      options($words);
      break;
    case ADD_WORD:
      $words->groups->groupsToString($words);
      addWord($words);
      options($words);
      break;
    case ADD_GROUP:
      $words->groups->groupsToString($words);
      addGroup($words);
      options($words);
      break;
    case SHOW_WORDS:
      $words->wordsToString();
      passthru('pause');
      options($words);
      break;
    case SHOW_GROUPS:
      $words->groups->groupsToString($words);
      passthru('pause');
      options($words);
      break;
    case EXIT_GAME:
      break;
    default:
      print("+--------------------------------------------+\n");
      print(str_pad("| OPCAO INVALIDA!! DIGITE NOVAMENTE", 45) . "|\n");
      print("+--------------------------------------------+\n");
      passthru('pause');
      options($words);
      break;
  }
}

function start(object $words) {
  while (true) {
    game($words);

    $option = readline("Deseja jogar novamente (s/n): ");

    if ($option == 'n') {
      break;
    }
  }
}

function game(object $words) {
  // PLAYERS
  $players = readline("Quantidade de players (MAX 3): ");
  while ($players < 1 || $players > 3) {
    Helper::clearCLI();
    print("Quantidade não permitida!\n");
    $players = readline("Quantidade de players (MAX 3): ");
  }

  // GAME
  $randomWord = $words->getRandomWord();
  $game = new Game($randomWord["word"], $randomWord["group"]);
  $totalPlayers = $game->setTotalPlayers($players);
  $currentPlayer = $game->setCurrentPlayer(1);

  // STATUS DO GAME
  $win = false;
  $gameOver = false;

  // VALIDAÇÃO DO INPUT
  $invalidCharacter = false;
  $alreadyExistsInKeyword = false;
  $doesNotContain = false;
  $foundInTheWord = false;
  
  $i = 0;
  while (true) {
    Helper::clearCLI();

    if ($game->getTotalPlayers() > 1) {
      print(Helper::playerDraw($game->getCurrentPlayer()) . "\n");
    }
    print(Helper::lifeDraw($game->getLife()) . "\n");

    print("+--------------------------------------------+\n");
    print(" Numero de letras: " . $game->numberOfLetters() . "\n");
    $nameGroup = $words->groups->getGroupById($game->getChosenGroup())["name"];
    print(" Dica: " . $nameGroup . "\n");
    print(" Você tem " . $game->getLife() . " vidas restante\n");
    print(" Letras utilizadas: " . $game->keywordsToString() . "\n");
    print("+--------------------------------------------+\n");

    $invalidCharacter && print(" Digite uma letra!!\n");
    $alreadyExistsInKeyword && print(" Você já digitou essa letra!!\n");
    $doesNotContain && print(" A palavra não tem essa letra!!\n");
    $foundInTheWord && print("Acertou, a palavra tem essa letra!!\n");

    if ($invalidCharacter || $alreadyExistsInKeyword || $doesNotContain || $foundInTheWord) {
      print("+--------------------------------------------+\n");
    }
    print("\n");

    print $game->traits() . "\n";

    if ($gameOver) {
      print("Palavra: " . $game->getChosenWord() . "\n");
      print(Helper::gameOverDraw() . "\n");
      break;
    } elseif ($win) {
      print(Helper::winDraw() . "\n");
      break;
    }

    $letter = readline("Digite uma letra: ");

    // VALIDACAO INPUT [RESET]
    $invalidCharacter = false;
    $alreadyExistsInKeyword = false;
    $doesNotContain = false;
    $foundInTheWord = false;

    if (!$game->inputIsValid($letter)) {
      $invalidCharacter = true;
      continue;
    }
    
    if ($game->checkLetterExistsInKeywords($letter)) {
      $alreadyExistsInKeyword = true;
      continue;
    }

    $game->setKeyword($letter);

    if (!$game->checkLetterExistsInWord($letter)) {
      $doesNotContain = true;

      if (!$game->damage()) {
        $gameOver = true;
        continue;
      }

      $game->setCurrentPlayer($game->nextPlayer());
    } else {
      $foundInTheWord = true;
    }
    
    if ($game->getChosenWord() == $game->traits()) {
      $win = true;
      continue;
    }
  };
}

function addWord(object $words) {
  $inputWord = readline("Digite uma palavra: ");
  $inputGroup = readline("Digite o id do grupo: ");

  if (!empty($inputWord) && $words->isValidWord($inputWord)) {
    $words->addWord($inputWord, $inputGroup);
    saveData($words->getWords(), $words->groups->getGroups());
  }
}

function addGroup(object $words) {
  $inputGroup = readline("Digite um grupo: ");
  
  if (!empty($inputGroup) && $words->isValidWord($inputGroup)) {
    $words->groups->addGroup($inputGroup);
    saveData($words->getWords(), $words->groups->getGroups());
  }
}

function saveData(array $words, array $groups) {
  $data = ["words" => $words, "groups" => $groups];

  $fp = fopen('data.json', 'w');
  fwrite($fp, json_encode((object) $data));
  fclose($fp);
}