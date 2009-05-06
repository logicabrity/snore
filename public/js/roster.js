// shortcut to form elements
function $_(el) {
  if ( el == null ) {
    return document.getElementById("ROSTER")
  }
    return document.getElementById("ROSTER").elements[el]
}

/* submit the roster */
function app_save() {
  if ( $_("TEAM").value == "" ) {
    alert(warning[13])
  }
  else {
    $_().submit()
  }
}

// reveal a box, as for skills or teampics...
function app_show(ID) {
  // box_visible is a global
  if ( box_visible == true ) {
    alert(warning[9]);
    return false
  }
  else {
    box_visible = true;
    document.getElementById(ID).className = 'element_visible';
    return true
  }
}

function app_show_injury_box(clicked_row) {
  if( player_is_assigned(clicked_row) == true ) {
    if( app_show('inj_box') == true ) {
      var player_number = clicked_row + 1
      var own_injuries = $_("INJURIES[]")[clicked_row].value.split(",")
      $_("TEMP2").value = player_number
      $_("OWN_INJURIES").options.length = 0
      arrayToOptions(own_injuries, 'OWN_INJURIES', 0)
    }
  }
  else {
    alert(warning[2])
  }
}

function app_show_journeymen_box() {
  if ( app_show('jm_box') == true ) {
    for ( i = 0; i < 16; i++ ) {
      if ( player_is_assigned(i) != "journeyman" ) {
        document.getElementById('jm'+i).style.display = "none";
      }
      else {
        document.getElementById('jm'+i).style.display = "block";
      }
    }
  }
}

function app_show_skill_box(clicked_row) {
  // only for real players, and not journeymen
  if( player_is_assigned(clicked_row) == true ) {
    if ( app_show('skill_box') == true ) {
      var position_id = $_("POSITION[]")[clicked_row].value
      var player_number = clicked_row + 1

      $_("TEMP1").value = player_number
      $_("TEMP3").value = 0
      $_("OWN_SKILLS").options.length = 0

      var categories_normal = stats[position_id][8].split("")
      var categories_double = stats[position_id][9].split("")

      // forbidden categories = all - normal - double categories
      var categories_forbidden = new Array("g","a","s","p","m","e")
      eliminateDoubles(categories_forbidden,categories_normal)
      eliminateDoubles(categories_forbidden,categories_double)

      // now that we know which categories (general,strength,...)
      // the user can select, generate the appropriate lists for
      // each type (normal, double, forbidden)
      var skills_owned = $_("SKILLS[]")[clicked_row].value.split(", ")
      var skills_normal = new Array()
      var skills_double = new Array()
      var skills_forbidden = new Array()

      for ( var i in categories_normal ) {
        skills_normal = skills_normal.concat(skills[categories_normal[i]])
      }
      for ( var i in categories_double ) {
        skills_double = skills_double.concat(skills[categories_double[i]])
      }
      for ( var i in categories_forbidden ) {
        skills_forbidden = skills_forbidden.concat(skills[categories_forbidden[i]])
      }

      // remove skills the player already owns from the choices
      eliminateDoubles(skills_normal, skills_owned)
      eliminateDoubles(skills_double, skills_owned)
      eliminateDoubles(skills_forbidden, skills_owned)

      // a player cannot have grab (skills['s'][1]) and frenzy (skills['g'][4])
      // at the same time (LRB5). So if the player owns one the skills, then
      // don't present the other as a choice (more exactly: put it to the
      // forbidden skills)
      if ( isStringInArray(skills['s'][1], skills_owned) ) {
        deleteArrayElement(skills['g'][4], skills_normal)
        deleteArrayElement(skills['g'][4], skills_double)
        skills_forbidden.push(skills['g'][4])
      }

      if ( isStringInArray(skills['g'][4], skills_owned) ) {
        deleteArrayElement(skills['s'][1], skills_normal)
        deleteArrayElement(skills['s'][1], skills_double)
        skills_forbidden.push(skills['s'][1])
      }

      // finally, turn all the lists into something the user can click
      arrayToOptions(skills_owned,      'OWN_SKILLS', 0)
      arrayToOptions(skills_normal,     'REP_NORMAL', 20000)
      arrayToOptions(skills_double,     'REP_DOUBLE', 30000)
      arrayToOptions(skills_forbidden,  'REP_FORBIDDEN', 0)
    }
  }
  else {
    alert(warning[2])
  }
}

