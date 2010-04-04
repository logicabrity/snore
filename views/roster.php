<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>TBT Snore</title>
  <meta http-equiv="content-type" content="application/xhtml+xml;charset=utf-8" />
  <link rel="stylesheet" type="text/css" media="screen" href="public/styles/roster.css" title="Default" />
  <link rel="stylesheet" type="text/css" media="print" href="public/styles/print.css" title="Default" />
  <script type="text/javascript" src="public/js/functions.js"></script>
  <script type="text/javascript" src="public/js/roster.js"></script>
  <script type="text/javascript">
    skills      = new Array()
    skills['g'] = new Array("<?= implode('","', $s['General']) ?>")
    skills['p'] = new Array("<?= implode('","', $s['Passing']) ?>")
    skills['a'] = new Array("<?= implode('","', $s['Agility']) ?>")
    skills['s'] = new Array("<?= implode('","', $s['Strength']) ?>")
    skills['m'] = new Array("<?= implode('","', $s['Mutation']) ?>")
    skills['e'] = new Array("<?= implode('","', $s['Extraordinary']) ?>")

    warning = new Array()
<?php foreach ( $t['js_warnings'] as $i => $w ): ?>
    warning[<?= $i ?>] = "<?= $w ?>"
<?php endforeach; ?>

    var M  = "<?= $t['injuries_m']; ?>"
    var N  = "<?= $t['injuries_n']; ?>"
    var MA = "<?= $t['stats_ma']; ?>"
    var ST = "<?= $t['stats_st']; ?>"
    var AG = "<?= $t['stats_ag']; ?>"
    var AV = "<?= $t['stats_av']; ?>"

    stats     = new Array()
    stats[0]  = new Array("","","","","","",99,16)
    skills[0] = new Array()
<?php $i=0; foreach ( $r['positions'] as $p ): $i++; ?>
    stats[<?php echo $i; ?>]  = new Array(<?php
      echo '"',$p['title'],'",',implode(",",$p['stats']),',',$p['cost'],',',$p['limit'],',0,"',
               $p['skills-access'][0],'","',$p['skills-access'][1],'","',$p['display'],'"'; ?>)
    skills[<?= $i ?>] = new Array("<?= implode('","', $p['skills']); ?>")
<?php endforeach; ?>

    var positions   = <?= count($r['positions']),"\n" ?>
    var apothecary  = new Boolean(<?= $r['medic-allowed'] ?>)
    var reroll_cost = <?= $r['reroll-cost'],"\n" ?>
    var box_visible = new Boolean(false)
  </script>
</head>
  <body onload="player_set_injured();team_get_number_of_healthy();team_set_total_value();">

    <form id="ROSTER" action="index.php" method="post" enctype="multipart/form-data">
      <input name="RACE_ID"   type="hidden" value="<?= $raceId; ?>" />
      <input name="LANG"      type="hidden" value="<?= LANG; ?>" />
      <input name="TEAMLOGO"  type="hidden" value="<?= $r['emblem'] ?>" />

      <table id="roster">
        <thead>
          <tr>
            <th>#</th>
            <th><?= $t['player_name'] ?></th>
            <th><?= $t['player_position'] ?></th>
            <th><?= $t['stats_ma'] ?></th>
            <th><?= $t['stats_st'] ?></th>
            <th><?= $t['stats_ag'] ?></th>
            <th><?= $t['stats_av'] ?></th>
            <th colspan="2"><?= $t['skills'] ?></th>
            <th><?= $t['injuries'] ?></th>
            <th><?= $t['spp'] ?></th>
            <th><?= $t['value'] ?></th>
          </tr>
        </thead>

        <!-- Start of Players -->
        <tbody>
