{section name=players start=0 loop=16 step=1}
{assign var='index'  value=$smarty.section.players.index}
{assign var='number' value=`$index+1`}
{assign var='p' value=$team.player[$index]|default:''}

<!-- The row for player number {$number}. -->

<tr>
<td class="label">{$number}</td>
<td><input name="NAME[]" onchange="player_set_name({$index})" type="text" value="{if isset($p.name)}{$p.name|default:''}{/if}" /></td>

<td>
<select class="position" name="POSITION[]" onchange="player_set_position({$index});team_get_number_of_healthy()">
<option value="0"{if !isset($p.position)} selected="selected"{/if}></option>
{foreach name="position" from=$race->positions->position item=pos}
<option value="{$smarty.foreach.position.iteration}"{if isset($p.position) && $smarty.foreach.position.iteration eq $p.position} selected="selected"{/if}>{$pos->title}</option>
{/foreach}
</select>
</td>

<td><input class="stats" name="MA[]" type="text" readonly="readonly" value="{if isset($p.ma)}{$p.ma|default:''}{/if}" /></td>
<td><input class="stats" name="ST[]" type="text" readonly="readonly" value="{if isset($p.st)}{$p.st|default:''}{/if}" /></td>
<td><input class="stats" name="AG[]" type="text" readonly="readonly" value="{if isset($p.ag)}{$p.ag|default:''}{/if}" /></td>
<td><input class="stats" name="AV[]" type="text" readonly="readonly" value="{if isset($p.av)}{$p.av|default:''}{/if}" /></td>

<td class="button" onclick="app_show_skill_box({$index})">
<textarea class="skills" name="SKILLS[]" readonly="readonly">{if isset($p.skill)}{glue var=$p.skill|default:''}{/if}</textarea>
</td>

<td class="button" onclick="app_show_injury_box({$index})">
<textarea class="injuries" name="INJURIES[]" readonly="readonly">{if isset($p.inj)}{glue var=$p.inj|default:''}{/if}</textarea>
</td>

<td><input class="spp" name="COMP[]" onchange="player_set_spp({$index})" type="text" value="{if isset($p.com)}{$p.com|default:''}{/if}" /></td>
<td><input class="spp" name="TD[]" onchange="player_set_spp({$index})" type="text" value="{if isset($p.td)}{$p.td|default:''}{/if}" /></td>
<td><input class="spp" name="INT[]" onchange="player_set_spp({$index})" type="text" value="{if isset($p.int)}{$p.int|default:''}{/if}" /></td>
<td><input class="spp" name="CAS[]" onchange="player_set_spp({$index})" type="text" value="{if isset($p.cas)}{$p.cas|default:''}{/if}" /></td>
<td><input class="spp" name="MVP[]" onchange="player_set_spp({$index})" type="text" value="{if isset($p.mvp)}{$p.mvp|default:''}{/if}" /></td>
<td><input class="spp" name="SPP[]" type="text" value="{if isset($p.spp)}{$p.spp|default:''}{/if}" readonly="readonly" /></td>
{assign var="health_status" value="healthy"}{if isset($p.inj)}{foreach from=$p.inj item=inj}{if $inj == $t.injuries_m}{assign var="health_status" value="injured"}{/if}{/foreach}{/if}
<td><input class="{$health_status}" name="VALUE[]" type="text" value="{if isset($p.cost)}{$p.cost|default:''}{/if}" readonly="readonly" /></td>

</tr>
{/section}

<!-- End of players. -->
