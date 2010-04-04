<?php
/**
 * @file
 * @brief implement TeamLoader class
 */

 /**
  * @brief Parse a roster.
  *
  * Given a roster stored in a xml file,
  * loading it with TeamLoader:load will
  * parse it into an array, so it can be
  * used by the application.
  */
class TeamLoader {
  private $XmlReader, $result, $temp_player,
          $last_opened_element_name,
          $parsing_inside_player_element;

  /**
   * @brief loads the roster from an xml file.
   * @param[in] $file the path of the roster
   * @param[in] $lang (optional) language the roster is translated into
   * @param[out] $roster an array in the language in $lang
   *
   * usage, for instance for handling an uploaded roster:
   * $roster = Teamloader::load($_FILES['userfile']['tmp_name']);
   * The result (which was translated into the language given
   * in LANG) can directly be passed to the templates.
   *
   */
  public static function load($file, $lang=LANG) {
    $team = new TeamLoader($file);
    $team->to_array();
    $result = $team->result;
    $result['raceName'] = $result['race'];
    return $result;
  }

  /**
   * @brief prepare for parsing
   * @param[in] $file the path of the roster
   *
   * Gets called by the static method load($file).
   */
  private function __construct($file) {
    $this->XmlReader = new XmlReader();
    $this->XmlReader->open($file);
    $this->last_opened_element_name = "";
    $this->result = array();
    $this->result['player'] = array();
    $this->temp_player = array();
  }

  /**
   * @brief Automatic closing of the XmlReader.
   */
  public function __destruct() {
    $this->XmlReader->close();
  }

  /**
   * @brief parse the xml and transform it into an array
   *
   * Straight-forward xml parsing with XmlReader. Reacts
   * to start and end tags. It knows certain tags (team, player, inj)
   * where it has to look for attributes, which are then handled
   * by the attributes() method. Text is delegated to handle_text().
   *
   * When parsing a player, content is buffered into the $temp_player
   * array. Then, after we have parsed the player, we know his number and
   * can attach him to the team array with his number as index.
   *
   */
  private function to_array() {
    while ( $this->XmlReader->read() ) {
      switch ( $this->XmlReader->nodeType ) {
        case XMLReader::ELEMENT:
          $this->last_opened_element_name = $this->XmlReader->name;
          switch ($this->last_opened_element_name) {
            case 'team':
              $this->attributes();
              break;
            case 'player':
              $this->parsing_inside_player_element = TRUE;
              $this->temp_player = array();
              $this->attributes();
              break;
            case 'injuries':
              $this->attributes();
              break;
            default:
              break;
          }
          break;
        case XMLReader::TEXT:
          $this->handle_text();
          break;
        case XMLReader::END_ELEMENT:
          if ( $this->XmlReader->name == 'player' ) {
            // connect the $temp_player variable to the correct place in the team array
            $player_number = $this->temp_player['number'] - 1;
            $this->result['player'][$player_number] = $this->temp_player;
            unset($this->temp_player);
          }
          break;
        default:
          break;
      }
    }
  }

  /**
   * @brief handle text when parsing xml
   */
  private function handle_text() {
    if ( $this->parsing_inside_player_element == TRUE ) {
      if ( $this->last_opened_element_name == "skill" ) {
        $this->temp_player['skill'][] = $this->XmlReader->value;
      }
      else {
        $this->temp_player[$this->last_opened_element_name] =
          $this->XmlReader->value;
      }
    }
    else {
      $this->result[$this->last_opened_element_name] = $this->XmlReader->value;
    }
  }

  /**
   * @brief handle attributes when parsing xml
   *
   * Does not get called for every tag with attributes, but only
   * for tags that are known to have attributes in to_array().
   */
  private function attributes() {
    $attribute_count = $this->XmlReader->attributeCount;
    for ( $i=0; $i<$attribute_count; $i++ ) {
      $this->XmlReader->moveToNextAttribute();
      switch ($this->last_opened_element_name) {
        case 'team':
          $this->result[$this->XmlReader->name] = $this->XmlReader->value;
        break;
        case 'player':
          $this->temp_player[$this->XmlReader->name] = $this->XmlReader->value;
        break;
        case 'injuries':
          switch ($this->XmlReader->name) {
            case 'missNextMatch':
              $this->temp_player['inj'][] = 'M';
            break;
            case 'nigglingInjuries':
              for ($j=0; $j < $this->XmlReader->value; $j++) {
                $this->temp_player['inj'][] = 'N';
              }
            break;
            default:
              // $this->XmlReader->name is something like AgReduction
              // so 'Ag' is extracted to have the injured stat.
              $str = '-' . strtoupper(substr($this->XmlReader->name, 0, 2));
              for ($k=0; $k < $this->XmlReader->value; $k++) {
                $this->temp_player['inj'][] = $str;
              }
            break;
          }
        break;
      }
    }
  }
}
?>