<?php foreach( range(0,15) as $i ): ?>
<?php if ( ak($lt['player'],$i) != "" ): $lp = $lt['player'][$i]; else: $lp = NULL; endif; ?>

          <!-- row for player number <?= $i+1 ?> -->
          <tr>
            <td class="label"><?= $i+1 ?></td>
            <td><input name="NAME[]" type="text" value="<?= ak($lp,'name') ?>" onchange="player_set_name(<?= $i ?>)" /></td>
            <td>
              <select name="POSITION[]" class="invisible" onchange="player_set_position(<?php echo $i; ?>);team_get_number_of_healthy()">
                <option value="0"></option>
<?php foreach ( $r['positions'] as $j => $p ): ?>
                <option <?php if ( $p['title'] == ak($lp,'position') ): echo 'selected="selected"'; endif; ?> value="<?= $j+1 ?>"><?= $p['title'] ?></option>
<?php endforeach; ?>
              </select>
            </td>
            <td><input class="num-s" name="MA[]" type="text" value="<?= ak($lp,'ma') ?>" readonly="readonly" /></td>
            <td><input class="num-s" name="ST[]" type="text" value="<?= ak($lp,'st') ?>" readonly="readonly" /></td>
            <td><input class="num-s" name="AG[]" type="text" value="<?= ak($lp,'ag') ?>" readonly="readonly" /></td>
            <td><input class="num-s" name="AV[]" type="text" value="<?= ak($lp,'av') ?>" readonly="readonly" /></td>
            <td class="clickable" onclick="app_show_skill_box(<?= $i ?>)" colspan="2"><input class="txt-l" name="SKILLS[]" type="text" value="<?= implode(', ',ak($lp,'skill')) ?>" readonly="readonly" /></td>
            <td class="clickable" onclick="app_show_injury_box(<?= $i ?>)"><input class="txt-m" name="INJURIES[]" type="text" value="<?= implode(', ',ak($lp,'inj')) ?>" readonly="readonly" /></td>
            <td class="clickable" onclick="app_show_spp_box(<?= $i ?>)">
              <input name="COMP[]" type="hidden" value="<?= ak($lp,'com') ?>" />
              <input name="TD[]" type="hidden" value="<?= ak($lp,'td') ?>" />
              <input name="INT[]" type="hidden" value="<?= ak($lp,'int') ?>" />
              <input name="CAS[]" type="hidden" value="<?= ak($lp,'cas') ?>" />
              <input name="MVP[]" type="hidden" value="<?= ak($lp,'mvp') ?>" />
              <input class="num-s" name="SPP[]" type="text" value="<?= ak($lp,'spp') ?>" readonly="readonly" />
            </td>
            <td><input class="num-m" name="VALUE[]" type="text" value="<?= ak($lp,'cost') ?>" readonly="readonly" /></td>
          </tr>
