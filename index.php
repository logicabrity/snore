<?php
/**
 * @file
 * @brief Main file, or "controller".
 *
 * See page @link Controller @endlink for more details.
 */
require_once 'config.php';

/* user saves a roster => process it, return xml ************************ */

if ( isset($_POST['TEAM']) ) {
  require_once PROJECT_DIR . '/lib/TeamSaver.php';
  $data = translateTeamBeforeSaving($_POST, $_POST['LANG']);
  header('Content-type: application/xml');
  header('Content-Disposition: attachment; filename="'.$_POST['TEAM'].'.xml"');
  echo TeamSaver::save($data);
}

/* user selected a race => show a roster ************************************ */

elseif ( isset($_GET['race']) ) {
  $race_id = htmlentities($_GET['race']);
  if ( is_numeric($race_id) && $race_id >= 0 && $race_id <= 20 ) {
    show_roster($race_id);
  }
  else {
    $errorCode = 1; // for the template
    show_index($errorCode); // invalid race, show welcome-page instead of roster
  }
}

/* user uploaded a file => parse it, show a roster ************************** */

elseif ( isset($_POST['upload']) ) {
  if (	array_key_exists('userfile', $_FILES) &&
        $_POST['upload'] == true &&
        $_FILES['userfile']['error'] != UPLOAD_ERR_NO_FILE ) {
    $file = $_FILES['userfile']['tmp_name'];
    require_once PROJECT_DIR . '/lib/TeamLoader.php';
    $team = translateTeamAfterLoading(TeamLoader::load($file), $_POST['LANG']);
    show_roster($team['raceName'], $team);
  }
  else {
    $errorCode = 2; // for the template
    show_index($errorCode); // invalid upload, show welcome-page
  }
}

/* nothing happened => show the welcome-page ******************************** */

else {
  show_index();
}

?>