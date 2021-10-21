<?php
const CADASTRAR_PRODUTO = 1;
const VISUALIZAR_PRODUTOS = 2;
const REGISTRAR_MOVIMENTACAO = 3;
const POSICAO_ESTOQUE = 4;
const MOVIMENTACOES = 5;
const SAIR = 6;

function clearCLI() {
  popen('cls || clear','w');
}

function stringToDate($dateStr) {
  $pattern = "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/";
  if (!preg_match($pattern, $dateStr)) {
    return false;
  }

  $date = str_replace('/', '-', $dateStr);
  return date('Y-m-d', strtotime($date));
}

function isInteger($input) {
  return(ctype_digit(strval($input)));
}

function implode_wrapped($before, $after, $array, $glue = '') {
  $str = $before . implode($after . $glue . $before, $array) . $after;
  return rtrim($str, ',');
}

function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = "UTF-8") {
  $encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
  $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
  $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
  $pad_len -= mb_strlen($str, $encoding);
  $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
  $strToRepeatLen = mb_strlen($pad_str, $encoding);
  $repeatTimes = ceil($targetLen / $strToRepeatLen);
  $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid utf-8 strings
  $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
  $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
  return $before . $str . $after;
}
