<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/********************************************************/
/* NukeProject(tm)                                      */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network         */
/********************************************************/

if(!defined('NSNPJ_PUBLIC')) { die("Illegal Access Detected!!!"); }
$project_id = intval($project_id);
$project = pjproject_info($project_id);
if($project['allowreports'] > 0) {
  $pagetitle = ": "._PJ_TITLE." ".$pj_config['version_number'].": "._PJ_SUBMITAREPORT;
  include_once(NUKE_BASE_DIR.'header.php');
  title(_PJ_TITLE." ".$pj_config['version_number'].": "._PJ_SUBMITAREPORT);
  OpenTable();
  echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
  echo "<form action='modules.php?name=$module_name' method='post'>\n";
  echo "<input type='hidden' name='op' value='PJReportInsert'>\n";
  echo "<input type='hidden' name='project_id' value='$project_id'>\n";
  echo "<tr><td align='center' colspan='2' class='title'>"._PJ_INPUTNOTE."</td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._PJ_PROJECT.":</td>\n";
  echo "<td><select name='project_id'>\n";
  $projectlist = $db->sql_query("SELECT `project_id`, `project_name` FROM `".$prefix."_nsnpj_projects` ORDER BY `project_name`");
  while(list($s_project_id, $s_project_name) = $db->sql_fetchrow($projectlist)){
    if($s_project_id == $project_id){ $sel = "selected"; } else { $sel = ""; }
    echo "<option value='$s_project_id' $sel>$s_project_name</option>\n";
  }
  echo "</select></td></tr>\n";        
  echo "<tr><td bgcolor='$bgcolor2'>"._PJ_TYPE.":</td><td><select name='type_id'>\n";
  $typelist = $db->sql_query("SELECT `type_id`, `type_name` FROM `".$prefix."_nsnpj_reports_types` ORDER BY `type_name`");
  while(list($s_type_id, $s_type_name) = $db->sql_fetchrow($typelist)){
    if($s_type_id == $type_id){ $sel = "selected"; } else { $sel = ""; }
    echo "<option value='$s_type_id' $sel>$s_type_name</option>\n";
  }
  echo "</select></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._PJ_USERNAME.":</td>\n";
  echo "<td><input type='text' name='submitter_name' size='30' value='".$userinfo['username']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._PJ_EMAILADDRESS.":</td>\n";
  echo "<td><input type='text' name='submitter_email' size='30' value='".$userinfo['user_email']."'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2'>"._PJ_SUMMARY.":</td>\n";
  echo "<td><input type='text' name='report_name' size='30'></td></tr>\n";
  echo "<tr><td bgcolor='$bgcolor2' valign='top'>"._PJ_DESCRIPTION.":</td>\n";
  echo "<td><textarea name='report_description' cols='60' rows='10'></textarea></td></tr>\n";
  echo "<tr><td align='center' colspan='2'><input type='submit' value='"._PJ_SUBMITREPORT."'></td></tr>\n";
  echo "</form>\n";
  echo "</table>\n";
  CloseTable();
  include_once(NUKE_BASE_DIR.'footer.php');
} else {
  header("Location: modules.php?name=$module_name");
}

?>