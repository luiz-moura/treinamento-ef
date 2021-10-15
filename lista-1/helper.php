<?php
function write($archive, $data) {
  $open = fopen($archive, "w");
  fwrite($open, $data); 
  fclose($open);
}

function read($archive) {
  $content = null;

  if (file_exists($archive)) {
    $open = fopen($archive, "r");
    if (filesize($archive) > 0) {
      $content = fread($open, filesize($archive));
    }
  }
	fclose($open);

  return $content;
}

function asc($a, $b) {
  return $a["points"] > $b["points"];
}

function desc($a, $b) {
  return $a["points"] < $b["points"];
}

function convertSecondsToHours($t,$f=':') {
  return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
}