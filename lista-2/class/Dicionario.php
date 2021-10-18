<?php
require_once "helper.php";

class Dicionario
{
  public function __construct(
    private array $groups,
  ) {}

  /**
   * @return array{string, string}
   */
  public function random() : array
  {
    $key = array_rand($this->groups);
    $word = array_rand($this->groups[$key]);
    $word = $this->groups[$key][$word];

    return [$word, $key];
  }

  public function add(int|string $group, $word=null) : void
  {
    if (is_null($word)) {
      $this->groups[$group] = [];
    } else {
      if (isInteger($group)) {
        $keys = array_keys($this->groups);
        $key = $keys[$group - 1];

        $this->groups[$key][] = $word;
      } else {
        $this->groups[$group][] = $word;
      }
    }
  }

  public function remove(int|string $group, $word=null) : void 
  {
    if (is_null($word)) {
      if (isInteger($group)) {
        $index = $group - 1;
        array_splice($this->groups, $index, 1);
      } else {
        unset($this->groups[$group]);
      }
    } else {
      $keys = array_keys($this->groups);
      $key = $group - 1;

      if (isset($keys[$key])) {
        $keyGroup = $keys[$key];
        $keyWord = array_search($word, $this->groups[$keyGroup]);

        if (isInteger($word)) {
          array_splice($this->groups[$keyGroup], $keyWord, 1);
        } else {
          unset($this->groups[$keyGroup][$keyWord]);
        }
      }
    }
  }

  public function isValidWord(string $input) : bool
  {
    if (preg_match('/^[\p{Latin}\s\-\_]+$/u', $input)) {
      return true;
    }
    return false;
  }

  public function toJson() : string 
  {
    return json_encode($this->groups);
  }

  public function printTableGroups() : void
  {
    print("+-----------------------------------------------------+\n");
    print("|                   LISTA DE GRUPOS                   |\n");
    print("+----+------------------------------------------------+\n");
    print("| ID |                      NOME                      |\n");
    print("+----+------------------------------------------------+\n");
    $i = 1;
    foreach($this->groups as $group => $wordsInGroup) {
      print(mb_str_pad("| " . $i, 5) . "| ");
      print(mb_str_pad($group, 47) . "|\n");
      $i++;
    }
    print("+----+------------------------------------------------+\n");
  }

  public function printTableWords() : void
  {
    print("+-----------------------------------------------------+\n");
    print("|                  LISTA DE PALAVRAS                  |\n");
    print("+---------------------------+-------------------------+\n");
    print("|           NOME            |          GRUPO          |\n");
    print("+---------------------------+-------------------------+\n");
    $i = 1;
    $j = 1;
    foreach($this->groups as $group => $wordsInGroup) {
      foreach($wordsInGroup as $word) {
        print(mb_str_pad("| $j - " . $word, 28) . "| ");
        print(mb_str_pad("$i - " . $group, 24) . "|\n");
        $j++;
      }
      $i++;
    }
    print("+---------------------------+-------------------------+\n");
  }
}