/* app_hide a box like skills or injuries */
function app_hide(ID) {
  // box_visible is a global, used again in app_show(ID)
  document.getElementById(ID).className = 'element_hidden';
  box_visible = false;
}

// calculate the value of each extra
// like assistant coaches, rerolls...
function extras_get_total_value(j) {
  var extras_total_value = 0
  for ( var i=16; i<=20; i++ ) {
    var value = parseInt($_("VALUE[]")[i].value)
    if ( isNaN(value) ) { value = 0 }
    extras_total_value += value
  }
  return extras_total_value
}

function extras_set_reroll_value() {
  var count = $_("REROLLS").value
  if ( count > 8 ) {
    count = 8
    $_("REROLLS").value = count
    alert(warning[10])
  }
  $_("VALUE[]")[16].value = count*reroll_cost
  team_set_total_value()
}

function extras_set_fanfactor_value() {
  var count = $_("FANFACTOR").value
  $_("VALUE[]")[17].value = count*10000
  team_set_total_value()
}

function extras_set_coaches_value() {
  var count = $_("COACHES").value
  $_("VALUE[]")[18].value = count*10000
  team_set_total_value()
}

function extras_set_cheerleaders_value() {
  var count = $_("CHEERLEADERS").value
  $_("VALUE[]")[19].value = count*10000
  team_set_total_value()
}

function extras_set_apothecary_value() {
  if ( apothecary == true ) {
    var count = $_("APOTHECARY").value
    if ( count > 1 ) {
      count = 1
      $_("APOTHECARY").value = count
      alert(warning[12])
    }
  }
  else {
    count = 0
    alert(warning[11])
  }
  $_("VALUE[]")[20].value = count*50000
  team_set_total_value()
}

// the objective is to see whether a skill is a normal,
// double or forbidden skill for that player
// it is used when you remove a skill from a player
function skill_get_category(skill, position_id) {
  var categories = new Array("g","a","p","s","m","e")
  for ( var i in categories ) {
    var x = categories[i]
    if ( isStringInArray(skill, skills[x]) == true ) {
      if ( stats[position_id][8].match(x) != null ) {
        return "normal"
      }
      if ( stats[position_id][9].match(x) != null ) {
        return "double"
      }
    }
  }
  return "forbidden"
}

// sum all the values from players and extras
function team_set_total_value() {
  var teamvalue = 0
  for ( k=0; k<=15; k++ ) { // players
    var value = parseInt($_("VALUE[]")[k].value)
    if(isNaN(value) == false && player_is_injured(k) == false ) {
      teamvalue = teamvalue + value
      }
    }
  teamvalue += extras_get_total_value()
  $_("TEAMVALUE").value = teamvalue

  // color teamvalue red if it is > 1.000.000 gp
  if (teamvalue > 1000000) {
    $_("TEAMVALUE").style.color = "red"
  }
  else {
    $_("TEAMVALUE").style.color = "black"
  }
}

// check before too many players of the same position are fielded
function team_is_quantity_problem() {
  for ( var i=0; i<=positions; i++ ) {
    // reset variable that stores the number of players
    // playing that position
    stats[i][7] = 0
  }
  for ( i=0; i<16; i++ ) {
    var position_id = parseInt($_("POSITION[]")[i].value)
    stats[position_id][7]++
    if ( stats[position_id][7] > stats[position_id][6] ) {
      stats[position_id][7]--
      return true
    }
  }
  return false
}

function team_is_journeyman_allowed() {
  if ( team_get_number_of_healthy() < 12 ) {
    return true
  }
  else {
    return false
  }
}

