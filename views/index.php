<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

  <head>
    <title>SNORE</title>
    <meta name="author" content="Marc-Antonio Bisotti"/>
    <meta name="date" content="2008-04-29 T13:15:15+0100"/>
    <meta name="copyright" content="GNU General Public License" />
    <meta name="keywords" content="Blood Bowl TowBowlTactics Team Editor Roster Editor" />
    <meta name="description" content="Manages TowBowlTactics Teamsheets according to the LRB 5.0" />
    <meta http-equiv="content-type" content="application/xhtml+xml;charset=utf-8" />
    <link rel="stylesheet" type="text/css" media="screen" href="public/styles/index.css" title="Default" />
  </head>

  <body>

    <div id="container">

      <div id="language">
        <a href="index.php?lang=en">english</a>
        <a href="index.php?lang=fr">franc&#807;ais</a>
        <a href="index.php?lang=de">deutsch</a>
      </div>

      <div id="meta">
        <h1>SNORE</h1>
        <p>the &quot;Super New Online Roster Editor&quot;</p>
      </div>

      <p>
        <?php echo $t['intro']; ?>
      </p>

      <!-- Start a new team -->
      <h2><?php echo $t['new']; ?></h2>

      <p class="error"><?php if ( $errorCode == 1 ): echo $t['invalid_race']; endif; ?></p>

      <div id="races">&#9674;
<?php $i=1; foreach ( $races as $id => $race ): ?>
        <a href="index.php?lang=<?php echo LANG; ?>&race=<?php echo $id; ?>"><?php echo str_replace(" ", "&nbsp;", $race); ?></a> &#9674;
<?php if ($i%5==0) { echo "        <br />\n"; } $i++; endforeach; ?>
      </div>

      <!-- Load an existing team. -->
      <h2><?php echo $t['load']; ?></h2>

      <p class="error"><?php if ( $errorCode == 2 ): echo $t['upload_error']; endif; ?></p>

      <form action="index.php?lang=<?php echo LANG; ?>" method="post" enctype="multipart/form-public">
        <input type="hidden" name="LANG" value="<?php echo LANG; ?>" />
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <input type="hidden" name="upload" value="true" />
        <p>
          <input name="userfile" type="file" />
          <input type="submit" />
        </p>
      </form>

    </div><!-- id="container" -->
  </body>
</html>