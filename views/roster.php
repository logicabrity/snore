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
    skills['g'] = new Array("<?php echo implode('","', $skills['General']); ?>")
    skills['p'] = new Array("<?php echo implode('","', $skills['Passing']); ?>")
    skills['a'] = new Array("<?php echo implode('","', $skills['Agility']); ?>")
    skills['s'] = new Array("<?php echo implode('","', $skills['Strength']); ?>")
    skills['m'] = new Array("<?php echo implode('","', $skills['Mutation']); ?>")
    skills['e'] = new Array("<?php echo implode('","', $skills['Extraordinary']); ?>")

    warning = new Array()
<?php foreach ( $t['js_warnings'] as $i => $w ): ?>
    warning[<?php echo $i; ?>] = "<?php echo $w; ?>"
<?php endforeach; ?>

    var M  = "<?php echo $t['injuries_m']; ?>"
    var N  = "<?php echo $t['injuries_n']; ?>"
    var MA = "<?php echo $t['stats_ma']; ?>"
    var ST = "<?php echo $t['stats_st']; ?>"
    var AG = "<?php echo $t['stats_ag']; ?>"
    var AV = "<?php echo $t['stats_av']; ?>"

    stats     = new Array()
    stats[0]  = new Array("","","","","","",99,16)
    skills[0] = new Array()
<?php $i=0; foreach ( $race->positions->position as $p ): $i++; ?>
    stats[<?php echo $i; ?>]  = new Array(<?php echo "\"{$p->title}\",{$p->ma},{$p->st},{$p->ag},{$p->av},{$p->cost},{$p->qty},0,\"{$p->normal}\",\"{$p->double}\",\"{$p->display}\""; ?>)
    skills[<?php echo $i; ?>] = new Array("<?php echo implode('","',$p->xpath("skills/skill")); ?>")
<?php endforeach; ?>

    var positions   = <?php echo count($race->positions->position)."\n"; ?>
    var apothecary  = new Boolean(<?php echo $race->apothecary; ?>)
    var reroll_cost = <?php echo $race->reroll->cost; ?> 
    var box_visible = new Boolean(false)
  </script>
</head>

<body onload="team_get_number_of_healthy();team_set_total_value();">
  <h1>Tow Bowl Tactics Teamroster</h1>

  <form id="ROSTER" action="index.php" method="post" enctype="multipart/form-data">
    <div>
      <input type="hidden" name="RACE_ID" value="<?php echo $race['id']; ?>" />
    </div>

    <!-- Start of Roster -->

    <table class="big">

      <thead>
        <tr>
          <td>#</td>
          <td><?php echo $t['player_name']; ?></td>
          <td><?php echo $t['player_position']; ?></td>
          <td><?php echo $t['stats_ma']; ?></td>
          <td><?php echo $t['stats_st']; ?></td>
          <td><?php echo $t['stats_ag']; ?></td>
          <td><?php echo $t['stats_av']; ?></td>
          <td><?php echo $t['skills']; ?></td>
          <td><?php echo $t['injuries']; ?></td>
          <td><?php echo $t['spp_comp']; ?></td>
          <td><?php echo $t['spp_td']; ?></td>
          <td><?php echo $t['spp_int']; ?></td>
          <td><?php echo $t['spp_cas']; ?></td>
          <td><?php echo $t['spp_mvp']; ?></td>
          <td><?php echo $t['spp']; ?></td>
          <td><?php echo $t['value']; ?></td>
        </tr>
      </thead>

      <!-- Start of Players -->
<?php for ( $i=0; $i<16; $i++ ): $n = $i+1; if (isset($p)): unset($p); endif; ?>
<?php if ( isset($loadedTeam['player'][$i]) ): $p = $loadedTeam['player'][$i]; endif; ?>

      <tr><!-- The row for player number <?php echo $n; ?>. -->
        <td class="label"><?php echo $n; ?></td>
        <td><input name="NAME[]" onchange="player_set_name(<?php echo $i; ?>)" type="text" value="<?php if ( isset($p['name']) ): echo $p['name']; endif; ?>" /></td>
        <td>
          <select class="position" name="POSITION[]" onchange="player_set_position(<?php echo $i; ?>);team_get_number_of_healthy()">
            <option value="0"<?php if ( !isset($p['position']) ): echo ' selected="selected"'; endif; ?>></option>
<?php $j=1; foreach ( $race->positions->position as $pos ): ?>
            <option value="<?php echo $j; ?>"<?php if ( isset($p['position']) && $j==$p['position'] ): echo ' selected="selected"'; endif; ?>><?php echo $pos->title; $j++; ?></option>
