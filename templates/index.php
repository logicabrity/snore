<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

  <head>
    <title>SNORE - TBT Roster Editor</title>
    <meta name="author" content="Marc-Antonio Bisotti"/>
    <meta name="date" content="2008-04-29 T13:15:15+0100"/>
    <meta name="copyright" content="GNU General Public License" />
    <meta name="keywords" content="Blood Bowl TowBowlTactics Team Editor Roster Editor" />
    <meta name="description" content="Manages TowBowlTactics Teamsheets according to the LRB 5.0" />
    <meta http-equiv="content-type" content="application/xhtml+xml;charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="screen" href="styles/index.css" title="Default" />
  </head>

  <body>

    <div id="title">

      <div id="flags">
        <form action="index.php" method="post" style="float: left;">
          <div>
            <input type="hidden" name="lang" value="en"></input>
            <button type="submit">
              <img src="data/pictures/en_flag.jpg" alt="union jack" />
            </button>
          </div>
        </form>
        <form action="index.php" method="post" style="float: left;">
          <div>
            <input type="hidden" name="lang" value="fr"></input>
            <button type="submit">
              <img src="data/pictures/fr_flag.jpg" alt="french flag" />
            </button>
          </div>
        </form>
        <form action="index.php" method="post" style="float: left;">
          <div>
            <input type="hidden" name="lang" value="de"></input>
            <button type="submit">
              <img src="data/pictures/de_flag.jpg" alt="german flag" />
            </button>
          </div>
        </form>
      </div><!-- id="flags" -->

      <h1>TBT - SNORE</h1>
      <p class="subtitle">the &quot;Super New Online Roster Editor&quot;</p>
    </div><!-- id="title" -->

    <div id="content">
      <p><?php echo $t['intro']; ?></p>

      <h2><?php echo $t['new']; ?></h2><!-- Start a new team -->
      <span class="error"><?php if ( $errorCode == 1 ): echo $t['invalid_race']; endif; ?></span>
      <div id="list">
<?php foreach ( $races as $id => $race ): ?>
        <a class="race" href="index.php?race=<?php echo $id; ?>"><?php echo $race; ?></a>
<?php endforeach; ?>
      </div>

      <h2><?php echo $t['load']; ?></h2><!-- Load an existing team. -->
      <span class="error"><?php if ( $errorCode == 2 ): echo $t['upload_error']; endif; ?></span>
      <form action="index.php" method="post" enctype="multipart/form-data">
        <p>
          <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
          <input type="hidden" name="upload" value="true" />
        </p>
        <p>
          <input name="userfile" type="file" />
          <input type="submit" />
        </p>
      </form>

    </div><!-- id="content" -->

    <div id="foot">
      <p>r <?php echo $version; ?></p>
    </div>
    
  </body>

</html>