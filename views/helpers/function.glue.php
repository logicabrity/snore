<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.glue.php
 * Type:     function
 * Name:     glue
 * Purpose:  alias for the php implode function
 * -------------------------------------------------------------
 */
function smarty_function_glue($params, &$smarty) {
	if ( isset($params['var']) ) {
		if ( is_array($params['var']) ) {
			return implode(', ', $params['var']);
		}
		else {
			return $params['var'];
		}
	}
	else {
		return '';
	}
}
?>