<?php endforeach; ?>
          </select>
        </td>
        <td><input class="stats" name="MA[]" type="text" readonly="readonly" value="<?php if (isset($p['ma'])) echo $p['ma']; ?>" /></td>
        <td><input class="stats" name="ST[]" type="text" readonly="readonly" value="<?php if (isset($p['st'])) echo $p['st']; ?>" /></td>
        <td><input class="stats" name="AG[]" type="text" readonly="readonly" value="<?php if (isset($p['ag'])) echo $p['ag']; ?>" /></td>
        <td><input class="stats" name="AV[]" type="text" readonly="readonly" value="<?php if (isset($p['av'])) echo $p['av']; ?>" /></td>
        <td class="button" onclick="app_show_skill_box(<?php echo $i; ?>)">
          <textarea rows="2" cols="30" class="skills" name="SKILLS[]" readonly="readonly"><?php if (isset($p['skill'])): echo implode(',', $p['skill']); endif; ?></textarea>
        </td>
        <td class="button" onclick="app_show_injury_box(<?php echo $i; ?>)">
          <textarea rows="2" cols="30" class="injuries" name="INJURIES[]" readonly="readonly"><?php if (isset($p['inj'])): echo implode(',', $p['inj']); endif; ?></textarea>
        </td>
        <td><input class="spp" name="COMP[]" onchange="player_set_spp(<?php echo $i; ?>)" type="text" value="<?php if (isset($p['com'])) echo $p['com']; ?>" /></td>
        <td><input class="spp" name="TD[]" onchange="player_set_spp(<?php echo $i; ?>)" type="text" value="<?php if (isset($p['td'])) echo $p['td']; ?>" /></td>
        <td><input class="spp" name="INT[]" onchange="player_set_spp(<?php echo $i; ?>)" type="text" value="<?php if (isset($p['int'])) echo $p['int']; ?>" /></td>
        <td><input class="spp" name="CAS[]" onchange="player_set_spp(<?php echo $i; ?>)" type="text" value="<?php if (isset($p['cas'])) echo $p['cas']; ?>" /></td>
        <td><input class="spp" name="MVP[]" onchange="player_set_spp(<?php echo $i; ?>)" type="text" value="<?php if (isset($p['mvp'])) echo $p['mvp']; ?>" /></td>
        <td><input class="spp" name="SPP[]" type="text" value="<?php if (isset($p['spp'])): echo $p['spp']; endif; ?>" readonly="readonly" /></td>
<?php if (isset($p['inj']) && in_array($t['injuries_m'], $p['inj'])): $health_status="injured"; else: $health_status="healthy"; endif; ?>
        <td><input class="<?php echo $health_status; ?>" name="VALUE[]" type="text" value="<?php if (isset($p['cost'])): echo $p['cost']; endif; ?>" readonly="readonly" /></td>
      </tr>
