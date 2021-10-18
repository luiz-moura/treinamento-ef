<?php
require_once "helper.php";

Class Game 
{
  public function __construct(
    private string      $chosenWord, 
    private string      $chosenGroup, 
    private int         $totalPlayers,
    private array       $keywords = array(),
    private int         $life = 7,
    private int         $currentPlayer = 1,
    private string|null $alert = null,
  ) {}

  public function getChosenWord()  : string
  {
    return $this->chosenWord;
  }

  public function getChosenGroup() : string
  {
    return $this->chosenGroup;
  }

  public function getLife() : int
  {
    return $this->life;
  }

  public function getCurrentPlayer(): int 
  {
    return $this->currentPlayer;
  }

  public function isOver() : bool
  {
    return $this->life == 0;
  }

  public function won() : bool
  {
    return $this->getChosenWord() == $this->traits();
  }

  public function popAlert() : null|string
  {
    $alert = $this->alert;
    $this->alert = null;
    return $alert;
  }

  private function setKeyword(string $letter) : void
  {
    $this->keywords[] = Helper::sanitizeString($letter);

    if (!$this->checkLetterExistsInWord($letter)) {
      $this->alert = "A palavra não tem essa letra!!";
      $this->damage();
      $this->nextPlayer();
    } else {
      $this->alert = "Acertou, a palavra tem essa letra!!";
    }
  }

  public function attempt(string $key) : void
  {
    if (!$this->inputIsValid($key)) {
      $this->alert = "Digite uma letra!!";
    } else if ($this->checkLetterExistsInKeywords($key)) {
      $this->alert = "Você já digitou essa letra!!";
    } else {
      $this->setKeyword($key);
    }
  }

  public function nextPlayer() : void
  {
    $this->currentPlayer = ($this->currentPlayer == $this->totalPlayers) ? 1 : $this->currentPlayer + 1;
  }

  public function damage() : int
  {
    return $this->life--;
  }

  public function numberOfLetters() : int
  {
    $patternLetters = '/(?<!^)(?!$)/u';
    $letters = preg_split($patternLetters, $this->chosenWord);

    $patternCountLetters = '/(?<!^)(?!$)/u';
    $total = preg_match_all($patternCountLetters, $this->chosenWord, $letters);

    return $total;
  }

  public function traits() : string
  {
    $patternLetters = '/(?<!^)(?!$)/u';
    $letters = preg_split($patternLetters, $this->chosenWord);
    
    $traits = "";

    foreach ($letters as $letter) {
      $args = array(" ", "-", "_");

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
          $traits = mb_substr_replace($traits, $letter, $index, 1);
        }
      }
      $index++;
    }

    return $traits;
  }

  public function checkLetterExistsInWord(string $letter) : bool
  {
    $chosenWordToUpper = mb_strtoupper($this->chosenWord, "UTF-8");
    $letterToUpper = mb_strtoupper($letter, "UTF-8");

    if (str_contains($chosenWordToUpper, $letterToUpper)) {
      return true;
    }
    
    return false;
  }

  public function checkLetterExistsInKeywords(string $letter) : bool
  {
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

  public function inputIsValid(string $input) : bool
  {
    if (preg_match('/^[\p{Latin}]+$/u', $input)) {
      if (mb_strlen($input, "UTF-8") == 1) {
        return true;
      }
    }

    return false;
  }

  public function keywordsToString() : null|string
  {
    $keywordsToString = implode(", ", $this->keywords);
    $keywordsToUpper = mb_strtoupper($keywordsToString, "UTF-8");

    return $keywordsToUpper;
  }
}