<!-- Journeymen -->

<div id="jm_box" class="element_hidden">

<button type="button" class="box_control" title="{$t.button_cancel}" onclick="app_hide('jm_box')">
	<img src="data/cancel.gif" alt="{$t.button_cancel}" />
</button>

<h2 class="popup">{$t.journeymen}</h2>
<p style="text-align: justify; ">{$t.journeymen_text}</p>

<table>
<tr>
{section name=journeymen start=0 loop=16 step=1}
{assign var='index' value=$smarty.section.journeymen.index}
{assign var='number' value=`$index+1`}
<td class= "button"><a id="jm{$index}" class="neutral" href="javascript:player_legalize({$index})">{$number}</a></td>
{if $index==7}
</tr>
<tr>
{/if}
{/section}
</tr>
</table>

</div>
