<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

// ==========================================
// PHP-NUKE: Shout Box
// ==========================
//
// Copyright (c) 2003-2005 by Aric Bolf (SuperCat)
// http://www.OurScripts.net
//
// Copyright (c) 2002 by Quiecom
// http://www.Quiecom.com
//
// This program is free software. You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation
// ===========================================

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       08/10/2005
      NukeSentinel                             v2.4.2       10/29/2005
-=[Mod]=-
      Advanced Username Color                  v1.0.5       10/10/2005
 ************************************************************************/

if(!defined('NUKE_EVO')) exit;

global $prefix, $ShoutSubmit, $ShoutComment, $db, $user, $cookie, $shoutuid, $top_content, $mid_content, $bottom_content, $ShoutMarqueewidth, $ShoutMarqueeheight, $currentlang, $user_prefix;

function ShoutBox($ShoutSubmit, $prefix, $ShoutComment, $db, $user, $cookie, $shoutuid) {

    global $admin, $admin_file, $currentlang, $top_content, $mid_content, $bottom_content, $ShoutMarqueewidth, $ShoutMarqueeheight, $nsnst_const, $user_prefix;

    $self = ereg_replace("/", "",$_SERVER['PHP_SELF']);
    if ((is_admin()) AND ("".$admin_file.".php" == $self)) {
        $sqlV = "SELECT * FROM ".$prefix."_config";
        $resultV = $db->sql_query($sqlV);
        $confV = $db->sql_fetchrow($resultV);
        if ($confV['Version_Num'] >= '7.6') {
            $preURL = 'index.php?url=';
        } else {
            $preURL = '';
        }
    }

    if ($currentlang) {
        include_once(NUKE_MODULES_DIR.'Shout_Box/lang-block/lang-'.$currentlang.'.php');
    } else {
        include_once(NUKE_MODULES_DIR.'Shout_Box/lang-block/lang-english.php');
    }

    $PreviousShoutComment = $ShoutComment;

    include_once(NUKE_BASE_DIR."config.php");

    cookiedecode();
    $username = $cookie[1];

    if (empty($username)) { $username = "Anonymous"; }

    $sql = "SELECT * FROM ".$prefix."_shoutbox_conf";
    $result = $db->sql_query($sql);
    $conf = $db->sql_fetchrow($result);

    // Check if block is in center position
    $sql = "SELECT bposition FROM ".$prefix."_blocks WHERE blockfile='block-Shout_Box.php'";
    $SBpos = $db->sql_query($sql);
    $SBpos = $db->sql_fetchrow($SBpos);
    if ($SBpos['bposition'] == 'c' || $SBpos['bposition'] == 'd') {
        $SBpos = 'center';
        $SBborder = 1;
    } else {
        $SBpos = 'side';
        $SBborder = 0;
    }

    // Find user's IP
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $uip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $uip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
        $uip = getenv("REMOTE_ADDR");
/*****[BEGIN]******************************************
 [ Base:    NukeSentinel                       v2.4.2 ]
 ******************************************************/
    } else if (isset($nsnst_const['remote_ip']) && $nsnst_const['remote_ip'] && strcasecmp($nsnst_const['remote_ip'], "unknown")) {
        $uip = $nsnst_const['remote_ip'];
    } else {
        $uip = "";
    }
    if ($uip == 'unknown') { $uip = $nsnst_const['remote_ip']; }
    if (empty($uip)) { $uip = $nsnst_const['remote_ip']; }
