<?php
/**
 * @file
 * @brief implement UserInterface class
 */

/**
 * @brief Returns all the text that composes the UI.
 *
 * The UserInterface class is mostly a wrapper around
 * the Spyc YAML library.
 */
class UserInterface {
	/**
	 * @brief Parse correct yaml file.
	 * @param[in] $page requested page, i.e. roster or index
	 * @param[in] $lang desired language of the results
	 * @param[out] $data associative array
	 */
	public static function get($page, $lang=LANG) {
		if ( $page != 'index' && $page != 'roster' ) {
			die("Page $page does not exist.");
		}
		$file = "data/$lang/$page.yaml";
		$data = Spyc::yaml_load($file);
		return $data;
	}

	/**
	 * @brief List stats and injury abbrevations.
	 * @param[in] $lang desired language of the results
	 * @param[out] $stats numbered array where values are the abbrevations
	 */
	public static function stats($lang=LANG) {
		$file = 'data/' . $lang . '/roster.yaml';
		$data = Spyc::yaml_load($file);

		$stats = array();
		$stats[] = $data['stats_ma'];
		$stats[] = $data['stats_st'];
		$stats[] = $data['stats_ag'];
		$stats[] = $data['stats_av'];
		$stats[] = $data['injuries_m'];
		$stats[] = $data['injuries_n'];
		return $stats;
	}
}
?>