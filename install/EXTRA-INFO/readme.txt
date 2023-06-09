/*****[NUKE-EVOLUTION BASIC: README]*************************************
 This file contains IMPORTANT NOTES, WARRANTY and a basic introduction/
 suggestions on how to get started and how you can help improving our
 package.
 ************************************************************************\


 ========================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System RELEASE 2.0.0
 ========================================================================


 ########################################################################
 #                    I M P O R T A N T    N O T E                      #
 ########################################################################
 # PLEASE READ AND ACCEPT THE FOLLOWING USER AGREEMENT IF YOU ARE USING #
 # THIS SOFTWARE PACKAGE OR ANY OF ITS CONTENT. THANKS:                 #
 #                                                                      #
 # 01. All copyright, credit and display notices used within this       #
 #     package must remain intact.                                      #
 #                                                                      #
 # 02. All copyright, credit and display notices generated by this      #
 #     package must remain intact.                                      #
 #                                                                      #
 # 03. All copyright, credit and display notices must remain visible    #
 #     and must remain intact in all locations.                         #
 #                                                                      #
 # 04. Modification and alteration of this package is done at the users #
 #     own risk. This must only be done in compliance with all visual,  #
 #     internal display, modification and distribution terms.           #
 #                                                                      #
 # 05. To ensure authenticity when downloading any material related to  #
 #     Nuke-Evolution Basic, download all / any material from the       #
 #     official mirror sites stated below. Material downloaded from     #
 #     non-official sources may contain malicious content and is not    #
 #     supported in any way by Nuke-Evolution Basic, the                #
 #     Nuke-Evolution Team or any of the authors of Nuke-Evolution      #
 #     material including authors of included scripts and authors of    #
 #     the CMS engine:                                                  #
 #                                                                      #
 #     - http://www.nuke-evolution.com                                  #
 #                                                                      #
 # 06. All mods, blocks, modules, themes, code that was not explicitly  #
 #     created by the Nuke-Evolution team has been credited to the      #
 #     original author.                                                 #
 #                                                                      #
 # 07. Official support for every aspect of the project will only be    #
 #     provided by the Nuke-Evolution website.                          #
 #                                                                      #
 #     - http://www.nuke-evolution.com                                  #
 #                                                                      #
 # 08. For further license information please read the gpl text file     #
 #     include in "licenses and credits\Licenses\GPL"                   #
 ########################################################################


 WARRANTY:
 ---------

 Because Nuke-Evolution Basic is licensed free of charge, there is no
 warranty for the package, to the extent permitted by applicable law.
 Except when otherwise stated in writing, the Nuke-Evolution Team
 provides Nuke-Evolution "as is" without warranty of any kind, either
 expressed or implied, including, but not limited to, the implied
 warranties of merchantability and fitness for a particular purpose.
 The entire risk as to the quality and performance of the package is
 with you. Should the package prove defective, you assume the cost of
 all necessary servicing, repair or correction.
 

 PLEASE READ CAREFULLY THE FOLLOWING INSTRUCTIONS. IF YOU DO THIS,
 YOU WILL HAVE YOUR SITE UP AND RUNNING IN JUST A FEW MINUTES.
 

 Base Requirements:
 ------------------

 Please refer to the included install/1-requirements.pdf file for the
 specific requirements.


 Installing Nuke-Evolution:
 --------------------------

 Please refer to the included install/2-install.pdf file for the
 specific installation steps.


 Post Installation Setup:
 ------------------------

 Please refer to the included install/3-setup.pdf file for the
 specific post installation steps.


 Supplementaries:
 ----------------

 In the supplementary folder we have collected and premodded some useful
 and popular add-ons to further improve your website. Please refer to the
 specific supplementary add-ons which you like to install and read the
 provided installation files.


 Theme Conversion:
 -----------------

 In order to use your favorite PHP-Nuke theme you must convert it to work
 with Nuke-Evolution. We have provided instructions on how to do this in the
 theme edits folder.


 Upgrade:
 --------

 Please refer to the included readme.txt file located in the upgrades folder.


 Security:
 ---------

 One of our major goals is to provide a web portal system with the latest
 security fixes and speed improvements known to date. To reach that goal
 we have tried our best to fix any known security holes.

 SECURITY TIP: It's a good idea to put your config.php file outside the
 Web Server path if you have the option to do so. If you cannot add it
 outside you may still want to move it to another folder within your root
 directory. Assumed you have created a folder inside your root directory
 called config, you can create a new config.php with the following lines
 and place it into the root directory. Be sure to move your original
 config.php to the config folder first:


<?php

if (stristr(htmlentities($_SERVER['PHP_SELF']), "config.php")) {
    Header("Location: index.php");
    die();
}

include("config/config.php");

?>


 Help:
 -----

 In the help folder you will find various important and useful guides and
 help documentation to get most out of Nuke-Evolution. Be sure to read
 them carefully as it will answer a lot of questions.
 
 
 Licenses and Credits:
 ---------------------
 
 We have tried to mention all licenses, authors, porters and other people
 who have contributed in any way. Please refer to the specific licenses
 and credits files located in the licenses and credits folder.
 

 Support / Feedback / Bugs / Suggestions / Improvements:
 -------------------------------------------------------

 Due to agreements with the original authors of all the included mods,
 modules and blocks, support will NOT be given by the original authors.
 
 PLEASE DO NOT ASK THEM for any type of support!

                     --------------------------

 We are always trying to improve our package and therefore we need your
 feedback to provide a better package. Feel free to post feedback,
 suggestions and other ideas on our forums.
 
 If you come across a bug within the provided package, please do not
 hesitate to contact us. Make sure you can explain the problem as
 detailed as possible in order to make it easier for us to find a
 solution for you and every one else.

                     --------------------------
 
 Thanks for using our enhanced PHP-Nuke Web Portal System and we hope you
 enjoy it :)

 Nuke-Evolution Team
 http://www.nuke-evolution.com