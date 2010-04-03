<?php

define('RACES_FILE', PROJECT_DIR."/data/races.yml");

function getRaceInfo($raceID, $file=RACES_FILE) {
  $yml = Spyc::YAMLLoad($file);
  if ( is_numeric($raceID) ) {
    $yml = Spyc::YAMLLoad($file);
    return $yml[$raceID];
  }
  else {
    foreach ( $yml as $item ) {
      if ( $item['race'] == $raceID ) {
        return $item;
      }
    }
  }
}

function getRaceList($file=RACES_FILE) {
  $yml = Spyc::YAMLLoad($file);
  $raceList = Array();
  foreach ( $yml as $item ) {
    $raceList[] = $item['race'];
  }
  return $raceList;
}