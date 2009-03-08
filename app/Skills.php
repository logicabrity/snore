<?php
/**
 * @file
 * @brief implement Skills class
 */

/**
 * @brief Parse the skills.yaml file.
 *
 * This class is used every time we need a list of all skills
 * that exist in the game.
 */
class Skills {
  private $file, $yaml, $list;

  /**
   * @brief compute the path of the skills.yaml file
   *
   * Could be replaced by a call to some FileProvider class
   * that does some checks on the file?
   */
  public function __construct($lang=LANG) {
    $this->file = PROJECT_DIR . '/data/' . $lang . '/skills.yaml';
    $this->yaml = explode("\n", file_get_contents($this->file));
    $this->list = array();
  }

	/**
	 * @brief returns all skills grouped by categories
	 * @param[in] $lang desired language of the results
	 * @param[out] $list two-dimensional array of skills grouped by categories
	 *
	 * This is used when the roster is generated. Ultimately, the template
	 * _javascript.tpl produces javascript-arrays, which are very important
	 * to the skill-handling in the javascript.
	 */
	public function nested() {
		$last_opened_node = '';
		foreach ( $this->yaml as $line ) {
			if ( substr($line,0,3) == " - " ) {
				$this->list[$last_opened_node][] = substr($line,3);
			}
			else {
				$last_opened_node = substr($line,0,-1); // because last char is ':'
			}
		}
		return $this->list;
	}

  /**
   * @brief returns all skills
   * @param[in] $lang desired language of the results
   * @param[out] $list simple numbered array of all skills
   *
   * Used twice (once for every language) everytime a
   * translation is built. The arrays are later combined.
   */
  public function flat() {
    foreach ( $this->yaml as $line ) {
      if ( substr($line,0,3) == " - " ) {
        $this->list[] = substr($line,3);
      }
    }
    return $this->list;
  }

  /**
   * @brief static interface to skill->flat
   *
   * to have calls to skills->flat in the application
   * stand on a single line, this defines a static
   * function.
   *
   */
  public static function all_flat($lang=LANG) {
    $skills = new Skills($lang);
    return $skills->flat();
  }

  /**
   * @brief static interface to skill->nested
   *
   * to have calls to skills->nested in the application
   * stand on a single line, this defines a static
   * function.
   *
   */
  public static function all_nested($lang=LANG) {
    $skills = new Skills($lang);
    return $skills->nested();
  }
}
?>
