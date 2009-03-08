<!-- Injuries -->

<div id="inj_box" class="element_hidden">

<button type="button" class="box_control" title="{$t.button_cancel}" onclick="app_hide('inj_box')">
	<img src="data/cancel.gif" alt="{$t.button_cancel}" />
</button>

<h2 class="popup">
	{$t.injuries} - {$t.player}&nbsp;<input name="TEMP2" type="text" readonly="readonly" size="2" />
</h2>

<select class="fix" name="OWN_INJURIES"><option></option></select>
<button type="button" title="{$t.button_remove}" onclick="player_remove_injury()">
	<img src="data/remove_green.gif" alt="{$t.button_remove}" />
</button>

<br />

<select class="fix" name="REP_INJURIES">
	<option>{$t.injuries_m}</option>
	<option>{$t.injuries_n}</option>
	<option>-{$t.stats_ma}</option>
	<option>-{$t.stats_st}</option>
	<option>-{$t.stats_ag}</option>
	<option>-{$t.stats_av}</option>
</select>
<button type="button" title="{$t.button_add}" onclick="player_add_injury()">
	<img src="data/add_red.gif" alt="{$t.button_add}" />
</button>

</div>