/*****[END]********************************************
 [ Base:    NukeSentinel                       v2.4.2 ]
 ******************************************************/
    if (empty($uip)) { $uip = "noip"; }
    if ($uip == 'unknown') { $uip = "noip"; }

    //do IP test then ban if on list
    if($conf['ipblock'] == "yes") {
        $sql = "SELECT * FROM ".$prefix."_shoutbox_ipblock";
        $ipresult = $db->sql_query($sql);
        while ($badips = $db->sql_fetchrow($ipresult)) {
            if (eregi("\*", $badips['name'])) { // Allow for Subnet bans like 123.456.*
                $badipsArray = explode(".",$badips['name']);
                $uipArray = explode(".",$uip);
                $i = 0;
                foreach($badipsArray as $badipsPart) {
                    if ($badipsPart == "*") { $BannedShouter = "yes"; break; }
                    if ($badipsPart != $uipArray[$i] AND $badipsPart != "*") { break; }
                    $i++;
                }
            } else {
                if($uip == $badips['name']) { $BannedShouter = "yes"; break; }
            }
        }
    }
    //do name test then ban if on list (only applies to registered users)
    if ($conf['nameblock'] == "yes" AND $BannedShouter != "yes") {
        $sql = "SELECT * FROM ".$prefix."_shoutbox_nameblock";
        $nameresult = $db->sql_query($sql);
        while ($badname = $db->sql_fetchrow($nameresult)){
            if ($username == $badname['name']) { $BannedShouter = "yes"; break; }
        }
    }
    if ($BannedShouter != "yes") {

    if ($ShoutSubmit == "ShoutPost") {
    // start processing shout
    if ($shoutuid) { $username = "$shoutuid"; }
    //shoutuid tests
    $username = trim($username); // remove whitespace off ends of nickname
    if($conf['anonymouspost'] == "yes") {
        $unum = strlen($username);
        if ($unum < 2) { $ShoutError = ""._NICKTOOSHORT.""; }
        if (!$username OR $username == ""._NAME."") { $ShoutError = ""._NONICK.""; }
        if (eregi(".xxx", $username) AND $conf['blockxxx'] == "yes") { $username = "Anonymous"; }
        if (eregi("javascript:(.*)", $username)) { $username = "Anonymous"; }
        $username = htmlspecialchars($username, ENT_QUOTES);
        $username = ereg_replace("&amp;amp;", "&amp;",$username);
    }
    if (!is_user() && ($username) && $username != "Anonymous") {
        $username = ereg_replace(" ", "_",$username);
    }

    $ShoutComment = trim($ShoutComment); // remove whitespace off ends of shout
    $ShoutComment = preg_replace('/\s+/', ' ', $ShoutComment); // convert double spaces in middle of shout to single space
    $num = strlen($ShoutComment);
    if ($num < 1) { $ShoutError = ""._SHOUTTOOSHORT.""; }
    if ($num > 2500) { $ShoutError = ""._SHOUTTOOLONG.""; }
    if (!$ShoutComment) { $ShoutError = ""._NOSHOUT.""; }
    if ($ShoutComment == ""._SB_MESSAGE."") { $ShoutError = ""._NOSHOUT.""; }
    $ShoutComment = ereg_replace(" [.] ", ".",$ShoutComment);
    if (eregi(".xxx", $ShoutComment) AND $conf['blockxxx'] == "yes") {
        $ShoutError = ""._XXXBLOCKED."";
        $PreviousShoutComment = "";
    }
    if (eregi("javascript:(.*)", $ShoutComment)) {
        $ShoutError = ""._JSINSHOUT."";
        $PreviousShoutComment = "";
    }

    $ShoutComment = htmlspecialchars($ShoutComment, ENT_QUOTES);
    $ShoutComment = ereg_replace("&amp;amp;", "&amp;",$ShoutComment);

    // Scan for links in the shout. If there is, replace it with [URL] or block it if disallowed
    $i = 0;
    $ShoutNew = '';
    $ShoutArray = explode(" ",$ShoutComment);
    foreach($ShoutArray as $ShoutPart) {
        if (is_array($ShoutPart) == TRUE) { $ShoutPart = $ShoutPart[0]; }
        if (eregi("http:\/\/", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            // fix for users adding text to the beginning of links: HACKhttp://www.website.com
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"http://");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" target=\"_blank\" href=\"$ShoutPart\">URL</a>&#93;";
        } elseif (eregi("ftp:\/\/", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"ftp://");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" target=\"_blank\" href=\"$ShoutPart\">FTP</a>&#93;";
        } elseif (eregi("irc:\/\/", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"irc://");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" href=\"$ShoutPart\">IRC</a>&#93;";
        } elseif (eregi("teamspeak:\/\/", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"teamspeak://");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" href=\"$ShoutPart\">TeamSpeak</a>&#93;";
        } elseif (eregi("aim:goim", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"aim:goim");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" href=\"$ShoutPart\">AIM</a>&#93;";
        } elseif (eregi("gopher:\/\/", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"gopher://");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" href=\"$ShoutPart\">Gopher</a>&#93;";
        } elseif (eregi("mailto:", $ShoutPart)) {
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"mailto:");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            // email encoding to stop harvesters
            $ShoutPart = bin2hex($ShoutPart);
            $ShoutPart = chunk_split($ShoutPart, 2, '%');
            $ShoutPart = '%' . substr($ShoutPart, 0, strlen($ShoutPart) - 1);
            $ShoutNew[$i] = "&#91;<a href=\"$ShoutPart\">E-Mail</a>&#93;";
        } elseif (eregi("www\.", $ShoutPart)) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPartL = strtolower($ShoutPart);
            $spot = strpos($ShoutPartL,"www.");
            if ($spot > 0) { $ShoutPart = substr($ShoutPart, $spot); }
            $ShoutPart = "http://" . $ShoutPart;
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" target=\"_blank\" href=\"$ShoutPart\">URL</a>&#93;";
        } elseif (eregi('@', $ShoutPart) AND eregi('\.', $ShoutPart)) {
            // email encoding to stop harvesters
            $ShoutPart = bin2hex($ShoutPart);
            $ShoutPart = chunk_split($ShoutPart, 2, '%');
            $ShoutPart = '%' . substr($ShoutPart, 0, strlen($ShoutPart) - 1);
            $ShoutNew[$i] = "&#91;<a href=\"mailto:$ShoutPart\">E-Mail</a>&#93;";
        } elseif ((eregi("\.(us|tv|cc|ws|ca|de|jp|ro|be|fm|ms|tc|ph|dk|st|ac|gs|vg|sh|kz|as|lt|to)", substr("$ShoutPart", -3,3))) OR (eregi("\.(com|net|org|mil|gov|biz|pro|xxx)", substr("$ShoutPart", -4,4))) OR (eregi("\.(info|name|mobi)", substr("$ShoutPart", -5,5))) OR (eregi("\.(co\.uk|co\.za|co\.nz|co\.il)", substr("$ShoutPart", -6,6)))) {
            if (((!is_user()) AND ($conf['urlanononoff'] == "no")) OR ((is_user()) AND ($conf['urlonoff'] == "no"))) { $ShoutError = ""._URLNOTALLOWED.""; break; }
            $ShoutPart = "http://" . $ShoutPart;
            $ShoutNew[$i] = "&#91;<a rel=\"nofollow\" target=\"_blank\" href=\"$ShoutPart\">URL</a>&#93;";
        } elseif (strlen(html_entity_decode($ShoutPart, ENT_QUOTES)) > 21) {
            $ShoutNew[$i] = htmlspecialchars(wordwrap(html_entity_decode($ShoutPart, ENT_QUOTES), 21, " ", 1), ENT_QUOTES);

            $ShoutNew[$i] = str_replace("[ b]", " [b]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[b ]", " [b]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[ /b]", "[/b] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/ b]", "[/b] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/b ]", "[/b] ",$ShoutNew[$i]);

            $ShoutNew[$i] = str_replace("[ i]", " [i]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[i ]", " [i]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[ /i]", "[/i] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/ i]", "[/i] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/i ]", "[/i] ",$ShoutNew[$i]);

            $ShoutNew[$i] = str_replace("[ u]", " [u]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[u ]", " [u]",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[ /u]", "[/u] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/ u]", "[/u] ",$ShoutNew[$i]);
            $ShoutNew[$i] = str_replace("[/u ]", "[/u] ",$ShoutNew[$i]);
        } else { $ShoutNew[$i] = $ShoutPart; }
        $i++;
    }
    if (empty($ShoutError)) { $ShoutComment = implode(" ",$ShoutNew); }

    //Smilies from database
    $ShoutArrayReplace = explode(" ",$ShoutComment);
    $ShoutArrayScan = $ShoutArrayReplace;
    $sql = "SELECT * FROM ".$prefix."_shoutbox_emoticons";
    $eresult = $db->sql_query($sql);
    while ($emoticons = $db->sql_fetchrow($eresult)) {
        $i = 0;
        foreach($ShoutArrayScan as $ShoutPart) {
            if ($ShoutPart == $emoticons['text']) { $ShoutArrayReplace[$i] = $emoticons['image']; }
            $i++;
        }
    }
    $ShoutComment = implode(" ",$ShoutArrayReplace);

    //do name test then error if on list
    if($conf['nameblock'] == "yes"){
        $sql = "SELECT * FROM ".$prefix."_shoutbox_nameblock";
        $nameresult = $db->sql_query($sql);
        while ($badname = $db->sql_fetchrow($nameresult)){
            if($username == $badname['name']) {
                $ShoutError = ""._BANNEDNICK."";
            }
        }
    }

    // check for anonymous users cloning/ghosting registered users' nicknames
    cookiedecode();
    if (!is_user() && ($username) && $username != "Anonymous") {
        $sql = "SELECT * FROM ".$user_prefix."_users WHERE username='$username'";
        $nameresult = $db->sql_query($sql);
        $row = $db->sql_fetchrow($nameresult);
        if ($row) {
            $ShoutError = ""._NOCLONINGNICKS."";
        }
    }

    //look for bad words, then censor them.
    if($conf['censor'] == "yes") {
        // start Anonymous nickname censor check here. If bad, replace bad nick with 'Anonymous'
        if (!is_user() && ($username) && $username != "Anonymous") {
            $sql = "SELECT * FROM ".$prefix."_shoutbox_censor";
            $cresult = $db->sql_query($sql);
            while ($censor = $db->sql_fetchrow($cresult)) {
                if ($username != 'Anonymous') {
                    $one = strtolower($censor['text']);
                    $usernameL = strtolower($username);
                    if (stristr($usernameL, $one) !== false) {
                        $username = "Anonymous";
                    }
                }
            }
        }
        // Censor of posting text
        $ShoutArrayReplace = explode(" ",$ShoutComment);
        $ShoutArrayScan = $ShoutArrayReplace;
        $sql = "SELECT * FROM ".$prefix."_shoutbox_censor";
        $cresult = $db->sql_query($sql);
        while ($censor = $db->sql_fetchrow($cresult)) {
            $i = 0;
            foreach($ShoutArrayScan as $ShoutPart) {
                $ShoutPart = strtolower($ShoutPart);
                $censor['text'] = strtolower($censor['text']);
                if ($ShoutPart == $censor['text']) { $ShoutArrayReplace[$i] = $censor['replacement']; }
                $i++;
            }
        }
        $ShoutComment = implode(" ",$ShoutArrayReplace);

        /*
        // Phrase censor - Needs work before implementing
        $sql = "SELECT * FROM ".$prefix."_shoutbox_emoticons";
        $eresult = $db->sql_query($sql);
        while ($emoticons = $db->sql_fetchrow($eresult)) {
            $ShoutComment = str_replace($emoticons['text'],$emoticons['image'],$ShoutComment);
        }
        */
    }

    // duplicate posting checker. stops repeated spam attacks
    $sql = "SELECT * FROM ".$prefix."_shoutbox_shouts ORDER BY id DESC LIMIT 5";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        if ($row['comment'] == $ShoutComment) {
            $ShoutError = ""._DUPLICATESHOUT."";
        }
    }

    if ($conf['anonymouspost'] == "no" && $username == "Anonymous") {
            $ShoutError = ""._ONLYREGISTERED2."";
    }

    if (!$ShoutError) {
        $sql = "SELECT * FROM ".$prefix."_shoutbox_date";
        $resultD = $db->sql_query($sql);
        $rowD = $db->sql_fetchrow($resultD);

        // Special thanks to JRSweets for tipping me off to the timestamp option in date()
        if ($conf['timeOffset'] == 0) {
            $day = date("$rowD[date]");
            $time = date("$rowD[time]");
        } elseif (strstr($conf['timeOffset'], '+')) {
            $sbTimeMultiplier = str_replace('+', '', $conf['timeOffset']);
            $sbTimeOffset = $sbTimeMultiplier * 3600;
            $sbTimeTemp = time();
            $time = date("$rowD[time]", ($sbTimeTemp + $sbTimeOffset));
            $day = date("$rowD[date]", ($sbTimeTemp + $sbTimeOffset));
        } else {
            $sbTimeMultiplier = str_replace('-', '', $conf['timeOffset']);
            $sbTimeOffset = $sbTimeMultiplier * 3600;
            $sbTimeTemp = time();
            $time = date("$rowD[time]", ($sbTimeTemp - $sbTimeOffset));
            $day = date("$rowD[date]", ($sbTimeTemp - $sbTimeOffset));
        }

        $currentTime = time();

        $sql = "INSERT INTO ".$prefix."_shoutbox_shouts (id,name,comment,date,time,ip,timestamp) VALUES ('0','$username','$ShoutComment','$day','$time','$uip','$currentTime')";
        $db->sql_query($sql);

        // if v7.0 of nuke or higher, add points earned per shout
        $sqlVer = "SELECT * FROM ".$prefix."_config";
        $resultVer = $db->sql_query($sqlVer);
        $confVer = $db->sql_fetchrow($resultVer);
        if (is_user() AND $confVer['Version_Num'] >= '7.0' AND $conf['pointspershout'] > 0) {
            $sqlP = "SELECT user_id,points FROM ".$user_prefix."_users WHERE username='$username'";
            $resultP = $db->sql_query($sqlP);
            $userP = $db->sql_fetchrow($resultP);
            $userPoints = $userP['points'] + $conf['pointspershout'];
            $sqlP = "UPDATE ".$user_prefix."_users set points='$userPoints' WHERE user_id='$userP[user_id]'";
            $db->sql_query($sqlP);
        }

        $PreviousShoutComment = "";
        $PreviousComment = "";
    } else {
        if ($username != ""._NAME."") {
            $PreviousUsername = $username;
        }
        if ($PreviousShoutComment != ""._SB_MESSAGE."") {
            $PreviousComment = $PreviousShoutComment;
        }
    }

    }

    //Display Content From here on down

    if (!is_user() && ($username) && $username != "Anonymous") { $username = "Anonymous"; }

    $ThemeSel = get_theme();
    $sql = "SELECT * FROM ".$prefix."_shoutbox_theme_images WHERE themeName='$ThemeSel'";
    $result = $db->sql_query($sql);
    $themeRow = $db->sql_fetchrow($result);

    if ($themeRow['blockBackgroundImage'] != '' AND file_exists("modules/Shout_Box/images/background/$themeRow[blockBackgroundImage]")) {
        $showBackground = 'yes';
    } else {
        $showBackground = 'no';
    }

    if (file_exists("modules/Shout_Box/images/up/$themeRow[blockArrowColor]") AND $themeRow['blockArrowColor'] != '') {
        $up_img = "modules/Shout_Box/images/up/$themeRow[blockArrowColor]";
    } else {
        $up_img = "modules/Shout_Box/images/up/Black.gif";
    }
    if (file_exists("modules/Shout_Box/images/down/$themeRow[blockArrowColor]") AND $themeRow['blockArrowColor'] != '') {
        $down_img = "modules/Shout_Box/images/down/$themeRow[blockArrowColor]";
    } else {
        $down_img = "modules/Shout_Box/images/down/Black.gif";
    }
    if (file_exists("modules/Shout_Box/images/pause/$themeRow[blockArrowColor]") AND $themeRow['blockArrowColor'] != '') {
        $pause_img = "modules/Shout_Box/images/pause/$themeRow[blockArrowColor]";
    } else {
        $pause_img = "modules/Shout_Box/images/pause/Black.gif";
    }

    $sql = "SELECT * FROM ".$prefix."_shoutbox_shouts ORDER BY id DESC LIMIT $conf[number]";
    $result = $db->sql_query($sql);

    // Top half

    // shout error reporting
    $top_content = "";
    if ($ShoutError) {
        $top_content .= "<table style=\"cursor: text;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td style=\"background-color: #FFFFE1;\"><strong>"._SB_NOTE.":</strong> $ShoutError</td></tr></table>";
    }

    // table that holds the scrolling area
    if ($showBackground == 'yes') {
        $top_content .= "<table style=\"cursor: text;\" width=\"100%\" border=\"$SBborder\" cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"background: url(modules/Shout_Box/images/background/$themeRow[blockBackgroundImage]);\" height=\"$conf[height]\">\n";
    } else {
        $top_content .= "<table style=\"cursor: text;\" width=\"100%\" border=\"$SBborder\" cellspacing=\"0\" cellpadding=\"0\"><tr><td height=\"$conf[height]\">\n";
    }
    // end top content

    // table of the actual scrolling content
    if ($showBackground == 'yes') {
        $mid_content = "<table style=\"table-layout: fixed; width: 100%;\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"5\">";
    } else {
        $mid_content = "<table style=\"table-layout: fixed; width: 100%;\" border=\"0\" align=\"left\" cellspacing=\"0\" cellpadding=\"2\">";
    }
    $flag = 1;
    $ThemeSel = get_theme();
    $sql = "SELECT * FROM ".$prefix."_shoutbox_themes WHERE themeName='$ThemeSel'";
    $resultT = $db->sql_query($sql);
    $rowColor = $db->sql_fetchrow($resultT);

    if (is_user()) {
        $username = $cookie[1];
        if ($username != '') {
            $sqlF = "SELECT user_timezone, user_dateformat FROM ".$user_prefix."_users WHERE username='$username'";
            $resultF = $db->sql_query($sqlF);
            $userSetup = $db->sql_fetchrow($resultF);
        }
    }
    $sql = "SELECT * FROM ".$prefix."_shoutbox_date";
    $resultD = $db->sql_query($sql);
    $rowD = $db->sql_fetchrow($resultD);

    // Sticky shouts
    $sql = "SELECT * FROM ".$prefix."_shoutbox_sticky WHERE stickySlot=0";
    $stickyResult = $db->sql_query($sql);
    $stickyRow0 = $db->sql_fetchrow($stickyResult);
    $sql = "SELECT * FROM ".$prefix."_shoutbox_sticky WHERE stickySlot=1";
    $stickyResult = $db->sql_query($sql);
    $stickyRow1 = $db->sql_fetchrow($stickyResult);

    if ($stickyRow0) {
        if ($showBackground == 'yes') {
            $mid_content .= "<tr><td>";
        } else {
            if ($flag == 1) { $flag = 2; }
            elseif ($flag == 2) { $flag = 1; }
            $mid_content .= "<tr><td style=\"background-color: $rowColor[blockColor1];\">";
        }
        $mid_content .= "<strong>"._SB_ADMIN.":</strong> $stickyRow0[comment]";
        if ($conf['date'] == "yes") {
            if (is_user()) {
                // add time adjustment for following user's timezone
                $displayTime = $userSetup['user_timezone'] - $conf['serverTimezone'];
                $displayTime = $displayTime * 3600;
                $newTimestamp = $stickyRow0['timestamp'] + $displayTime;
                $unixTime = date("$userSetup[user_dateformat]", $newTimestamp);
                $mid_content .= "<br />$unixTime";
            } else {
                $unixDay = date("$rowD[date]", $stickyRow0['timestamp']);
                $unixTime = date("$rowD[time]", $stickyRow0['timestamp']);
                $mid_content .= "<br />$unixDay&nbsp;$unixTime";
            }
        }
        $mid_content .= "</td></tr>";
    }
    if ($stickyRow1) {
        if ($showBackground == 'yes') {
            $mid_content .= "<tr><td>";
        } else {
            if ($flag == 1) { $flag = 2; }
            elseif ($flag == 2) { $flag = 1; }
            $mid_content .= "<tr><td style=\"background-color: $rowColor[blockColor2];\">";
        }
        $mid_content .= "<strong>"._SB_ADMIN.":</strong> $stickyRow1[comment]";
        if ($conf['date'] == "yes") {
            if (is_user()) {
                // add time adjustment for following user's timezone
                $displayTime = $userSetup['user_timezone'] - $conf['serverTimezone'];
                $displayTime = $displayTime * 3600;
                $newTimestamp = $stickyRow1['timestamp'] + $displayTime;
                $unixTime = date("$userSetup[user_dateformat]", $newTimestamp);
                $mid_content .= "<br />$unixTime";
            } else {
                $unixDay = date("$rowD[date]", $stickyRow1['timestamp']);
                $unixTime = date("$rowD[time]", $stickyRow1['timestamp']);
                $mid_content .= "<br />$unixDay&nbsp;$unixTime";
            }
        }
        $mid_content .= "</td></tr>";
    }
    // end sticky shouts

    $i = 0;
    while ($row = $db->sql_fetchrow($result)) {
        if ($flag == 1) { $bgcolor = $rowColor['blockColor1']; }
        if ($flag == 2) { $bgcolor = $rowColor['blockColor2']; }
        if ($showBackground == 'yes') {
            $tempContent[$i] = "<tr><td>";
        } else {
            $tempContent[$i] = "<tr><td style=\"background-color: $bgcolor;\">";
        }
        $ShoutComment = str_replace('src=', 'src="', $row['comment']);
        $ShoutComment = str_replace('.gif>', '.gif" alt="" />', $ShoutComment);
        $ShoutComment = str_replace('.jpg>', '.jpg" alt="" />', $ShoutComment);
        $ShoutComment = str_replace('.png>', '.png" alt="" />', $ShoutComment);
        $ShoutComment = str_replace('.bmp>', '.bmp" alt="" />', $ShoutComment);
        $ShoutComment = str_replace("http:", "".$preURL."http:", $ShoutComment);
        $ShoutComment = str_replace("ftp:", "".$preURL."ftp:", $ShoutComment);

        // BB code [b]word[/b] [i]word[/i] [u]word[/u]
        if ((eregi("[b]", $ShoutComment)) AND (eregi("[/b]", $ShoutComment)) AND (substr_count("$ShoutComment","[b]") == substr_count("$ShoutComment","[/b]"))) {
            $ShoutComment = eregi_replace("\[b\]","<span style=\"font-weight: bold\">","$ShoutComment");
            $ShoutComment = eregi_replace("\[\/b\]","</span>","$ShoutComment");
        }
        if ((eregi("[i]", $ShoutComment)) AND (eregi("[/i]", $ShoutComment)) AND (substr_count("$ShoutComment","[i]") == substr_count("$ShoutComment","[/i]"))) {
            $ShoutComment = eregi_replace("\[i\]","<span style=\"font-style: italic\">","$ShoutComment");
            $ShoutComment = eregi_replace("\[\/i\]","</span>","$ShoutComment");
        }
        if ((eregi("[u]", $ShoutComment)) AND (eregi("[/u]", $ShoutComment)) AND (substr_count("$ShoutComment","[u]") == substr_count("$ShoutComment","[/u]"))) {
            $ShoutComment = eregi_replace("\[u\]","<span style=\"text-decoration: underline\">","$ShoutComment");
            $ShoutComment = eregi_replace("\[\/u\]","</span>","$ShoutComment");
        }

        if ($username == "Anonymous") {
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
            $tempContent[$i] .= "<strong>" . UsernameColor($row['name']) . ":</strong> $ShoutComment";
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
        }
        else {
            // check to see if nickname is a user in the DB
            $sqlN = "SELECT * FROM ".$user_prefix."_users WHERE username='$row[name]'";
            $nameresultN = $db->sql_query($sqlN);
            $rowN = $db->sql_fetchrow($nameresultN);
            if (($rowN) AND ($row['name'] != "Anonymous")) {
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
                $tempContent[$i] .= "<strong><a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$row[name]\">" . UsernameColor($row['name']) . "</a>:</strong> $ShoutComment";
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
            } else {
                $tempContent[$i] .= "<strong>$row[name]:</strong> $ShoutComment";
            }
        }
        if ($conf['date'] == "yes") {
            if ($row['timestamp'] != '') {
                // reads unix timestamp and formats it to the viewer's timezone
                if (is_user()) {
                    // time adjustment for following user's timezone
                    $displayTime = $userSetup['user_timezone'] - $conf['serverTimezone'];
                    $displayTime = $displayTime * 3600;
                    $newTimestamp = $row['timestamp'] + $displayTime;
                    $unixTime = date("$userSetup[user_dateformat]", $newTimestamp);
                    $tempContent[$i] .= "<br />$unixTime";
                } else {
                    // adjustmet for timezone offset
                    $displayTime = $conf['timeOffset'] * 3600;
                    $newTimestamp = $row['timestamp'] + $displayTime;
                    $unixDay = date("$rowD[date]", $newTimestamp);
                    $unixTime = date("$rowD[time]", $newTimestamp);
                    $tempContent[$i] .= "<br />$unixDay&nbsp;$unixTime";
                }
            } else {
                $tempContent[$i] .= "<br />$row[date]&nbsp;$row[time]";
            }
        }
        $tempContent[$i] .= "</td></tr>";
        if ($flag == 1) { $flag = 2; }
        elseif ($flag == 2) { $flag = 1; }
        $i++;
    }
    // Reversing the posts
    if ($conf['reversePosts'] == "no") {
        for ($j = 0; $j < $conf['number']; $j++) {
            $mid_content .= $tempContent[$j];
        }
    } else {
        for ($j = $conf['number']; $j >= 0; $j = $j - 1) {
            $mid_content .= $tempContent[$j];
        }
    }
    // You may not remove or edit this copyright!!! Doing so violates the GPL license.
    $mid_content .= "<tr><td align=\"right\"><a title=\"Free scripts!\" target=\"_blank\" href=\"http://www.ourscripts.net\"><span style=\"font-size: 9;\">Shout Box &copy;</span></a></td></tr></table>";
    // end copyright.
    // end mid content
    // start bottom content $bottom_content

    $bottom_content = "</td></tr></table>\n";

    // bottom half

    if ($conf['anonymouspost'] == "no" && $username == "Anonymous") {
        $bottom_content .= "<div style=\"padding: 1px;\" align=\"center\" class=\"content\"><a href=\"modules.php?name=Shout_Box\">"._SHOUTHISTORY."</a>";
        $bottom_content .= "&nbsp;<span style=\"cursor: pointer;\" onmouseover=\"SBspeed=4\" onmouseout=\"SBspeed=1\"><img src=\"$up_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "&nbsp;<span style=\"cursor: pointer;\" onmouseover=\"SBspeed=1-5\" onmouseout=\"SBspeed=1\"><img src=\"$down_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "&nbsp;<span style=\"cursor: wait;\" onmouseover=\"SBspeed=0\" onmouseout=\"SBspeed=1\"><img src=\"$pause_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "</div><div style=\"padding: 1px; text-align: center;\" class=\"content\"><br />"._ONLYREGISTERED." <a href=\"modules.php?name=Your_Account\">"._SHOUTLOGIN."</a> "._OR." <a href=\"modules.php?name=Your_Account&amp;op=new_user\">"._CREATEANACCT."</a>.</div>";
    } else {
        $bottom_content .= "<form name=\"shoutform1\" method=\"post\" action=\"\" style=\"margin-bottom: 0px; margin-top: 0px\">";
        $bottom_content .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">";
        $bottom_content .= "<tr><td align=\"center\"><a href=\"modules.php?name=Shout_Box\">"._SHOUTHISTORY."</a>";
        $bottom_content .= "&nbsp;<span style=\"cursor: pointer;\" onmouseover=\"SBspeed=4\" onmouseout=\"SBspeed=1\"><img src=\"$up_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "&nbsp;<span style=\"cursor: pointer;\" onmouseover=\"SBspeed=1-5\" onmouseout=\"SBspeed=1\"><img src=\"$down_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "&nbsp;<span style=\"cursor: wait;\" onmouseover=\"SBspeed=0\" onmouseout=\"SBspeed=1\"><img src=\"$pause_img\" border=\"0\" alt=\"\" width=\"9\" height=\"5\" /></span>";
        $bottom_content .= "</td></tr>\n";
        // Start smilie Drop-Down Code
        $messageDefinition = ""._SB_MESSAGE."";

        if (eregi("MSIE(.*)", $_SERVER['HTTP_USER_AGENT']) || eregi("Konqueror/3(.*)", $_SERVER['HTTP_USER_AGENT']) || (eregi("Opera(.*)", $_SERVER['HTTP_USER_AGENT']))) {
            $ShoutNameWidth = $conf['textWidth'];
            $ShoutTextWidth = $conf['textWidth'];
        } else {
            // Firefox, Mozilla, NS, and any others.
            $ShoutNameWidth = $conf['textWidth'] - 4;
            $ShoutTextWidth = $conf['textWidth'] - 4;
        }
        if ($conf['anonymouspost'] == "yes" && $username == "Anonymous") {
            if ($PreviousUsername) { $boxtext = $PreviousUsername; } else { $boxtext = ""._NAME.""; }
            $bottom_content .= "<tr><td align=\"center\"><input type=\"text\" name=\"shoutuid\" size=\"$ShoutNameWidth\" value=\"$boxtext\" maxlength=\"25\" onfocus=\"if ( this.value == '"._NAME."' ) { this.value=''; }\" onblur=\"if (this.value == '') { this.value='"._NAME."' }\" /></td></tr>\n";
        }
        if ($PreviousComment) { $boxtext = $PreviousComment; } else { $boxtext = ""._SB_MESSAGE.""; }
        $bottom_content .= "<tr><td align=\"center\" nowrap=\"nowrap\"><input type=\"text\" name=\"ShoutComment\" size=\"$ShoutTextWidth\" value=\"$boxtext\" maxlength=\"2500\" onfocus=\"if ( this.value == '"._SB_MESSAGE."' ) { this.value=''; }\" onblur=\"if (this.value == '') { this.value='"._SB_MESSAGE."' }\" /></td></tr>";

        $bottom_content .= "<tr><td align=\"center\"><input type=\"hidden\" name=\"ShoutSubmit\" value=\"ShoutPost\" />";

        $bottom_content .= "<div id=\"smilies_hide\" style=\"display: block;\"><div class=\"content\"><input type=\"submit\" name=\"button\" value=\""._SHOUT."\" />&nbsp;<span onclick=\"changeBoxSize ('show'); return false;\"><input type=\"button\" value=\""._SMILIES."\" /></span></div></div>";

        $bottom_content .= "<div id=\"smilies_show\" style=\"display: none;\"><div class=\"content\"><input type=\"submit\" name=\"button\" value=\""._SHOUT."\" />&nbsp;<span onclick=\"changeBoxSize ('hide'); return false;\"><input type=\"button\" value=\""._SMILIES."\" /></span><br /><br />";

        $sql = "SELECT distinct image FROM ".$prefix."_shoutbox_emoticons";
        $nameresult1 = $db->sql_query($sql);
        $flag = 1;
        $second = 0;
        while ($return = $db->sql_fetchrow($nameresult1)){
            $sql = "SELECT * FROM ".$prefix."_shoutbox_emoticons WHERE image='$return[0]' LIMIT 1";
            $nameresult = $db->sql_query($sql);
            while ($emoticons = $db->sql_fetchrow($nameresult)){
                $emoticons[3] = str_replace('>', '', $emoticons['image']);
                $emoticons[3] = str_replace('src=', 'src="', $emoticons[3]);
                $bottom_content .= "<span style=\"cursor: pointer;\" onclick=\"DoSmilie(' $emoticons[text] ','$messageDefinition');\">$emoticons[3]\" border=\"0\" alt=\"\" /></span>&nbsp;";
                if ($flag == $conf['smiliesPerRow']) {
                    $bottom_content .="<br /><br />\n";
                    $flag = 1;
                    continue;
                }
                $flag++;
            }
        }
        $bottom_content .= "</div></div></td></tr>\n";

        $bottom_content .= "</table></form>\n";
    }

    } else {
        $top_content = "<p class=\"title\" align=\"center\"><strong>";
        $mid_content = ""._YOUAREBANNED."";
        $bottom_content = "</strong></p>";
    }

    $sql = "SELECT * FROM ".$prefix."_shoutbox_conf";
    $resultsize = $db->sql_query($sql);
    $rowsize = $db->sql_fetchrow($resultsize);
    $ShoutMarqueeheight = $rowsize['height'];

}

$ShoutComment = (isset($_POST['ShoutComment'])) ? $_POST['ShoutComment'] : '';
$ShoutSubmit = (isset($_POST['ShoutSubmit'])) ? $_POST['ShoutSubmit'] : '';
$shoutuid = (isset($_POST['shoutuid'])) ? $_POST['shoutuid'] : '';

switch($ShoutSubmit) {
    default:
    ShoutBox($ShoutSubmit, $prefix, $ShoutComment, $db, $user, $cookie, $shoutuid);
    break;
}

?>
<script type="text/javascript">
var SBheight = '<?php echo "$ShoutMarqueeheight"; ?>';
var SBcontent = new String('<?php echo "$mid_content"; ?>');
</script>
<script type="text/javascript" src="shoutbox.js"></script>
<?php

$content = "$top_content\n";
$content .= "<div align=\"center\"><script type=\"text/javascript\">document.write(SBtxt);</script></div>\n";
$content .= "$bottom_content\n";

?>