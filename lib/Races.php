<?php
/**
 * @brief return everything there is to know about a specific race
 * @param[in] $raceId the id attribute of the chosen race
 * @param[out] $data an simplexml object
 *
 * Parses the file data/en/races.xml. This really is the fastest way to get
 * all information we want, because simplexml_load_file maps the whole file
 * to a sort of array.
 */
function getRaceInfo($raceId) {
  $file = 'data/races.xml';
  $xml = simplexml_load_file($file);
  // as of PHP 5.2.3 don't remove the int typecast
  $data = $xml->race[(int)$raceId];
  return $data;
}

/**
 * @brief list all races that exist in english
 * @param[out] $list a numbered array
 *
 * The parsing is done with the XmlReader class, which is perhaps
 * not as easy to use as the simplexml one, but it is more
 * memory-efficient to parse the file by hand instead
 * of mapping everything to a huge array, only to traverse it
 * and grab the race-names.
 */
function getRacesList() {
  $file = 'data/races.xml';

  $XmlReader = new XmlReader();
  $XmlReader->open($file);

  $list = array();
  while ( $XmlReader->read() ) {
    if (	$XmlReader->nodeType == XMLREADER::ELEMENT &&
        $XmlReader->name == 'race' ) {
      $XmlReader->moveToAttributeNo(1);
      $list[] = $XmlReader->value;
      $XmlReader->next(); // skips the child elements
    }
  }
  return $list;
}

/**
 * @brief list the positionals of a specific race in english
 * @param[in] $raceId the id attribute of the chosen race
 * @param[out] $list numbered array listing the names of all the positions
 */
function listPositionsOfRace($raceId) {
  $file = 'data/races.xml';
  $xml = simplexml_load_file($file);

  $list = array();
  foreach ( $xml->race[(int)$raceId]->positions->position as $position) {
    $list[] = (string)$position->title;
  }
  return $list;
}
?>