function team_get_number_of_healthy() {
  // first it counts how many healthy players the roster has
  var healthy_players = 0
  for ( i=0; i<16; i++ ) {
    if ( player_is_assigned(i) == true && player_is_injured(i) == false ) {
      healthy_players++
    }
  }
  $_("HEALTHY").value = healthy_players

  // then it will return a boolean to say if journeymen are allowed
  return healthy_players
}

/* add a skill to a player */
function player_add_skill(repertory) {
  var no_problem_with_stats = true;
  switch( repertory ) {
    case "normal":
      var rep = $_("REP_NORMAL")
      break;
    case "double":
      var rep = $_("REP_DOUBLE")
      break;
    case "stat":
      var rep  = $_("REP_STATS")
      var clicked_row = parseInt($_("TEMP1").value - 1)
      var stat = rep.options[rep.selectedIndex].text.substr(1)
      no_problem_with_stats = player_set_stat_increase(clicked_row, stat)
      break;
    case "impossible":
      var rep = $_("REP_FORBIDDEN")
      break;
    default:
      alert(repertory)
      break;
  }

  if ( no_problem_with_stats == true ) {
    selected_name  = rep.options[rep.selectedIndex].text
    selected_value = rep.options[rep.selectedIndex].value

    value_add = parseInt(rep.options[rep.selectedIndex].value)
    $_("TEMP3").value = value_add

    index = $_("OWN_SKILLS").options.length
    $_("OWN_SKILLS").options[index] = new Option(selected_name, selected_value)

    player_set_skillchanges()
  }
}

/* add an injury to a player */
function player_add_injury() {
  var injuries = $_("REP_INJURIES")
  var selected_injury = injuries.options[injuries.selectedIndex].text
  var clicked_row = parseInt($_("TEMP2").value - 1)
  var no_problem = true

  switch ( selected_injury ) {
    case M:
      if ( player_is_missing_next_game() == true ) {
        alert(warning[14])
        no_problem = false
      }
      break;
    case N:
      if ( player_is_missing_next_game() == false ) {
        index = $_("OWN_INJURIES").options.length
        $_("OWN_INJURIES").options[index] = new Option(M)
      }
      break;
    default:
      var stat = selected_injury.substr(1)
      no_problem = player_set_stat_decrease(clicked_row, stat)
      break;
  }

  if ( no_problem == true ) {
    index = $_("OWN_INJURIES").options.length
    $_("OWN_INJURIES").options[index] = new Option(selected_injury)
    player_set_injurychanges()
  }
}

/* remove a skill from a player */
function player_remove_skill() {
  var clicked_row    = parseInt($_("TEMP1").value - 1)
  var position_id    = $_("POSITION[]")[clicked_row].value
  var owned_skills   = $_("OWN_SKILLS")
  var selected_skill = owned_skills.options[owned_skills.selectedIndex].text

  /* check if it's a starting skill */
  if ( isStringInArray(selected_skill, skills[position_id]) == true ) {
    alert(warning[6])
  }
  /* everything else is allowed to be removed */
  else {
    if ( selected_skill.charAt(0) == "+" ) {
      /* our "skill" is in fact a stat improvement */
      var selected_stat = selected_skill.substr(1)
      if ( player_set_stat_decrease(clicked_row, selected_stat) == true ) {
        switch ( selected_stat ) {
          case ST: /* one of the global variables */
            var value_change = -50000
            break;
          case AG:
            var value_change = -40000
            break;
          default:
            /* AV or MA */
            var value_change = -30000
            break;
        }
      }
    }
    else {
      var repertory = skill_get_category(selected_skill, position_id)
      switch( repertory ) {
        case "normal":
          var value_change = -20000
          break;
        case "double":
          var value_change = -30000
          break;
        default:
          var value_change = 0
          break;
      }
    }
    $_("TEMP3").value = value_change
    owned_skills.remove(owned_skills.selectedIndex)
    player_set_skillchanges()
  }
}

