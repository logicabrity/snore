<!-- Skills -->

<div id="skill_box" class="element_hidden">

<button type="button" class="box_control" title="{$t.button_cancel}" onclick="app_hide('skill_box')">
	<img src="data/cancel.gif" alt="{$t.button_cancel}" />
</button>

<h2 class="popup">
	{$t.skills} - {$t.player}&nbsp;<input name="TEMP1" type="text" readonly="readonly" size="2" />
</h2>

<input style="display: none;" name="TEMP3" type="text" readonly="readonly" value="0" />

<h3>{$t.skills_own}</h3>

<select class="fix" size="6" name="OWN_SKILLS"><option></option></select>
<button type="button" title="{$t.button_remove}" onclick="player_remove_skill()">
 <img src="data/remove_red.gif" alt="{$t.button_remove}" />
</button>

<h3>{$t.button_add}:</h3>

<p>{$t.skills_normal}</p>
<select class="fix" name="REP_NORMAL"><option></option></select>
<button type="button" title="{$t.button_add}" onclick="player_add_skill('normal')">
  <img src="data/add_green.gif" alt="{$t.button_add}" />
</button>

<p>{$t.skills_double}</p>
<select class="fix" name="REP_DOUBLE"><option></option></select>
<button type="button" title="{$t.button_add}" onclick="player_add_skill('double')">
  <img src="data/add_green.gif" alt="{$t.button_add}" />
</button>

<p>{$t.stats}</p>
<select class="fix" name="REP_STATS">
  <option value="30000">+{$t.stats_ma}</option>
	<option value="50000">+{$t.stats_st}</option>
	<option value="40000">+{$t.stats_ag}</option>
	<option value="30000">+{$t.stats_av}</option>
</select>
<button type="button" title="{$t.button_add}" onclick="player_add_skill('stat')">
  <img src="data/add_green.gif" alt="{$t.button_add}" />
</button>

<p>{$t.skills_forbidden}</p>
<select class="fix" name="REP_FORBIDDEN">
  <option></option>
</select>
<button type="button" title="{$t.button_add}" onclick="player_add_skill('impossible')">
	<img src="data/add_red.gif" alt="{$t.button_add}" />
</button>

</div>
