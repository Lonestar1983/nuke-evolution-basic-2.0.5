<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network         */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

$pagetitle = _AB_NUKESENTINEL.": "._AB_ADDIP;
include(NUKE_BASE_DIR."header.php");
OpenTable();
OpenMenu(_AB_ADDIP);
ipbanmenu();
CarryMenu();
blockedipmenu();
CloseMenu();
CloseTable();
echo "<br />\n";
OpenTable();
echo "<form action='".$admin_file.".php' method='post'>\n";
echo "<table align='center' border='0' cellpadding='2' cellspacing='2'>\n";
echo "<tr bgcolor='$bgcolor1'><td align='center' class='content' colspan='2'>"._AB_ADDIPS."</td></tr>\n";
// Start submitted by technocrat
if(!isset($tip)) {
  $tip[0]=""; $tip[1]=$tip[2]=$tip[3]="0";
} else {
  if(ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $tip)) {
    $tip = explode(".", $tip);
  } else {
    $tip[0]=""; $tip[1]=$tip[2]=$tip[3]="0";
  }
}
// End submitted by technocrat
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_IPBLOCKED.":</strong></td>\n";
echo "<td><input type='text' name='xip[0]' value='$tip[0]' size='4' maxlength='3' align='right' />\n";
echo ". <input type='text' name='xip[1]' value='$tip[1]' size='4' maxlength='3' align='right' />\n";
echo ". <input type='text' name='xip[2]' value='$tip[2]' size='4' maxlength='3' align='right' />\n";
echo ". <input type='text' name='xip[3]' value='$tip[3]' size='4' maxlength='3' align='right' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_USERID.":</strong></td><td><input type='text' name='xuser_id' size='10' value='1' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_USERNAME.":</strong></td><td><input type='text' name='xusername' size='20' value='$anonymous' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_AGENT.":</strong></td><td><input type='text' name='xuser_agent' size='40' value='"._AB_UNKNOWN."' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2' valign='top'><strong>"._AB_EXPIRESIN.":</strong></td><td><select name='xexpires'>\n";
echo "<option value='0'>"._AB_PERMENANT."</option>\n";
$i=1;
while($i<=365) {
  $expiredate = date("Y-m-d", time() + ($i * 86400));
  echo "<option value='$i'>$i ($expiredate)</option>\n";
  $i++;
}
echo "</select><br />\n"._AB_EXPIRESINS."</td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_COUNTRY.":</strong></td>\n";
echo "<td><select name='xc2c'>\n";
echo "<option value='00' selected='selected'>"._AB_SELECTCOUNTRY."</option>\n";
$result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_countries` ORDER BY `c2c`");
while($countryrow = $db->sql_fetchrow($result)) {
  echo "<option value='".$countryrow['c2c']."'>".strtoupper($countryrow['c2c'])." - ".$countryrow['country']."</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2' valign='top'><strong>"._AB_NOTES.":</strong></td><td><textarea name='xnotes' $textrowcol>"._AB_ADDBY." $aid</textarea></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_REASON.":</strong></td><td><select name='xreason'>";
$result = $db->sql_query("SELECT * FROM `".$prefix."_nsnst_blockers` ORDER BY `block_name`");
while($blockerrow = $db->sql_fetchrow($result)) {
  echo "<option value='".$blockerrow['blocker']."'>".$blockerrow['reason']."</option>\n";
}
echo "</select></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_QUERY.":</strong></td><td><input type='text' name='xquery_string' size='40' value='"._AB_UNKNOWN."' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_X_FORWARDED.":</strong></td><td><input type='text' name='xx_forward_for' size='40' value='none' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_CLIENT_IP.":</strong></td><td><input type='text' name='xclient_ip' size='40' value='none' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_REMOTE_ADDR.":</strong></td><td><input type='text' name='xremote_addr' size='40' value='none' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_REMOTE_PORT.":</strong></td><td><input type='text' name='xremote_port' size='40' value='"._AB_UNKNOWN."' /></td></tr>\n";
echo "<tr><td bgcolor='$bgcolor2'><strong>"._AB_REQUEST_METHOD.":</strong></td><td><input type='text' name='xrequest_method' size='40' value='"._AB_UNKNOWN."' /></td></tr>\n";
echo "<tr><td colspan='2' align='center'><input type='checkbox' name='another' value='1' checked='checked' />"._AB_ADDANOTHERIP."</td></tr>\n";
echo "<tr><td colspan='2' align='center'><input type='hidden' name='op' value='ABBlockedIPAddSave' /><input type=submit value='"._AB_ADDIP."' /></td></tr>\n";
echo "</table>\n";
echo "</form>";
CloseTable();
include(NUKE_BASE_DIR."footer.php");

?>