/* remove injury */
function player_remove_injury() {
  var owned_injuries  = $_("OWN_INJURIES")
  var selected_injury = owned_injuries.options[owned_injuries.selectedIndex].text
  var clicked_row = parseInt($_("TEMP2").value - 1)

  var no_problem_with_stats = true
  if ( selected_injury.charAt(0) == "-" ) {
    var stat = selected_injury.substr(1)
    no_problem_with_stats = player_set_stat_increase(clicked_row, stat)
  }

  if ( no_problem_with_stats == true ) {
    owned_injuries.remove(owned_injuries.selectedIndex)
    player_set_injurychanges()
  }
}

/* transform a journeyman into a regular player */
function player_legalize(player) {
  $_("POSITION[]")[player].value = 1
  player_legalize_skills = $_("SKILLS[]")[player].value
  player_legalize_skills = player_legalize_skills.replace(skills['e'][9],"")
  player_legalize_skills = player_legalize_skills.replace(/,$/,"")
  player_legalize_skills = player_legalize_skills.replace(/^,/,"")
  player_legalize_skills = player_legalize_skills.replace(/,,/,"")
  $_("SKILLS[]")[player].value = player_legalize_skills
  app_hide('jm_box')
}

/* write changes in skill-box to roster */
function player_set_skillchanges() {
  var clicked_row = parseInt($_("TEMP1").value - 1)
  var options     = $_("OWN_SKILLS").options

  /* it is not possible to directly join the options elements */
  var skills_arr = new Array()
  for ( var i=0; i < options.length; i++ ) {
    if ( options[i].text != "" ) {
      skills_arr.push(options[i].text)
    }
  }
  var skills_str = skills_arr.join(", ")

  var value_old    = parseInt($_("VALUE[]")[clicked_row].value)
  var value_change = parseInt($_("TEMP3").value)

  $_("SKILLS[]")[clicked_row].value = skills_str
  $_("VALUE[]")[clicked_row].value = value_old + value_change

  team_set_total_value()
  app_hide('skill_box')
}

/* write changes in injury-box to roster */
function player_set_injurychanges() {
  var clicked_row = parseInt($_("TEMP2").value - 1)
  var options     = $_("OWN_INJURIES").options
  $_("VALUE[]")[clicked_row].className = 'healthy'

  /* it is not possible to directly join the options elements */
  var injuries_arr = new Array()
  for ( var i=0; i < options.length; i++ ) {
    if ( options[i].text != "" ) {
      injuries_arr.push(options[i].text)
      if ( options[i].text == M ) {
        $_("VALUE[]")[clicked_row].className = 'injured'
      }
    }
  }
  var injuries_str = injuries_arr.join(", ")
  $_("INJURIES[]")[clicked_row].value = injuries_str


  team_set_total_value()
  app_hide('inj_box')
}

/* write stat-change (in skill-box) to roster */
function player_set_stat_increase(clicked_row, stat) {
  var position_id = $_("POSITION[]")[clicked_row].value

  switch ( stat ) {
    case MA:
      stat = "MA"
      var stat_id = 1
      break;
    case ST:
      stat = "ST"
      var stat_id = 2
      break;
    case AG:
      stat = "AG"
      var stat_id = 3
      break;
    case AV:
      stat = "AV"
      var stat_id = 4
      break;
  }

  var stat_now  = $_(stat+"[]")[clicked_row].value
  var stat_diff = stat_now - stats[position_id][stat_id]

  if ( stat_now < 10 && stat_diff < 2 ) {
    $_(stat+"[]")[clicked_row].value++
    return true
  }
  else {
    alert(warning[4])
    return false
  }
}

