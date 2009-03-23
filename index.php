<?php
/**
 * @file
 * @brief Main file, or "controller".
 *
 * See page @link Controller @endlink for more details.
 */
require_once 'app/helper.php';
require_once 'config.php';

/* user saves a roster => process it, return xml ************************ */

if ( isset($_POST['TEAM']) ) {
  header('Content-type: application/xml');
  header('Content-Disposition: attachment; filename="'.$_POST['TEAM'].'.xml"');
  echo TeamSaver::save($_POST);
}

/* user selected a race => show a roster ************************************ */

elseif ( isset($_GET['race']) ) {
  $race_id = htmlentities($_GET['race']);
  if ( is_numeric($race_id) && $race_id >= 0 && $race_id <= 20 ) {
    show_roster($race_id);
  }
  else {
    $getError_race = TRUE; // for the template
    show_index(); // race is not valid, show welcome-page instead of roster
  }
}

/* user uploaded a file => parse it, show a roster ************************** */

elseif ( isset($_POST['upload']) ) {
  if (	$_POST['upload'] == true &&
        $_FILES['userfile']['error'] != UPLOAD_ERR_NO_FILE ) {
    $team = TeamLoader::load($_FILES['userfile']['tmp_name']);
    show_roster($team['id'], $team);
  }
  else {
    $getError_upload = TRUE; // for the template
    show_index(); // there was a problem with the upload, show welcome-page
  }
}

/* nothing happened => show the welcome-page ******************************** */

else {
  show_index();
}
?>
