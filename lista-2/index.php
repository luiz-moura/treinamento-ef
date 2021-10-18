<?php
require_once "class/Game.php";
require_once "class/Dicionario.php";

require_once "helper.php";

$json = file_get_contents("data.json");
$data = json_decode($json, true);

$dicionario = new Dicionario($data);

unset($data);
unset($json);

options();

function options() {
  global $dicionario;

  Helper::clearCLI();

  print("+-----------------------------------------------------+\n");
  print("|                       OPCOES                        |\n");
  print("+---+-------------------------------------------------+\n");
  print(mb_str_pad("| 1 | Jogar", 54) . "|\n");
  print("+---+-------------------------------------------------+\n");
  print(mb_str_pad("| 2 | Cadastrar Palavra", 54) . "|\n");
  print(mb_str_pad("| 3 | Remover Palavra", 54) . "|\n");
  print(mb_str_pad("| 4 | Listar Palavras", 54) . "|\n");
  print("+---+-------------------------------------------------+\n");
  print(mb_str_pad("| 5 | Cadastrar Grupo", 54) . "|\n");
  print(mb_str_pad("| 6 | Remover Grupo", 54) . "|\n");
  print(mb_str_pad("| 7 | Listar Grupos", 54) . "|\n");
  print("+---+-------------------------------------------------+\n");
  print(mb_str_pad("| 8 | Sair do jogo", 54) . "|\n");
  print("+---+-------------------------------------------------+\n");
  $option = readline("Digite uma opção: ");

  Helper::clearCLI();

  switch ($option) {
    case PLAY:
      start();
      options();
      break;
    case ADD_WORD:
      addWord();
      options();
      break;
    case REMOVE_WORD:
      removeWord();
      options();
      break;
    case ADD_GROUP:
      addGroup();
      options();
      break;
    case REMOVE_GROUP:
      removeGroup();
      options();
      break;
    case SHOW_WORDS:
      $dicionario->printTableWords();
      passthru('pause');
      options($words);
      break;
    case SHOW_GROUPS:
      $dicionario->printTableGroups();
      passthru('pause');
      options();
      break;
    case EXIT_GAME:
      break;
    default:
      print("+-----------------------------------------------------+\n");
      print("|          OPCAO INVALIDA!! DIGITE NOVAMENTE          |\n");
      print("+-----------------------------------------------------+\n");
      passthru('pause');
      options();
      break;
  }
}

function start() {
  while (true) {
    game();

    $option = readline("Deseja jogar novamente (s/n): ");

    if ($option == 'n') {
      break;
    }

    Helper::clearCLI();
  }
}

function game() {
  global $dicionario;

  // PLAYERS
  $totalPlayers = readline("Quantidade de players (MAX 3): ");
  while ($totalPlayers < 1 || $totalPlayers > 3) {
    Helper::clearCLI();
    print("Quantidade não permitida!\n");
    $totalPlayers = (int) readline("Quantidade de players (MAX 3): ");
  }

  /**
   * @var string $palavra
   * @var string $grupo
   */
  [$chosenWord, $chosenGroup] = $dicionario->random();

  $game = new Game($chosenWord, $chosenGroup, $totalPlayers);
  
  while (true) {
    Helper::clearCLI();

    if ($totalPlayers > 1) {
      print(Helper::playerDraw($game->getCurrentPlayer()) . "\n");
    }
    print(Helper::lifeDraw($game->getLife()) . "\n");

    print("+-----------------------------------------------------+\n");
    print(mb_str_pad("| Numero de letras: " . $game->numberOfLetters(), 54) . "|\n");
    print(mb_str_pad("| Dica: " . $chosenGroup, 54) . "|\n");
    print(mb_str_pad("| Você tem " . $game->getLife() . " vidas restante", 54) . "|\n");
    print(mb_str_pad("| Utilizadas: " . $game->keywordsToString(), 54) . "|\n");
    print("+-----------------------------------------------------+\n");

    if (($alert = $game->popAlert()) != null) {
      print(mb_str_pad("| " . $alert, 54) . "|\n");
      print("+-----------------------------------------------------+\n");
    }

    print("\n");
    print $game->traits() . "\n";

    if ($game->isOver()) {
      print("Palavra: " . $chosenWord . "\n");
      print(Helper::gameOverDraw() . "\n");
      break;
    } elseif ($game->won()) {
      print(Helper::winDraw() . "\n");
      break;
    }

    $game->attempt(readline("Digite uma letra: "));
  }
}

function addWord() {
  global $dicionario;

  $dicionario->printTableGroups();

  print("+-----------------------------------------------------+\n");
  print("|                  CADASTRAR PALAVRA                  |\n");
  print("+-----------------------------------------------------+\n");

  $inputWord = readline("Digite uma palavra: ");
  $inputGroup = readline("Digite o id do grupo: ");

  if (!empty($inputWord) && $dicionario->isValidWord($inputWord)) {
    $dicionario->add($inputGroup, $inputWord);
    saveData($dicionario->toJson());
  }
}

function addGroup() {
  global $dicionario;

  $dicionario->printTableGroups();

  print("+-----------------------------------------------------+\n");
  print("|                   CADASTRAR GRUPO                   |\n");
  print("+-----------------------------------------------------+\n");

  $inputGroup = readline("Digite um grupo: ");
  
  if (!empty($inputGroup) && $dicionario->isValidWord($inputGroup)) {
    $dicionario->add($inputGroup);
    saveData($dicionario->toJson());
  }
}

function removeGroup() {
  global $dicionario;

  $dicionario->printTableGroups();

  print("+-----------------------------------------------------+\n");
  print("|                    REMOVER GRUPO                    |\n");
  print("+-----------------------------------------------------+\n");
  print("|  Todas as palavras do grupo também seram removidas  |\n");
  print("+-----------------------------------------------------+\n");

  $inputGroup = readline("Digite um grupo (ID ou NOME): ");
  
  if (!empty($inputGroup)) {
    $dicionario->remove($inputGroup);
    saveData($dicionario->toJson());
  }
}

function removeWord() {
  global $dicionario;

  $dicionario->printTableWords();

  print("+-----------------------------------------------------+\n");
  print("|                   REMOVER PALAVRA                   |\n");
  print("+-----------------------------------------------------+\n");

  $inputGroup = readline("Digite o grupo da palavra (ID): ");
  $inputWord = readline("Digite uma palavra (ID ou NOME): ");
  
  if (!empty($inputGroup) and !empty($inputWord)) {
    $dicionario->remove($inputGroup, $inputWord);
    saveData($dicionario->toJson());
  }
}

function saveData($data) {
  $fp = fopen('data.json', 'w');
  fwrite($fp, $data);
  fclose($fp);
}