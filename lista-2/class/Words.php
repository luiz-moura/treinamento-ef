<?php
require_once "helper.php";

Class Words {
  public object $groups;
  private array $words;

  public function __construct(array $words, object $groups) {
    $this->words = $words;
    $this->groups = $groups;
  }

  public function getWords() {
    return $this->words;
  }

  public function setWords(array $words) {
    $this->words = $words;
  }

  public function getRandomWord() {
    $limit = count($this->words);

    $random = random_int(0, $limit - 1);

    return $word = $this->words[$random];
  }

  public function isValidWord(string $input) {
    if (preg_match('/^[\p{Latin}\s\-\_]+$/u', $input)) {
      return true;
    }

    return false;
  }

  public function wordsToString() {
    print("+--------------------------------------------+\n");
    print("|             LISTA DE PALAVRAS              |\n");
    print("+------------------+-------------------------+\n");
    print("|       NOME       |           GRUPO         |\n");
    print("+------------------+-------------------------+\n");
    foreach($this->getWords() as $word) {
      print("| " . mb_str_pad($word["word"], 17) . "| ");
      print(mb_str_pad($this->groups->getGroupById($word["group"])["name"], 24) . "|\n");
    }
    print("+------------------+-------------------------+\n");
  }

  public function addWord(string $word, int $group) {
    $keys = array_column($this->groups->getGroups(), "id");
    if (in_array($group, $keys)) {
      $word = ["word" => $word, "group" => $group];
      $newWord = [...$this->getWords(), $word];
  
      $this->setWords($newWord);
    }
  }
}