<?php endforeach; ?>
          
        </tbody>
        <!-- End of Players -->

        <tfoot>
          <tr>
            <!-- race, number of players, rerolls -->
            <td rowspan="4"></td>
            <td class="label"><?= $t['race'] ?></td>
            <td colspan="5"><input name="RACE" type="text" value="<?= $r['race'] ?>" readonly="readonly" /></td>
            <td class="label"><?= $t['player'] ?> (<a href="javascript:app_show_journeymen_box()"><?= $t['journeymen_manage'] ?></a>)</td>
            <td><input class="num-s" name="HEALTHY" type="text" readonly="readonly" /></td>
            <td class="label"><?= $t['rerolls'] ?></td>
            <td><input class="num-s" name="REROLLS" type="text" value="<?= ak($lt,'reroll') ?>" onchange="extras_set_reroll_value()" /></td>
            <td><input class="num-m" name="VALUE[]" type="text" value="<?= ak($lt,'reroll')*$r['reroll-cost'] ?>" readonly="readonly" /></td>
          </tr>
          <tr>
            <!-- team-name, fanfactor, cheerleaders -->
            <td class="label"><?= $t['team']; ?></td>
            <td colspan="5"><input name="TEAM" type="text" value="<?= ak($lt,'name') ?>" /></td>
            <td class="label"><?= $t['fanfactor'] ?></td>
            <td><input class="num-s" name="FANFACTOR" type="text" value="<?= ak($lt,'fanfactor') ?>" onchange="extras_set_fans_and_cheerleaders_value()" /></td>
            <td class="label"><?= $t['cheerleaders'] ?></td>
            <td><input class="num-s" name="CHEERLEADERS" type="text" value="<?= ak($lt,'cheerleader') ?>" onchange="extras_set_fans_and_cheerleaders_value()" /></td>
            <td><input class="num-m" name="VALUE[]" type="text" value="<?= (ak($lt,'fanfactor')+ak($lt,'cheerleader'))*10000 ?>" readonly="readonly" /></td>
          </tr>
          <tr>
            <!-- coach-name, apothecary, assistant-coaches -->
            <td class="label"><?= $t['headcoach'] ?></td>
            <td colspan="5"><input name="HEADCOACH" type="text" value="<?= ak($lt,'coach') ?>" /></td>
            <td class="label"><?= $t['apothecary'] ?></td>
            <td><input class="num-s" name="APOTHECARY" type="text" value="<?= ak($lt,'apothecary') ?>" onchange="extras_set_medic_and_coaches_value()" /></td>
            <td class="label"><?= $t['assistants'] ?></td>
            <td><input class="num-s" name="COACHES" type="text" value="<?= ak($lt,'assistant') ?>" onchange="extras_set_medic_and_coaches_value()" /></td>
            <td><input class="num-m" name="VALUE[]" type="text" value="<?= ak($lt,'apothecary')*50000+ak($lt,'assistant')*10000 ?>" readonly="readonly" /></td>
          </tr>
          <tr>
            <!-- save, treasury, teamvalue -->
            <td class="label">SNORE</td>
            <td colspan="5"><a href="javascript:app_save()"><?= $t['save'] ?></a></td>
            <td class="label"><?= $t['treasury'] ?></td>
            <td><input class="num-m" name="TREASURY" type="text" value="<?= ak($lt,'treasury') ?>" /></td>
            <td class="label" colspan="2"><?= $t['teamvalue'] ?></td>
            <td><input class="num-m" name="TEAMVALUE" type="text" value="0" readonly="readonly" /></td>
          </tr>
        </tfoot>
      </table>

      <!-- Start of Journeymen-Box -->
      <div id="jm_box" class="element_hidden">
        <img class="close" src="public/pics/close.png" alt="<?= $t['button_cancel'] ?>" onclick="app_hide('jm_box')" />
        <h2><?= $t['journeymen'] ?></h2>
        <p><?= $t['journeymen_text'] ?></p>
<?php foreach ( range(0, 15) as $k ): ?>
        <a id="jm<?= $k ?>" href="javascript:player_legalize(<?= $k ?>)"><?= $k+1 ?></a>
