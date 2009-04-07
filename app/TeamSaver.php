<?php
/**
 * @file
 * @brief implement TeamSaver class
 */

/**
 * @brief Return a roster as xml.
 */
class TeamSaver {
  private $XmlWriter, $data, $result, $race;

  /**
   * @brief setup
   *
   * Create XmlWriter instance, and set some
   * options, like indentation. Prepare needed
   * variables.
   */
  private function __construct() {
    $this->data = array();
    $this->result = "";
    $this->XmlWriter = new XmlWriter();
    $this->XmlWriter->openMemory();
    $this->XmlWriter->setIndent(true);
    $this->XmlWriter->setIndentString("  ");
  }

  /**
   * @brief given data, returns xml
   * @param[in] $post_data data which comes from the html-form
   * @param[in] $lang $language of the data
   * @param[out] $result xml string
   */
  public static function save($data, $lang=LANG) {
    $team = new TeamSaver();
    $team->prepare($data);
    $team->data = $data;
    $team->race = getRaceInfo($data['RACE_ID']);
    $team->write_team();
    //$team->correct_fullEndElement_bug(); // missing newline, see comment in function
    $result = $team->result;
    return $result;
  }

  /**
   * @brief prepares the data for translation
   * @param[in] $data the data
   *
   * In the user interface, list content (skills, injuries)
   * is stored in a string (example: block,dodge or M,N,-AG).
   * This has to be transformed into an array.
   *
   * Additionally, some temporary variables can be removed.
   */
  private function prepare(&$data) {
    unset($data['VERBOSE']);
    unset($data['SKILLSRCN'], $data['SKILLSRCD'], $data['SKILLSRCF'], $data['SKILLDEST']);
    unset($data['TEMP1'], $data['TEMP2'], $data['TEMP3']);
    unset($data['HEALTHY']);

    for($i=16; $i<=20; $i++) {
      unset($data['VALUE'][$i]);
    }

    foreach($data['SKILLS'] as &$v) {
      if ($v != "") {
        $v = explode(', ', $v);
      }
    }

    foreach($data['INJURIES'] as &$v) {
      if ($v != "") {
        $v = str_replace('-','',$v);
        $v = explode(', ', $v);
      }
    }
  }

  /**
   * @brief builds the xml of the roster
   *
   * Starts and ends the document. Inbeetween, some
   * parts have been refactored out into the other write_* methods.
   */
  private function write_team() {
    $this->XmlWriter->startDocument();
    $this->XmlWriter->startElement('team');
    $this->write_team_attributes();
    $this->XmlWriter->writeElement('formation', "default.xml");
    $this->XmlWriter->writeElement('name',$this->data['TEAM']);
    $this->XmlWriter->writeElement('coach',$this->data['HEADCOACH']);
    $this->XmlWriter->writeElement('treasury',$this->data['TREASURY']);
    $this->XmlWriter->writeElement('reroll',$this->data['REROLLS']);
    $this->XmlWriter->writeElement('fanfactor',$this->data['FANFACTOR']);
    $this->XmlWriter->writeElement('assistant',$this->data['COACHES']);
    $this->XmlWriter->writeElement('cheerleader',$this->data['CHEERLEADERS']);
    $this->XmlWriter->writeElement('apothecary',$this->data['APOTHECARY']);
    $this->write_players();
    $this->XmlWriter->writeElement('background',$this->data['BACKGROUND']);
    $this->XmlWriter->endDocument();
    $this->result = $this->XmlWriter->flush();
  }

  /**
   * @brief Write the attributes of the team element.
   */
  private function write_team_attributes() {
    $this->XmlWriter->writeAttribute('xmlns:xsi',"http://www.w3.org/2001/XMLSchema-instance");
    $this->XmlWriter->writeAttribute('bb_version','5');
    $this->XmlWriter->writeAttribute('xsi:noNamespaceSchemaLocation','team.xsd');
    $this->XmlWriter->writeAttribute('race', $this->data['RACE']);
    $this->XmlWriter->writeAttribute('id', $this->data['RACE_ID']);
    $this->XmlWriter->writeAttribute('emblem', $this->data['TEAMLOGO']);
  }

