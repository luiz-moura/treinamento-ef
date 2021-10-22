<?php
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

function dataHoraAtual() {
  $timezone = new DateTimeZone('America/Porto_velho');
  return (new DateTime('now', $timezone))->format('d/m/Y H:i');
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

$papelaria = '
`7MMF    `7MF `7MN.   `7MF `7MMF `7MMF    `7MF `7MM"""YMM  `7MM"""Mq.   .M"""bgd   .g8""8q.
  MM       M    MMN.    M    MM    `MA     ,V    MM    `7    MM   `MM. ,MI    "Y .dP     `YM.
  MM       M    M YMb   M    MM     VM:   ,V     MM   d      MM   ,M9  `MMb.     dM       `MM
  MM       M    M  `MN. M    MM      MM.  M      MMmmMM      MMmmdM9     `YMMNq. MM        MM
  MM       M    M   `MM.M    MM      `MM A       MM   Y  ,   MM  YM.   .     `MM MM.      ,MP
  YM.     ,M    M     YMM    MM       :MM;       MM     ,M   MM   `Mb. Mb     dM `Mb.    ,dP
   `bmmmmd"´  .JML.    YM  .JMML.      VF      .JMMmmmmMMM .JMML. .JMM.P"Ybmmd"    `"bmmd"´
';