<?php endfor; ?>

      <!-- End of Players -->

      <tr class="separator">
       <td rowspan="6" colspan="2">
        <p>
         <a href="javascript:app_save()"><?php echo $t['save']; ?></a>
        </p>
       </td>
       <td rowspan="6" colspan="1"></td>
       <td class="label" colspan="4" rowspan="2"><?php echo $t['team']; ?></td>
       <td rowspan="2"><input name="TEAM" type="text" value="<?php echo $loadedTeam['name']; ?>" /></td>
       <td class="label" colspan="3"><?php echo $t['rerolls']; ?></td>
       <td colspan="1"><input class="extras" name="REROLLS" onchange="extras_set_reroll_value()" type="text" value="<?php if (isset($loadedTeam['reroll'])) echo $loadedTeam['reroll']; ?>" /></td>
       <td colspan="3" class="label">x <?php echo $race->reroll->cost; ?></td>
       <td><input  class="value" name="VALUE[]" type="text" readonly="readonly" value="<?php if (isset($loadedTeam['reroll'])) echo $loadedTeam['reroll']*$race->reroll->cost; ?>" /></td>
      </tr>

      <tr>
       <td class="label" colspan="3"><?php echo $t['fanfactor']; ?></td>
       <td colspan="1"><input class="extras" name="FANFACTOR" onchange="extras_set_fanfactor_value()" type="text" value="<?php if (isset($loadedTeam['fanfactor'])) echo $loadedTeam['fanfactor']; ?>" /></td>
       <td colspan="3" class="label">x 10000</td>
       <td><input  class="value" name="VALUE[]" type="text" readonly="readonly" value="<?php if (isset($loadedTeam['fanfactor'])) echo $loadedTeam['fanfactor']*10000; ?>" /></td>
      </tr>

      <tr>
       <td class="label" colspan="4"><?php echo $t['player']; ?><a class="blue" href="javascript:app_show_journeymen_box()">(<?php echo $t['journeymen_manage']; ?>)</a></td>
       <td colspan="1"><input name="HEALTHY" type="text" readonly="readonly" /></td>
       <td colspan="3" class="label"><?php echo $t['assistants']; ?></td>
       <td colspan="1"><input class="extras" name="COACHES" onchange="extras_set_coaches_value()" type="text" value="<?php if (isset($loadedTeam['assistant'])) echo $loadedTeam['assistant']; ?>" /></td>
       <td colspan="3" class="label">x 10000</td>
       <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="<?php if (isset($loadedTeam['assistant'])) echo $loadedTeam['assistant']*10000; ?>" /></td>
      </tr>

      <tr>
       <td colspan="4" class="label"><?php echo $t['race']; ?></td>
       <td><input name="RACE" type="text" value="<?php echo $race['name']; ?>" readonly="readonly" /></td>
       <td colspan="3" class="label"><?php echo $t['cheerleaders']; ?></td>
       <td colspan="1"><input class="extras" name="CHEERLEADERS" onchange="extras_set_cheerleaders_value()" type="text" value="<?php if (isset($loadedTeam['cheerleader'])) echo $loadedTeam['cheerleader']; ?>" /></td>
       <td colspan="3" class="label">x 10000</td>
       <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="<?php if (isset($loadedTeam['cheerleader'])) echo $loadedTeam['cheerleader']*10000; ?>" /></td>
      </tr>

      <tr>
       <td colspan="4" class="label"><?php echo $t['treasury']; ?></td>
       <td><input name="TREASURY" type="text" value="<?php if (isset($loadedTeam['treasury'])) echo $loadedTeam['treasury']; ?>" /></td>
       <td colspan="3" class="label"><?php echo $t['apothecary']; ?></td>
       <td colspan="1"><input class="extras" name="APOTHECARY" onchange="extras_set_apothecary_value()" type="text" value="<?php if (isset($loadedTeam['apothecary'])) echo $loadedTeam['apothecary']; ?>" /></td>
       <td colspan="3" class="label">x 50000</td>
       <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="<?php if (isset($loadedTeam['apothecary'])) echo $loadedTeam['apothecary']*50000; ?>" /></td>
      </tr>

      <tr>
       <td colspan="4" class="label"><?php echo $t['headcoach']; ?></td>
       <td><input name="HEADCOACH" type="text" value="<?php if (isset($loadedTeam['coach'])) echo $loadedTeam['coach']; ?>" /></td>
       <td colspan="7" class="label"><?php echo $t['teamvalue']; ?></td>
       <td><input class="value" name="TEAMVALUE" type="text" readonly="readonly" value="" /></td>
      </tr>

    </table>

    <!-- End of Roster -->

    <!-- Start of Boxes -->

    <!-- Start of Skill-Box -->

    <div id="skill_box" class="element_hidden">
      <button type="button" class="box_control" title="<?php echo $t['button_cancel']; ?>" onclick="app_hide('skill_box')">
        <img src="public/pics/cancel.gif" alt="<?php echo $t['button_cancel']; ?>" />
      </button>

      <h2 class="popup">
        <?php echo $t['skills']; ?> - <?php $t['player']; ?>&nbsp;<input name="TEMP1" type="text" readonly="readonly" size="2" />
      </h2>

      <h3><?php echo $t['skills_own']; ?></h3>

      <select class="fix" size="6" name="OWN_SKILLS">
        <option></option>
      </select>
      <button type="button" title="<?php echo $t['button_remove']; ?>" onclick="player_remove_skill()">
       <img src="public/pics/remove_red.gif" alt="<?php echo $t['button_remove']; ?>" />
      </button>
      
      <h3><?php echo $t['button_add']; ?>:</h3>

      <p><?php echo $t['skills_normal']; ?></p>
      <select class="fix" name="REP_NORMAL"><option></option></select>
      <button type="button" title="<?php echo $t['button_add']; ?>" onclick="player_add_skill('normal')">
        <img src="public/pics/add_green.gif" alt="<?php echo $t['button_add']; ?>" />
      </button>

      <p><?php echo $t['skills_double']; ?></p>
      <select class="fix" name="REP_DOUBLE"><option></option></select>
      <button type="button" title="<?php echo $t['button_add']; ?>" onclick="player_add_skill('double')">
        <img src="public/pics/add_green.gif" alt="<?php echo $t['button_add']; ?>" />
      </button>

      <p><?php echo $t['stats']; ?></p>
      <select class="fix" name="REP_STATS">
        <option value="30000">+<?php echo $t['stats_ma']; ?></option>
        <option value="50000">+<?php echo $t['stats_st']; ?></option>
        <option value="40000">+<?php echo $t['stats_ag']; ?></option>
        <option value="30000">+<?php echo $t['stats_av']; ?></option>
      </select>
      <button type="button" title="<?php echo $t['button_add']; ?>" onclick="player_add_skill('stat')">
        <img src="public/pics/add_green.gif" alt="<?php echo $t['button_add']; ?>" />
      </button>

      <p><?php echo $t['skills_forbidden']; ?></p>
      <select class="fix" name="REP_FORBIDDEN">
        <option></option>
      </select>
      <button type="button" title="<?php echo $t['button_add']; ?>" onclick="player_add_skill('impossible')">
        <img src="public/pics/add_red.gif" alt="<?php echo $t['button_add']; ?>" />
      </button>

      <input style="display: none;" name="TEMP3" type="text" readonly="readonly" value="0" />
    </div>

    <!-- End of Skill-Box -->

    <!-- Start of Injury-Box -->

    <div id="inj_box" class="element_hidden">
      <button type="button" class="box_control" title="<?php echo $t['button_cancel']; ?>" onclick="app_hide('inj_box')">
        <img src="public/pics/cancel.gif" alt="<?php echo $t['button_cancel']; ?>" />
      </button>

      <h2 class="popup">
        <?php echo $t['injuries']; ?> - <?php echo $t['player']; ?>&nbsp;<input name="TEMP2" type="text" readonly="readonly" size="2" />
      </h2>

      <select class="fix" name="OWN_INJURIES">
        <option></option>
      </select>
      <button type="button" title="<?php echo $t['button_remove']; ?>" onclick="player_remove_injury()">
        <img src="public/pics/remove_green.gif" alt="<?php echo $t['button_remove']; ?>" />
      </button>
      <br />
      <select class="fix" name="REP_INJURIES">
        <option><?php echo $t['injuries_m']; ?></option>
        <option><?php echo $t['injuries_n']; ?></option>
        <option>-<?php echo $t['stats_ma']; ?></option>
        <option>-<?php echo $t['stats_st']; ?></option>
        <option>-<?php echo $t['stats_ag']; ?></option>
        <option>-<?php echo $t['stats_av']; ?></option>
      </select>
      <button type="button" title="<?php echo $t['button_add']; ?>" onclick="player_add_injury()">
        <img src="public/pics/add_red.gif" alt="<?php echo $t['button_add']; ?>" />
      </button>

    </div>

    <!-- End of Injury-Box -->

    <!-- Start of Journeymen-Box -->

    <div id="jm_box" class="element_hidden">
      <button type="button" class="box_control" title="<?php echo $t['button_cancel']; ?>" onclick="app_hide('jm_box')">
        <img src="public/pics/cancel.gif" alt="<?php echo $t['button_cancel']; ?>" />
      </button>

      <h2 class="popup"><?php echo $t['journeymen']; ?></h2>
      <p style="text-align: justify; "><?php echo $t['journeymen_text']; ?></p>

      <table>
        <tr>
