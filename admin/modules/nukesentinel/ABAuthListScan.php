<?php

/********************************************************/
/* NukeSentinel(tm)                                     */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2006 by NukeScripts Network         */
/* See CREDITS.txt for ALL contributors                 */
/********************************************************/

if(is_god($_COOKIE['admin'])) {
  $importad = $db->sql_query("SELECT `aid`, `name`, `pwd` FROM `".$prefix."_authors`");
  while(list($a_aid, $a_name, $a_pwd) = $db->sql_fetchrow($importad)) {
    $adminrow = $db->sql_numrows($db->sql_query("SELECT `aid` FROM `".$prefix."_nsnst_admins` WHERE `aid`='$a_aid'"));
    if($adminrow == 0) {
      $makepass = "";
      $strs = "abc2def3ghj4kmn5opq6rst7uvw8xyz9";
      for($x=0; $x < 20; $x++) {
        mt_srand ((double) microtime() * 1000000);
        $str[$x] = substr($strs, mt_rand(0, strlen($strs)-1), 1);
        $makepass = $makepass.$str[$x];
      }
      $xpassword_md5 = md5($makepass);
      $xpassword_crypt = crypt($makepass);
      if(!get_magic_quotes_runtime()) { $makepass = addslashes($makepass); }
      if(strtolower($a_name) == "god") { $is_god = 1; } else { $is_god = 0; }
      $result = $db->sql_query("INSERT INTO `".$prefix."_nsnst_admins` (`aid`, `login`, `protected`, `password`, `password_md5`, `password_crypt`) VALUES ('$a_aid', '$a_aid', '$is_god', '$makepass', '$xpassword_md5', '$xpassword_crypt')");
      $db->sql_query("OPTIMIZE TABLE ".$prefix."_nsnst_admins");
      $aidrow = $db->sql_fetchrow($db->sql_query("SELECT * FROM `".$prefix."_nsnst_admins` WHERE `aid`='$a_aid'"));
      $subject = _AB_ACCESSFOR." ".$nuke_config['sitename'];
      $message  = ""._AB_HTTPONLY."\n";
      $message .= ""._AB_LOGIN.": ".$aidrow['login']."\n";
      $message .= ""._AB_PASSWORD.": ".$aidrow['password']."\n";
      $message .= ""._AB_PROTECTED.": ";
      if($aidrow['protected']==0) { $message .= ""._AB_NO."\n"; } else { $message .= ""._AB_YES."\n"; }
      list($amail) = $db->sql_fetchrow($db->sql_query("SELECT `email` FROM `".$prefix."_authors` WHERE `aid`='$a_aid'"));
      @evo_mail($amail, $subject, $message,"From: ".$nuke_config['adminmail']."\r\nX-Mailer: "._AB_NUKESENTINEL."\r\n");
    }
  }
  $exportad = $db->sql_query("SELECT `aid` FROM `".$prefix."_nsnst_admins`");
  while(list($a_aid) = $db->sql_fetchrow($exportad)) {
    $adminrow = $db->sql_numrows($db->sql_query("SELECT `aid` FROM `".$prefix."_authors` WHERE `aid`='$a_aid'"));
    if($adminrow == 0) {
      $result = $db->sql_query("DELETE FROM `".$prefix."_nsnst_admins` WHERE `aid`='$a_aid'");
      $db->sql_query("OPTIMIZE TABLE `".$prefix."_nsnst_admins`");
    }
  }
  $pagetitle = _AB_NUKESENTINEL.": "._AB_SCANADMINS;
  include(NUKE_BASE_DIR."header.php");
  OpenTable();
  OpenMenu(_AB_SCANADMINS);
  ipbanmenu();
  CarryMenu();
  searchmenu();
  CloseMenu();
  CloseTable();
  echo "<br />\n";
  OpenTable();
  echo "<center><strong>"._AB_SCANADMINSDONE."</strong></center>\n";
  CloseTable();
  include(NUKE_BASE_DIR."footer.php");
}

?>