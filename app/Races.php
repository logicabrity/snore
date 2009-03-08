<?php
/**
 * @file
 * @brief implement Races class
 */

 /**
  * @brief Parse correct races.xml file.
  *
  * The member functions will all return results in the
  * language given as a parameter.
  */
class Races {
	/**
	 * @brief return everything there is to know about a specific race
	 * @param[in] $race_id the position in which the race appears in the file
	 * @param[in] $lang language so the application knows which race file to parse
	 * @param[out] $data an array and simplexml-object crossbreed
	 *
	 * This really is the fastest way to get all information we want,
	 * because simplexml_load_file maps the whole file to a sort of array.
	 */
	public static function get($race_id, $lang=LANG) {
		$file = 'data/' . $lang . '/races.xml';
		$xml = simplexml_load_file($file);
		// as of PHP 5.2.3 don't remove the int typecast
		$data = $xml->race[(int)$race_id];
		return $data;
	}

	/**
	 * @brief list all races that exist
	 * @param[in] $lang the current language
	 * @param[out] $list a numbered array
	 *
	 * The parsing is done with the XmlReader class, which is perhaps
	 * not as easy to use as the simplexml one, but it is more
	 * memory-efficient to parse the file by hand instead
	 * of mapping everything to a huge array, only to traverse it
	 * and grab the race-names.
	 */
	public static function list_all($lang=LANG) {
		$file = 'data/' . $lang . '/races.xml';

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
	 * @brief list the positionals of a specific race
	 * @param[in] $race_id the position in which the race appears in the file
	 * @param[in] $lang the current language
	 * @param[out] $list numbered array listing the names of all the positions
	 */
	public static function list_positions($race_id, $lang=LANG) {
		$file = 'data/' . $lang . '/races.xml';
		$xml = simplexml_load_file($file);

		$list = array();
		foreach ( $xml->race[(int)$race_id]->positions->position as $position) {
			$list[] = (string)$position->title;
		}
		return $list;
	}
}
?>