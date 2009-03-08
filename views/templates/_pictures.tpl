<!-- Pictures -->

<div id="pic_box" class="element_hidden">

<button type="button" class="box_control" title="{$t.button_validate}" onclick="app_hide('pic_box')">
	<img src="data/check.gif" alt="{$t.button_validate}" />
</button>

<h2 class="popup">{$t.teampics_label}</h2>
<p style="text-align: justify;">{$t.teampics_text}</p>

<table>

<tr>
<td class="label">{$t.team}</td>
<td><input name="TEAMLOGO" type="text" value="{$race->emblem}" /></td>
</tr>

{section name=pictures start=0 loop=16 step=1}
{assign var='number'  value=`$smarty.section.pictures.index+1`}
<tr>
	<td class="label">{$number}</td>
	<td><input name="DISPLAY[]" type="text" value="" /></td>
</tr>
{/section}

</table>


</div>
