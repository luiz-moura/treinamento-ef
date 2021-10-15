<?php
require_once "helper.php";

Class Game {
  private string $chosenWord;
  private int $chosenGroup;
  private array $keywords = array();
  private int $life = 7;
  private int $totalPlayers;
  private int $currentPlayer;

  public function __construct(string $chosenWord, int $chosenGroup){
    $this->chosenWord = $chosenWord;
    $this->chosenGroup = $chosenGroup;
  }

  public function getChosenWord() {
    return $this->chosenWord;
  }

  public function getChosenGroup() {
    return $this->chosenGroup;
  }

  public function setKeyword(string $key) {
    $this->keywords[] = Helper::sanitizeString($key);
  }

  public function getKeywords() {
    return $this->keywords;
  }

  public function getLife() {
    return $this->life;
  }

  public function setTotalPlayers(int $total) {
    $this->totalPlayers = $total;
  }

  public function getTotalPlayers() {
    return $this->totalPlayers;
  }

  public function setCurrentPlayer(int $current) {
    $this->currentPlayer = $current;
  }

  public function getCurrentPlayer() {
    return $this->currentPlayer;
  }

  public function nextPlayer() {
    $next = ($this->currentPlayer == $this->totalPlayers) ? 1 : $this->currentPlayer + 1;

    return $next;
  }

  public function traits() {
    // $patternTraits = array('/[\p{Latin}]/');
    // $traits = preg_replace('/^[\p{Latin}\s]+$/u', "_", $this->chosenWord);

    $patternLetters = '/(?<!^)(?!$)/u';
    $letters = preg_split($patternLetters, $this->chosenWord);

    $traits = "";

    foreach ($letters as $letter) {
      $args = array(" ", "-");

      if (!in_array($letter, $args)) {
        $traits .= "_";
        continue;
      }

      $traits .= $letter;
    }

    $index = 0;
    foreach($letters as $letter) {
      $letterWithoutChars = Helper::sanitizeString($letter);
      $letterToUpper = mb_strtoupper($letterWithoutChars, "UTF-8");

      foreach($this->keywords as $keyword) {
        $keywordWithoutChars = Helper::sanitizeString($keyword);
        $keywordToUpper = mb_strtoupper($keywordWithoutChars, "UTF-8");
        
        if ($keywordToUpper == $letterToUpper) {
          // $traits[$index] = "$letter";
          $traits = Helper::mb_substr_replace($traits, $letter, $index, 1);
        }
      }
      $index++;
    }

    return $traits;
  }

  public function damage() {
    $this->life--;

    if ($this->life == 0) {
      return false;
    }

    return true;
  }

  public function numberOfLetters() {
    return mb_strlen($this->chosenWord, "UTF-8");
  }

  public function checkLetterExistsInWord(string $letter) {
    $chosenWordToUpper = mb_strtoupper($this->chosenWord, "UTF-8");
    $letterToUpper = mb_strtoupper($letter, "UTF-8");

    if (str_contains($chosenWordToUpper, $letterToUpper)) {
      return true;
    }
    
    return false;
  }

  public function checkLetterExistsInKeywords(string $letter) {
    $keywordsToString = implode($this->keywords);
    
    $letterWithoutChars = Helper::sanitizeString($letter);
    $keywordWithoutChars = Helper::sanitizeString($keywordsToString);

    $keywordsToUpper = mb_strtoupper($keywordWithoutChars, "UTF-8");
    $letterToUpper = mb_strtoupper($letterWithoutChars, "UTF-8");

    if (str_contains($keywordsToUpper, $letterToUpper)) {
      return true;
    }

    return false;
  }

  public function inputIsValid(string $input) {
    if (preg_match('/^[\p{Latin}]+$/u', $input)) {
      if (mb_strlen($input, "UTF-8") == 1) {
        return true;
      }
    }

    return false;
  }

  public function keywordsToString() {
    $keywordsToString = implode(', ', $this->keywords);
    $keywordsToUpper = mb_strtoupper($keywordsToString, "UTF-8");

    return $keywordsToUpper;
  }
}