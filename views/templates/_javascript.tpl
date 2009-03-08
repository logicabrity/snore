<script type="text/javascript">

skills      = new Array()
skills['g'] = new Array({foreach from=$skills.General item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})
skills['p'] = new Array({foreach from=$skills.Passing item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})
skills['a'] = new Array({foreach from=$skills.Agility item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})
skills['s'] = new Array({foreach from=$skills.Strength item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})
skills['m'] = new Array({foreach from=$skills.Mutation item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})
skills['e'] = new Array({foreach from=$skills.Extraordinary item=v name=skills}"{$v}"{if $smarty.foreach.skills.last == false},{/if}{/foreach})

warning = new Array()
{counter start=-1 print=false}{foreach from=$t.js_warnings item=w}
warning[{counter}] = "{$w}"
{/foreach}

var M  = "{$t.injuries_m}"
var N  = "{$t.injuries_n}"
var MA = "{$t.stats_ma}"
var ST = "{$t.stats_st}"
var AG = "{$t.stats_ag}"
var AV = "{$t.stats_av}"

stats = new Array
stats[{counter start=0}] = new Array("","","","","","",99,16)
{foreach from=$race->positions->position item=p}
stats[{counter}] = new Array("{$p->title}",{$p->ma},{$p->st},{$p->ag},{$p->av},{$p->cost},{$p->qty},0,"{$p->normal}","{$p->double}","{$p->display}")
{/foreach}

skills[{counter start=0}] = new Array()
{foreach from=$race->positions->position item=p}
skills[{counter}] = new Array({foreach from=$p->skills->skill item=s name=sk}"{$s}"{if $smarty.foreach.sk.last == false},{/if}{/foreach})
{/foreach}

var positions = {$race->positions->position|@count}
var apothecary = new Boolean({$race->apothecary})
var reroll_cost = {$race->reroll->cost}
var box_visible = new Boolean(false)

</script>
