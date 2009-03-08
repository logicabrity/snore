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
    <link rel="stylesheet" type="text/css" media="screen" href="views/styles/index.css" title="Default" />
  </head>

  <body>

    <div id="title">
      <div id="flags">

        <form action="index.php" method="post" style="float: left;">
          <div><input type="hidden" name="lang" value="en"></input>
          <button type="submit"><img src="data/en/flag.jpg" alt="union jack" /></button></div>
        </form>

        <form action="index.php" method="post" style="float: left;">
          <div><input type="hidden" name="lang" value="fr"></input>
          <button type="submit"><img src="data/fr/flag.jpg" alt="french flag" /></button></div>
        </form>

        <form action="index.php" method="post" style="float: left;">
          <div><input type="hidden" name="lang" value="de"></input>
          <button type="submit"><img src="data/de/flag.jpg" alt="german flag" /></button></div>
        </form>

      </div>
      <h1>TBT - SNORE</h1>
      <p class="subtitle">the &quot;Super New Online Roster Editor&quot;</p>
    </div>

    <div id="content">
      <p>{$t.intro}</p>

      <!-- Start a new team -->
      <h2>{$t.new}</h2>
        <span class="error">{insert name="getError_race" message=$t.invalid_race}</span>
      <div id="list">
        {foreach from=$races key=id item=race}
        <a class="block" href="index.php?race={$id}">{$race}</a>
        {/foreach}
      </div>

      <!-- Load an existing team. -->
      <h2>{$t.load}</h2>
      <form action="index.php" method="post" enctype="multipart/form-data">
        <p>
          <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
          <input type="hidden" name="upload" value="true" />
        </p>
        <p>
          {$t.upload_label}
          <input name="userfile" type="file" />
          <input type="submit" />
          <span class="error">{insert name="getError_upload" message=$t.upload_error}</span>
        </p>
      </form>

    </div>

    <div id="foot">
      <p>r {$version}</p>
    </div>

  </body>

</html>