<?php endforeach; ?>
      </div>
      <!-- End of Journeymen-Box -->

      <!-- Start of Skill-Box -->
      <div id="skill_box" class="element_hidden">
        <img class="close" src="public/pics/close.png" alt="<?= $t['button_cancel'] ?>" onclick="app_hide('skill_box')" />
        <h2><?= $t['skills'] ?> - <?= $t['player'] ?><input class="bignum-s" name="TEMP1" type="text" readonly="readonly" size="2" /></h2>
        <select class="fix" size="6" name="OWN_SKILLS"><option></option></select>
        <img src="public/pics/remove.png" alt="<?= $t['button_remove'] ?>" onclick="player_remove_skill()" /><br />
        <select class="fix" name="REP_NORMAL"><option></option></select>
        <img src="public/pics/add.png" alt="<?= $t['button_add'] ?>" onclick="player_add_skill('normal')" />
        <?= $t['skills_normal'] ?><br />
        <select class="fix" name="REP_DOUBLE"><option></option></select>
        <img class="button" src="public/pics/add.png" alt="<?= $t['button_add'] ?>" onclick="player_add_skill('double')" />
        <?= $t['skills_double'] ?><br />
        <select class="fix" name="REP_STATS">
          <option value="30000">+<?= $t['stats_ma'] ?></option>
          <option value="50000">+<?= $t['stats_st'] ?></option>
          <option value="40000">+<?= $t['stats_ag'] ?></option>
          <option value="30000">+<?= $t['stats_av'] ?></option>
        </select>
        <img src="public/pics/add.png" alt="<?= $t['button_add'] ?>" onclick="player_add_skill('stat')" />
        <?= substr($t['stats'], 0, 5) ?><br />
        <select class="fix" name="REP_FORBIDDEN"><option></option></select>
        <img src="public/pics/add.png" alt="<?= $t['button_add'] ?>" onclick="player_add_skill('impossible')" />
        <?= $t['skills_forbidden'] ?><br />
        <input name="TEMP3" type="hidden" readonly="readonly" value="0" />
      </div>
      <!-- End of Skill-Box -->

      <!-- Start of Injury-Box -->
      <div id="inj_box" class="element_hidden">
        <img class="close" src="public/pics/close.png" alt="<?= $t['button_cancel'] ?>" onclick="app_hide('inj_box')" />
        <h2><?= $t['injuries'] ?> - <?= $t['player'] ?><input class="bignum-s" name="TEMP2" type="text" readonly="readonly" size="2" /></h2>
        <select class="fix" name="OWN_INJURIES"><option></option></select>
        <img src="public/pics/remove.png" alt="<?= $t['button_remove'] ?>" onclick="player_remove_injury()" />
        <br />
        <select class="fix" name="REP_INJURIES">
          <option><?php echo $t['injuries_m']; ?></option>
          <option><?php echo $t['injuries_n']; ?></option>
          <option>-<?php echo $t['stats_ma']; ?></option>
          <option>-<?php echo $t['stats_st']; ?></option>
          <option>-<?php echo $t['stats_ag']; ?></option>
          <option>-<?php echo $t['stats_av']; ?></option>
        </select>
        <img class="button" src="public/pics/add.png" alt="<?= $t['button_add'] ?>" onclick="player_add_injury()" />
      </div>
      <!-- End of Injury-Box -->

      <!-- Start of SPP-Box -->
      <div id="spp_box" class="element_hidden">
        <img class="close" src="public/pics/close.png" alt="<?= $t['button_cancel'] ?>" onclick="app_hide('spp_box')" />
        <h2><?= $t['spp'] ?> - <?= $t['player'] ?><input class="bignum-s" name="TEMP4" type="text" readonly="readonly" size="2" /></h2>
        <table>
          <tr>
            <td class="label"><?= $t['spp_comp'] ?></td>
            <td><input class="num-s" name="TEMPCOMP" type="text" value="" onchange="player_set_spp()" /></td>
            <td class="label"><?= $t['spp_td'] ?></td>
            <td><input class="num-s" name="TEMPTD" type="text" value="" onchange="player_set_spp()" /></td>
          </tr>
          <tr>
            <td class="label"><?= $t['spp_int'] ?></td>
            <td><input class="num-s" name="TEMPINT" type="text" value="" onchange="player_set_spp()" /></td>
            <td class="label"><?= $t['spp_cas'] ?></td>
            <td><input class="num-s" name="TEMPCAS" type="text" value="" onchange="player_set_spp()" /></td>
          </tr>
          <tr>
            <td class="label"><?= $t['spp_mvp'] ?></td>
            <td><input class="num-s" name="TEMPMVP" type="text" value="" onchange="player_set_spp()" /></td>
            <td class="label"><?= $t['spp'] ?></td>
            <td><input class="num-s" name="TEMPSPP" type="text" value="" readonly="readonly" /></td>
          </tr>
        </table>
      </div>
      <!-- End of SPP-Box -->

    </form>
  </body>
</html>