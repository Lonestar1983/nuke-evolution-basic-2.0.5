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

if(!defined('NSNPJ_ADMIN')) { die("Illegal File Access Detected!!"); }
$pagetitle = "NukeProject(tm): Error Loading Functions";
include_once(NUKE_BASE_DIR.'header.php');
OpenTable();
echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=PJMain\">" . _PJ_ADMIN_HEADER . "</a></div>\n";
echo "<br /><br />";
echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _PJ_RETURNMAIN . "</a> ]</div>\n";
CloseTable();
echo "<br />";
title($pagetitle);
OpenTable();
echo "It appears that NukeProject(tm) has not been configured correctly.  The
most common cause is that you either have an error in the syntax that is
including includes/nsnbc_func.php from your mainfile.php, or you have not
added the NukeProject(tm) code to your mainfile.php.  Details for including this
code are included in the download package in the <strong>Edits_For_Core_Files</strong> directory.";
CloseTable();
include_once(NUKE_BASE_DIR.'footer.php');

?>