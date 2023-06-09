<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Advanced Downloads Module
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : index.php
   Author        : Quake (www.Nuke-Evolution.com)
   Version       : 1.0.0
   Date          : 11.21.2005 (mm.dd.yyyy)

   Notes         : Advanced Downloads module with BBGroups Integration
                   based on NSN GR Downloads.
************************************************************************/

/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network         */
/********************************************************/

if(!defined('IN_DOWNLOADS')) {
  exit('Access Denied');
}

if (!isset($cid)) { $cid = 0; }
$cid = intval($cid);
include_once(NUKE_BASE_DIR.'header.php');
if (!isset($min)) $min=0;
if (!isset($max)) $max=$min+$dl_config['perpage'];
if(isset($orderby)) { $orderby = convertorderbyin($orderby); } else { $orderby = "title ASC"; }
if ($cid == 0) {
  menu(0);
  $title = _MAIN;
} else {
  menu(1);
  $cidinfo = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE cid=$cid AND active>'0'"));
  $title = getparentlink($cidinfo['parentid'], $cidinfo['title']);
  $title = "<a href=modules.php?name=$module_name>"._MAIN."</a> -&gt; $title";
}
echo "<br />";
OpenTable();
echo "<table align='center'><tr><td><span class='option'><strong>"._CATEGORY.": $title</strong></span></td></tr></table>";
$result2 = $db->sql_query("SELECT * FROM ".$prefix."_downloads_categories WHERE parentid=$cid ORDER BY title");
$numrows2 = $db->sql_numrows($result2);
if ($numrows2 > 0) {
  echo "<table align='center' border='0' cellpadding='10' cellspacing='1' width='100%'>\n";
  $count = 0;
  while($cidinfo2 = $db->sql_fetchrow($result2)) {
    if ($count == 0) { echo "<tr>\n"; }
    if ($dl_config['show_links_num'] == 1) {
      $cnumrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='".$cidinfo2['cid']."' AND active>'0'"));
      $categoryinfo = getcategoryinfo($cidinfo2['cid']);
      $cnumm = " (".$cnumrows."/".$categoryinfo['downloads'].")";
    } else {
      $cnumm = "";
    }
    echo "<td valign='top' width='33%'><span class='option'>";
    $myimage = myimage("icon-.png");
    echo "<img align='top' src='$myimage' border='0' alt='' title=''>&nbsp;";
    echo "<a href='modules.php?name=$module_name&amp;cid=".$cidinfo2['cid']."'><strong>".$cidinfo2['title']."</strong></a>$cnumm</span>";
    newcategorygraphic($cidinfo2['cid']);
    if ($cidinfo2['cdescription']) {
      echo "<span class='content'>".$cidinfo2['cdescription']."</span><br />";
    } else {
      echo "<br />";
    }
    $space = 0;
    $result3 = $db->sql_query("SELECT cid, title FROM ".$prefix."_downloads_categories WHERE parentid='".$cidinfo2['cid']."' AND active>'0' ORDER BY title");
    while($cidinfo3 = $db->sql_fetchrow($result3)) {
      if ($dl_config['show_links_num'] == 1) {
        $snumrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE cid='".$cidinfo3['cid']."' AND active>'0'"));
        $categoryinfosub = getcategoryinfo($cidinfo3['cid']);
        $cnum = " (".$snumrows."/".$categoryinfosub['downloads'].")";
      } else {
        $cnum = "";
      }
      $myimage = myimage("icon-plus.png");
      echo "&nbsp;&nbsp;<img align='top' src='$myimage' border='0' alt='' title=''>&nbsp;";
      echo "<span class='content'><a href='modules.php?name=$module_name&amp;cid=".$cidinfo3['cid']."'>".$cidinfo3['title']."</a>$cnum</span>";
      newcategorygraphic($cidinfo3['cid']);
      echo "<br />\n";
      $space++;
    }
    echo "</td>\n";
    if ($count < 2) { $dum = 1; }
    $count++;
    if ($count == 3) { echo "</tr>\n"; $count = 0; $dum = 0; }
  }
  if ($dum == 1 && $count == 2) {
    echo "<td>&nbsp;</td>\n</tr>\n</table>\n";
  } elseif ($dum == 1 && $count == 1) {
    echo "<td>&nbsp;</td>\n<td>&nbsp;</td>\n</tr>\n</table>\n";
  } elseif ($dum == 0) {
    echo "</tr>\n</table>\n";
  }
}
$listrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid='$cid'"));
if ($listrows > 0) {
  $op = $query = "";
  $orderbyTrans = convertorderbytrans($orderby);
  echo "<table border='0' cellpadding='0' cellspacing='4' width='100%'>";
  echo "<tr><td colspan='2'><hr noshade size='1'></td></tr>";
  echo "<tr><td align='center' colspan='2'><span class='content'>"._SORTDOWNLOADSBY.": ";
  echo ""._TITLE." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=titleA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=titleD'>D</a>) ";
  echo ""._DATE." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=dateA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=dateD'>D</a>) ";
  echo ""._POPULARITY." (<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=hitsA'>A</a>\<a href='modules.php?name=$module_name&amp;cid=$cid&amp;orderby=hitsD'>D</a>)";
  echo "<br /><strong>"._RESSORTED.": $orderbyTrans</strong></span></td></tr>\n";
  echo "</table>";
  $totalselected = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid=$cid"));
  pagenums($cid, $query, $orderby, $op, $totalselected, $dl_config['perpage'], $max);
  echo "<table border='0' cellpadding='0' cellspacing='4' width='100%'>";
  // START LISTING
  $x = 0;
  $a = 0;
  $result=$db->sql_query("SELECT lid FROM ".$prefix."_downloads_downloads WHERE active>'0' AND cid=$cid ORDER BY $orderby LIMIT $min,".$dl_config['perpage']);
  while(list($lid)=$db->sql_fetchrow($result)) {
    if ($a == 0) { echo "<tr>"; }
    echo "<td valign='top' width='50%'><span class='content'>";
    showlisting($lid);
    echo "</span></td>";
    $a++;
    if ($a == 2) { echo "</tr>"; $a = 0; }
    $x++;
  }
  if ($a == 1) { echo "<td width=\"50%\">&nbsp;</td></tr>"; } else { echo "</tr>"; }
  echo "</table>";
  // END LISTING
  $orderby = convertorderbyout($orderby);
  pagenums($cid, $query, $orderby, $op, $totalselected, $dl_config['perpage'], $max);
}
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>