<!-- Background -->

<div id="background_box" class="element_hidden">

<button type="button" class="box_control" title="{$t.button_validate}" onclick="app_hide('background_box')">
	<img src="data/check.gif" alt="{$t.button_validate}" />
</button>

<h2 class="popup">{$t.background_text}</h2>

<p>
  <textarea class="background" name="BACKGROUND" cols="60" rows="7">{$team.background|default:$race->background|nl2br}</textarea>
</p>

</div>