  /**
   * @brief build the players element
   *
   * Delegates the grunt-work to write_player().
   */
  private function write_players() {
    $this->XmlWriter->startElement('players');
    foreach(range(0, 15) as $i) {
      if ( $this->data['POSITION'][$i] > 0 ) {
        $this->write_player($i);
      }
    }
    $this->XmlWriter->endElement();
  }

  /**
   * @brief write a player element
   */
  private function write_player($index) {
    $this->XmlWriter->startElement('player');
    $this->write_player_attributes($index);
    $position_id = $this->data['POSITION'][$index] - 1;
    $this->XmlWriter->writeElement('positionid', (int) $this->race->positions->position[$position_id]->positionid);
    $this->XmlWriter->writeElement('ma',$this->data['MA'][$index]);
    $this->XmlWriter->writeElement('st',$this->data['ST'][$index]);
    $this->XmlWriter->writeElement('ag',$this->data['AG'][$index]);
    $this->XmlWriter->writeElement('av',$this->data['AV'][$index]);
    $this->XmlWriter->writeElement('cost',$this->data['VALUE'][$index]);
    $this->write_player_skills($index);
    $this->write_player_injuries($index);
    $this->XmlWriter->writeElement('com',$this->data['COMP'][$index]);
    $this->XmlWriter->writeElement('td',$this->data['TD'][$index]);
    $this->XmlWriter->writeElement('int',$this->data['INT'][$index]);
    $this->XmlWriter->writeElement('cas',$this->data['CAS'][$index]);
    $this->XmlWriter->writeElement('mvp',$this->data['MVP'][$index]);
    $this->XmlWriter->writeElement('spp',$this->data['SPP'][$index]);
    $this->XmlWriter->endElement();
  }

  /**
   * @brief write the attributes of the player element
   */
  private function write_player_attributes($index) {
    $this->XmlWriter->writeAttribute('name',$this->data['NAME'][$index]);
    $this->XmlWriter->writeAttribute('number',$index+1);
    $position_id = $this->data['POSITION'][$index] - 1;
    $this->XmlWriter->writeAttribute('position', (string) $this->race->positions->position[$position_id]->title);
    $this->XmlWriter->writeAttribute('display', (string) $this->race->positions->position[$position_id]->display);
  }

  /**
   * @brief write the skills of a player
   */
  private function write_player_skills($index) {
    if ( $this->data['SKILLS'][$index] ) {
      foreach ( $this->data['SKILLS'][$index] as $skill ) {
        $this->XmlWriter->writeElement('skill',$skill);
      }
    }

  }

  /**
   * @brief write the injuries of a player
   */
  private function write_player_injuries($index) {
    $this->XmlWriter->startElement('injuries');
    $miss = false; $niggling = 0;
    $st = 0; $ag = 0; $ma = 0; $av = 0;
    if ( ($this->data['INJURIES'][$index]) ) {
      foreach ( $this->data['INJURIES'][$index] as $injury ) {
        switch ($injury) {
          case 'M':
            $miss = true;
            break;
          case 'N':
            $niggling++;
            break;
          case 'MA':
            $ma++;
            break;
          case 'ST':
            $st++;
            break;
          case 'AG':
            $ag++;
            break;
          case 'AV':
            $av++;
            break;
          default:
            die("unknown injury string.");
            break;
        }
      }
    }

    if ( $miss == true )  $this->XmlWriter->writeAttribute('missNextMatch',1);
    if ( $niggling > 0 )  $this->XmlWriter->writeAttribute('nigglingInjuries',$niggling);
    if ( $ma > 0 )        $this->XmlWriter->writeAttribute('MaReduction',$ma);
    if ( $st > 0 )        $this->XmlWriter->writeAttribute('StReduction',$st);
    if ( $ag > 0 )        $this->XmlWriter->writeAttribute('AgReduction',$ag);
    if ( $av > 0 )        $this->XmlWriter->writeAttribute('AvReduction',$av);

    $this->XmlWriter->fullEndElement();
  }

  /**
   * @brief append a newline after a the /inj tag
   * @deprecated apparently issue was resolved?
   *
   * XmlWriter->fullEndElement doesn't append a newline after the tag,
   * so we look for the tag using regular expressions.
   */
  private function correct_fullEndElement_bug() {
    $w = '/\<\/inj\>/';
    $c = "</inj>\n";
    $this->result = preg_replace($w, $c, $this->result);
  }
}
?>