<?php for ( $ji=0; $ji<16; $ji++ ): ?>
          <td class= "button"><a id="jm<?php echo $ji; ?>" class="neutral" href="javascript:player_legalize(<?php echo $ji; ?>)"><?php echo $ji+1; ?></a></td>
<?php if ($ji==7): // end the first row after half the players ?>
        </tr>
        <tr>
<?php endif; ?>
<?php endfor; ?>
        </tr>
      </table>
    </div>

    <!-- End of Journeymen-Box -->
    
    <!-- Start of Pictures-Box -->

    <div id="pic_box" class="element_hidden">
      <button type="button" class="box_control" title="<?php echo $t['button_validate']; ?>" onclick="app_hide('pic_box')">
        <img src="public/pics/check.gif" alt="<?php echo $t['button_validate']; ?>" />
      </button>

      <h2 class="popup"><?php echo $t['teampics_label']; ?></h2>
      <p style="text-align: justify;"><?php echo $t['teampics_text']; ?></p>

      <table>
        <tr>
          <td class="label"><?php echo $t['team']; ?></td>
          <td><input name="TEAMLOGO" type="text" value="<?php echo $race->emblem; ?>" /></td>
        </tr>
<?php for ( $ji=0; $ji<16; $ji++ ): ?>
        <tr><td class="label"><?php echo $ji; ?></td><td><input name="DISPLAY[]" type="text" value="" /></td></tr>
<?php endfor; ?>
      </table>
      <button type="button" class="box_control" title="<?php echo $t['button_validate']; ?>" onclick="app_hide('pic_box')" />
    </div>

    <!-- End of Pictures-Box -->

    <!-- End of Boxes -->

  </form>

</body>
</html>