<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>TBT Snore</title>
<meta http-equiv="content-type" content="application/xhtml+xml;charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="views/styles/roster.css" title="Default" />
<link rel="stylesheet" type="text/css" media="print" href="views/styles/print.css" title="Default" />
<script type="text/javascript" src="app/js/functions.js"></script>
<script type="text/javascript" src="app/js/roster.js"></script>
{include file='_javascript.tpl'}
</head>

<body onload="team_get_number_of_healthy();team_set_total_value();">
<h1>Tow Bowl Tactics Teamroster</h1>

<form id="ROSTER" name="ROSTER" action="index.php" method="post" enctype="multipart/form-data">
<div><input type="hidden" name="RACE_ID" value="{$race.id}" /></div>

<table class="big">

<thead>
<td>#</td>
<td>{$t.player_name}</td>
<td>{$t.player_position}</td>
<td>{$t.stats_ma}</td>
<td>{$t.stats_st}</td>
<td>{$t.stats_ag}</td>
<td>{$t.stats_av}</td>
<td>{$t.skills}</td>
<td>{$t.injuries}</td>
<td>{$t.spp_comp}</td>
<td>{$t.spp_td}</td>
<td>{$t.spp_int}</td>
<td>{$t.spp_cas}</td>
<td>{$t.spp_mvp}</td>
<td>{$t.spp}</td>
<td>{$t.value}</td>
</thead>

{include file='_players.tpl'}

<tr class="separator">
 <td rowspan="6" colspan="2">
  <p>
   <!-- javascript: have to use the return value of app_show() -->
   <a href="javascript:var foo = app_show('background_box')">{$t.background_label}</a><br />
   <a href="javascript:app_save()">{$t.save}</a>
  </p>
 </td>
 <td rowspan="6" colspan="1" style="text-align: center">
  <p><img alt="a colorful picture" style="width:104px;height:140px;" src="data/logos/{$race->emblem}" /></p>
 </td>
 <td class="label" colspan="4" rowspan="2">{$t.team}</td>
 <td rowspan="2"><input name="TEAM" type="text" value="{$team.name|default:''}" /></td>
 <td class="label" colspan="3">{$t.rerolls}</td>
 <td colspan="1"><input class="extras" name="REROLLS" onchange="extras_set_reroll_value()" type="text" value="{$team.reroll|default:''}" /></td>
 <td colspan="3" class="label">x {$race->reroll->cost}</td>
 <td><input  class="value" name="VALUE[]" type="text" readonly="readonly" value="{$team.reroll*$race->reroll->cost|default:''}" /></td>
</tr>

<tr>
 <td class="label" colspan="3">{$t.fanfactor}</td>
 <td colspan="1"><input class="extras" name="FANFACTOR" onchange="extras_set_fanfactor_value()" type="text" value="{$team.fanfactor|default:''}" /></td>
 <td colspan="3" class="label">x 10000</td>
 <td><input  class="value" name="VALUE[]" type="text" readonly="readonly" value="{$team.fanfactor*10000|default:''}" /></td>
</tr>

<tr>
 <td class="label" colspan="4">{$t.player} <a class="blue" href="javascript:app_show_journeymen_box()">({$t.journeymen_manage})</a></td>
 <td colspan="1"><input name="HEALTHY" type="text" readonly="readonly" /></td>
 <td colspan="3" class="label">{$t.assistants}</td>
 <td colspan="1"><input class="extras" name="COACHES" onchange="extras_set_coaches_value()" type="text" value="{$team.assistant|default:''}" /></td>
 <td colspan="3" class="label">x 10000</td>
 <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="{$team.assistant*10000|default:''}" /></td>
</tr>

<tr>
 <td colspan="4" class="label">{$t.race}</td>
 <td><input name="RACE" type="text" value="{$race.name}" readonly="readonly" /></td>
 <td colspan="3" class="label">{$t.cheerleaders}</td>
 <td colspan="1"><input class="extras" name="CHEERLEADERS" onchange="extras_set_cheerleaders_value()" type="text" value="{$team.cheerleader|default:''}" /></td>
 <td colspan="3" class="label">x 10000</td>
 <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="{$team.cheerleaders*10000|default:''}" /></td>
</tr>

<tr>
 <td colspan="4" class="label">{$t.treasury}</td>
 <td><input name="TREASURY" type="text" value="{$team.treasury|default:''}" /></td>
 <td colspan="3" class="label">{$t.apothecary}</td>
 <td colspan="1"><input class="extras" name="APOTHECARY" onchange="extras_set_apothecary_value()" type="text" value="{$team.apothecary|default:'0'}" /></td>
 <td colspan="3" class="label">x 50000</td>
 <td><input class="value" name="VALUE[]" type="text" readonly="readonly" value="{$team.apothecary*50000|default:''}" /></td>
</tr>

<tr>
 <td colspan="4" class="label">{$t.headcoach}</td>
 <td><input name="HEADCOACH" type="text" value="{$team.coach|default:''}" /></td>
 <td colspan="7" class="label">{$t.teamvalue}</td>
 <td><input class="value" name="TEAMVALUE" type="text" readonly="readonly" value="" /></td>
</tr>

</table>

<!-- End of table -->

<!-- Boxes -->
{include file='_skills.tpl'}
{include file='_injuries.tpl'}
{include file='_journeymen.tpl'}
{include file='_background.tpl'}
{include file='_pictures.tpl'}
<!-- End of boxes -->

</form>

</body>
</html>