/* write stat-change (in injury-box) to roster */
function player_set_stat_decrease(clicked_row, stat) {
  var position_id = $_("POSITION[]")[clicked_row].value

  switch ( stat ) {
    case MA:
      stat = "MA"
      var stat_id = 1
      break;
    case ST:
      stat = "ST"
      var stat_id = 2
      break;
    case AG:
      stat = "AG"
      var stat_id = 3
      break;
    case AV:
      stat = "AV"
      var stat_id = 4
      break;
  }

  var stat_now  = $_(stat+"[]")[clicked_row].value
  var stat_diff = stat_now - stats[position_id][stat_id]

  if ( stat_now > 1 && stat_diff > -2 ) {
    $_(stat+"[]")[clicked_row].value--
    return true
  }
  else {
    alert(warning[5])
    return false
  }
}

// go through all SPP-fields of a player
// check them, and do the sum of SPP-points
function player_set_spp(row) {
  if(player_is_assigned(row) != false) {
    var actions = new Array("COMP", "TD", "INT", "CAS", "MVP")
    var actions_points = new Array(1, 3, 2, 2, 5)
    var total_spp_points = 0

    for ( var i in actions ) {
      var count = $_(actions[i]+"[]")[row].value
      if ( isNaN(count) || count<0 ) {
        count = prompt(warning[8])
        if ( isNaN(count) || count<0 ) {
          count = 0
          $_(actions[i]+"[]")[row].value = count
        }
      }
      total_spp_points += count * actions_points[i]
    }
    $_("SPP[]")[row].value = total_spp_points
  } else {
    alert(warning[2])
    $_("COMP[]")[row].value = ""
    $_("TD[]")[row].value   = ""
    $_("INT[]")[row].value  = ""
    $_("CAS[]")[row].value  = ""
    $_("MVP[]")[row].value  = ""
  }
}

function player_set_position(row) {
  var position_id = $_("POSITION[]")[row].value
  if ( team_is_quantity_problem() == true ) {
    alert(warning[0] + " " + stats[position_id][6] + " '" + stats[position_id][0] + "'.")
    position_id = 0
  }

  if ( position_id == positions && !(team_is_journeyman_allowed()) ) {
    healthy_players = $_("HEALTHY") - 1
    alert(warning[1] + " " + healthy_players + ".")
    position_id = 0
  }
  $_("NAME[]")[row].value      = ""
  $_("MA[]")[row].value        = stats[position_id][1]
  $_("ST[]")[row].value        = stats[position_id][2]
  $_("AG[]")[row].value        = stats[position_id][3]
  $_("AV[]")[row].value        = stats[position_id][4]
  $_("VALUE[]")[row].className = 'healthy'
  $_("VALUE[]")[row].value     = stats[position_id][5]
  $_("POSITION[]")[row].value  = position_id
  $_("SKILLS[]")[row].value    = skills[position_id].join(", ")
  $_("INJURIES[]")[row].value  = ""
  $_("COMP[]")[row].value      = ""
  $_("TD[]")[row].value        = ""
  $_("INT[]")[row].value       = ""
  $_("CAS[]")[row].value       = ""
  $_("MVP[]")[row].value       = ""
  $_("SPP[]")[row].value       = ""
  team_set_total_value()
}

function player_set_name(row) {
  // don't allow to name non-existent players
  if( player_is_assigned(row) == false ) {
    alert(warning[2])
    $_("NAME[]")[row].value = ""
  }
}

function player_is_injured(row) {
  if ( $_("VALUE[]")[row].className == "injured" ) {
    return true
  }
  return false
}

function player_is_assigned(row) {
  var position_id = parseInt($_("POSITION[]")[row].value)
  if ( position_id == 0 ) {
    return false
  }
  else {
    if ( position_id == positions ) {
      // because the journeymen are the last position, their id
      // is equally the number of positions there is in total
      // ("empty" position is counted as 0, first as 1 etc...)
      return "journeyman" 
      // roster.js app_show_journeymen_box token to recognize a journeyman
    }
    return true
  }
}

function player_is_missing_next_game() {
  var own_injuries = $_('OWN_INJURIES')
  for ( var i=0; i<own_injuries.options.length; i++ ) {
    if ( own_injuries.options[i].text == M ) {
      return true
    }
  }
  return false
}
