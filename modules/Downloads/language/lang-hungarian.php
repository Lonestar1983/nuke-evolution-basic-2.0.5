<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/********************************************************/
/* Evo Downloads module                                 */
/* By: Quake (quake2005@gmail.com)                      */
/* http://www.nuke-evolution.com                        */
/********************************************************/
/* Based on NSN GR Downloads                            */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright � 2000-2005 by NukeScripts Network         */
/********************************************************/

global $sitename;

define("_MODEXTENSION","Kiterjeszt�s m�dos�t�s");
define("_EXT","Kiterjeszt�s");
define("_1WEEK","1 h�t");
define("_2WEEKS","2 h�t");
define("_30DAYS","30 nap");
define("_ACCEPT","Elfogadom");
define("_ACTIVE","Akt�v");
define("_ADDCATEGORY","Kateg�ri�t hozz�ad");
define("_ADDDOWNLOAD","Let�lt�st hozz�ad");
define("_ADDED","Hozz�ad");
define("_ADDEDON","Hozz�adva");
define("_ADDEXTENSION","Kiterjeszt�st hozz�ad");
define("_ADDMAINCATEGORY","Kateg�ri�t hozz�ad");
define("_ADDNEWDOWNLOAD","�j let�lt�s hozz�ad");
define("_ADDSUBCATEGORY","Alkateg�ri�t hozz�ad");
define("_ADMADMPERPAGE","T�telek sz�ma az admin list�kban:");
define("_ADMBLOCKUNREGMODIFY","Vend�gek javasolhatnak m�dos�t�st:");
define("_ADMMOSTPOPULAR","N�pszer� t�telek megjelen�t�si sz�ma:");
define("_ADMMOSTPOPULARTRIG","N�pszer� t�telek megjelen�t�se:");
define("_ADMPERPAGE","T�telek sz�ma az oldalakon:");
define("_ADMPOPULAR","N�pszer�s�ghez sz�ks�ges tal�latok sz�ma:");
define("_ADMRESULTS","T�telek sz�ma a kers�lapon:");
define("_ADMSHOWDOWNLOAD","Let�lt�sek mutat�s mindenkinek:");
define("_ADMSHOWNUM","T�telek megjelen�t�se a kateg�ri�kn�l:");
define("_ADMUSEGFX","Biztons�gi k�d haszn�lata:");
define("_ALREADYEXIST","m�r l�tezik!");
define("_AUTHOR","Szerz�");
define("_AUTHOREMAIL","Szerz� e-mail c�me");
define("_AUTHORNAME","Szerz� neve");
define("_BEPATIENT","(k�rem l�gy t�relmes)");
define("_BROKENDOWNLOADSREP","Hib�s link");
define("_BROKENREP","Hiba jelent�s");
define("_BYTES","b�jt");
define("_CATEGORIES","Kateg�ri�k");
define("_CATEGORIESADMIN","Kateg�ria adminisztr�ci�");
define("_CATEGORIESLIST","Kateg�ri�k list�ja");
define("_CATEGORY","Kateg�ria");
define("_CATTRANS","Kateg�ria �tvitel");
define("_CHECK","Ellen�rz�s");
define("_CHECKALLDOWNLOADS","Let�lt�sek ellen�rz�se");
define("_CHECKCATEGORIES","Kateg�ri�k ellen�rz�se");
define("_CHECKFORIT","Nem adtad meg az email c�med, de a linked hamarosan ellen�rizni fogjuk.");
define("_DATE","D�tum");
define("_DATEADD","Hozz�adva");
define("_DAYS","nap");
define("_DCATLAST2WEEKS","�j let�lt�sek ebben a kateg�ri�ban az elm�lt 2 h�tben");
define("_DCATLAST3DAYS","�j let�lt�sek ebben a kateg�ri�ban az elm�lt 3 napban");
define("_DCATNEWTODAY","�j let�lt�sek ebben a kateg�ri�ban a mai napon");
define("_DCATTHISWEEK","�j let�lt�sek ebben a kateg�ri�ban a h�ten");
define("_DDATE1","R�gebbi let�lt�sek fel�l");
define("_DDATE2","R�gebbi let�lt�sek alul");
define("_DDELETEINFO","T�rl�s (T�rli a <b><i>hib�s let�lt�st</i></b> �s a let�lt�sre vonatkoz� <b><i>k�r�seket</i></b>)");
define("_DELETEDOWNLOAD","Let�lt�s t�rl�s");
define("_DELEXTSURE","!!!!FIGYELEM!!!!<br>Biztosan t�r�lni akarod ezt a kiterjeszt�st?");
define("_DELEZDOWNLOADSCATWARNING","!!!!FIGYELEM!!!!<br>Biztosan t�r�lni akarod ezt a kateg�ri�t?<br>T�rl�d az alkateg�ri�t valamint a hozz� kapcsolod� let�lt�seket is!");
define("_DESCRIPTION","Le�r�s");
define("_DIGNOREINFO","Elutas�t (T�rli az �sszes let�lt�si <b><i>k�relmet</i></b>)");
define("_DL_ACTIVATE","Aktiv�l");
define("_DL_ADD","K�ldj let�lt�st");
define("_DL_ADMIN","Adminisztr�torok");
define("_DL_ALL","Minden l�togat�");
define("_DL_ANON","Csak Vend�gek");
define("_DL_APPROVEDMSG","Elk�ld�tt let�lt�st elfogadtuk!");
define("_DL_BACKTO","Vissza");
define("_DL_CANBEDOWN","F�jlt let�lthetik: ");
define("_DL_CANBEVIEW","F�jlt megtek�nthetik:");
define("_DL_CANUPLOAD","Felt�lt�si enged�ly:");
define("_DL_DATEFORMAT","D�tum form�tum");
define("_DL_DATEMSG","A haszn�lt szintaxis azonos a PHP <a href='http://www.php.net/date' target='_new'>date()</a> funkci�val");
define("_DL_DBCONFIG","Adatb�zis hiba. K�rlek �rtes�tsd a webmestert konfigur�lja a let�lt�seket!");
define("_DL_DEACTIVATE","Deaktiv�l");
define("_DL_DELETE","T�rl�s");
define("_DL_DENIED","F�jl hozz�f�r�s megtagadva");
define("_DL_DIRECTIONS","<b>�tmutat�:</b>");
define("_DL_DLNOTES1","\"<b>");
define("_DL_DLNOTES2","</b>\" megnevez�s� f�jl let�lt�s�hez az �res mez�be �rd be a k�dot, majd kattints a let�lt�s gombra. N�h�ny pillanat m�lva elindul a let�lt�s, vagy a let�lt�shez tartoz� oldal.");
define("_DL_DN","Let�lt�sek"); 
define("_DL_EDIT","Szerkeszt�s");
define("_DL_ERROR","Hiba");
define("_DL_FILES","f�jlok"); 
define("_DL_FILESDL","let�lt�tt f�jlok"); 
define("_DL_FLAGGED","Ezt a let�lt�st automatikusan jel�lt�k webmester fel�lvizsg�latra.");
define("_DL_FNF","Keresett f�jl nincs:");
define("_DL_FNFREASON","Mivel a let�lt�si f�jlt �tnevezt�k, vagy t�r�lt�k.");
define("_DL_GOGET","Let�lt�s");
define("_DL_INVALIDPASS","�rv�nytelen k�d.");
define("_DL_INVALIDURL","Nem megfelel� az url.");
define("_DL_LEGEND","Jelmagyar�zat");
define("_DL_NEXT","K�vetkez� oldal >>");
define("_DL_NO","Nem");
define("_DL_NONE","Nincs");
define("_DL_NONEXT","Nincs k�vetkez� oldal");
define("_DL_NOPREVIOUS","Nincs el�z� oldal");
define("_DL_NOTFOUND","nem tal�lom.");
define("_DL_NOTLIST","Nem felt�ntetett");
define("_DL_OF","-");
define("_DL_OK","Rendben");
define("_DL_ONLY","Csak");
define("_DL_PAGE","oldal");
define("_DL_PAGES","oldal");
define("_DL_PASSERR","K�d hiba");
define("_DL_PERM","Hozz�f�r�si jog");
define("_DL_PREVIOUS","<< El�z� oldal");
define("_DL_RESTRICTED","F�jl hozz�f�r�s korl�tolt");
define("_DL_SEARCH","Keres�s");
//define("_DL_SELECTPAGE","Page");
define("_DL_SELECTPAGE","V�lassz oldalt");
define("_DL_SORRY","Eln�z�st");
define("_DL_TDN","�sszes let�lt�s");
define("_DL_TIMES","id�"); 
define("_DL_TYPEPASS","K�d");
define("_DL_UADD","Felhaszn�l�k tudnak hozz�adni");
define("_DL_UP","Felt�lt�sek"); 
define("_DL_URLERR","URL Hiba");
define("_DL_USERS","Regisztr�lt felhaszn�l�k");
define("_DL_WHOADD","Bek�ld�si enged�ly");
define("_DL_YES","Igen");
define("_DL_YOURPASS","K�d");
define("_DNODOWNLOADSWAITINGVAL","Nincs �rv�nyes�t�sre v�r� let�lt�s");
define("_DNOREPORTEDBROKEN","Nincs let�lt�si hiba jelent�s.");
define("_DONLYREGUSERSMODIFY","Csak regisztr�lt felhaszn�l�k javasolhatnak let�lt�s m�dos�t�st . K�rlek <a href=\"modules.php?name=Your_Account\">regisztr�lj �s jelentkezz be</a>.");
define("_DOWNCONFIG","Let�lt�sek konfigur�l�sa");
define("_DOWNLOAD","Let�lt�s");
define("_DOWNLOADALREADYEXT","HIBA: Ezt a linket m�r tartalmazza az adatb�zis!");
define("_DOWNLOADAPPROVEDMSG","J�v�gytuk a beny�jtott let�lt�sed.");
define("_DOWNLOADID","F�jl sz�m");
define("_DOWNLOADMODREQUEST","Let�lt�s m�dos�t�si k�relem");
define("_DOWNLOADNODESC","HIBA: Nem adtad meg a let�lt�s le�r�s�t!");
define("_DOWNLOADNOTITLE","HIBA: Nem adt�l megnevez�st a let�lt�snek!");
define("_DOWNLOADNOURL","HIBA: Nem adt�l meg URL c�met!");
define("_DOWNLOADNOW","T�ltsd le ezt a f�jlt most!");
define("_DOWNLOADOWNER","Szerz�");
define("_DOWNLOADPROFILE","Let�lt�s");
define("_DOWNLOADRECEIVED","Megkaptuk az �ltalad hozz�adott let�lt�st. K�sz�nj�k!");
define("_DOWNLOADS","Let�lt�sek");
define("_DOWNLOADSADMIN","Let�lt�s adminisztr�ci�");
define("_DOWNLOADSINDB","Let�lt�sek az adatb�zisunkban");
define("_DOWNLOADSLIST","Let�lt�sek list�ja");
define("_DOWNLOADSMAIN","Let�lt�sek");
define("_DOWNLOADSMAINCAT","Let�lt�si f� kateg�ri�k");
define("_DOWNLOADSMAINTAIN", "Let�lt�sek karbantart�sa");
define("_DOWNLOADSWAITINGVAL","�rv�nyes�t�sre v�r� let�lt�s");
define("_DOWNLOADVALIDATION","Let�lt�s �rv�nyes�t�s");
define("_DSCRIPT","Let�lt�s Script");
define("_DSUBMITONCE","Egy let�lt�st csak egyszer k�ldj el!");
define("_DTOTALFORLAST","�j let�lt�sek a legut�bbi");
define("_DUSERMODREQUEST","Felhaszn�l� let�lt�s m�dos�t�si k�relem");
define("_DUSERREPBROKEN","Felhaszn�l� jelent�s hib�s let�lt�sekr�l");
define("_ERRORNODESCRIPTION","HIBA: Nem adtad meg a let�lt�s le�r�s�t!");
define("_ERRORNOTITLE","HIBA: Nem adt�l megnevez�st a let�lt�snek!");
define("_ERRORNOURL","HIBA: Nem adt�l meg URL c�met!");
define("_ERRORTHECATEGORY","Hiba: A kateg�ri�ban");
define("_ERRORTHESUBCATEGORY","Hiba: Az alkateg�ri�ban");
define("_ERRORURLEXIST","HIBA: Ezt a linket m�r tartalmazza az adatb�zis!");
define("_EXTENSION","Kiterjeszt�s");
define("_EXTENSIONS","Kiterjeszt�sek");
define("_EXTENSIONSADMIN","Kiterjeszt�sek Adminisztr�l�sa");
define("_EXTENSIONSLIST","Kiterjeszt�sek list�ja");
define("_EZATTACHEDTOCAT","kateg�ria alatt ");
define("_EZSUBCAT","alkateg�ri�k");
define("_EZTHEREIS","Ott van");
define("_EZTRANSFER","�tvitel");
define("_EZTRANSFERDOWNLOADS","�sszes let�lt�s �tvitele a kateg�ri�b�l.");
define("_FAILED","Sikertelen!");
define("_FILENAME","F�jl n�v");
define("_FILESIZE","F�jl m�ret");
define("_FILESIZEVALIDATION","F�jl m�ret �rv�nyes�t�s");
define("_FILETYPE","F�jl t�pus");
define("_FROM","Eredet");
define("_FUNCTIONS","Funkci�");
define("_GB","Gb");
define("_GENECONFIG","�ltal�nos be�ll�t�s");
define("_HELLO","Hello");
define("_HITS","Tal�latok");
define("_HOMEPAGE","Honlap");
define("_IGNORE","Elutas�t");
define("_IMAGETYPE","K�p t�pus");
define("_INBYTES","b�jt-ban");
define("_INCLUDESUBCATEGORIES","(alkateg�ri�k is)");
define("_INDB","az adatb�zisunkban");
define("_INVALIDDOWNLOAD","Let�lt�s azonos�t� �rv�nytelen.");
define("_KB","Kb");
define("_LAST30DAYS","Elm�lt 30 nap");
define("_LASTWEEK","M�lt heti");
//define("_LINKSDATESTRING","%Y-%b-%d");
define("_LINKSDATESTRING","%d-%b-%Y");
define("_LOOKTOREQUEST","R�vid id�n bel�l megvizsg�ljuk.");
define("_MAIN","Let�lt�sek");
define("_MAINADMIN","Adminisztr�ci�s oldal");
define("_MB","Mb");
define("_MODCATEGORY","Kateg�ria m�dos�t�s");
define("_MODDOWNLOAD","Let�lt�s m�dos�t�s");
define("_MODIFY","M�dos�t�s");
define("_MODREQUEST","M�dos�t�si k�relem");
define("_MOSTPOPULAR","N�pszer� - Top");
define("_NAME","N�v");
define("_NEW","�j let�lt�sek");
define("_NEWDOWNLOADADDED","�j let�lt�s hozz�adva az adatb�zishoz");
define("_NEWDOWNLOADS","�j let�lt�s");
define("_NEWLAST2WEEKS","�j az elm�lt 2 h�tben");
define("_NEWLAST3DAYS","�j az elm�lt 3 nap");
define("_NEWSIZE","�j m�ret");
define("_NEWTHISWEEK","�j ezen a h�ten");
define("_NEWTODAY","�j ma");
define("_NEXT","K�vetkez� oldal");
define("_NOCATTRANS","Ott nincsenek Kateg�ri�k az adatb�zisban.");
define("_NOMATCHES","Nincs egyez� tal�lat a keres�si k�r�snek");
define("_NOMODREQUESTS","Nincs m�dos�t�si k�relem");
define("_NONEXT","Nincs k�vetkez� oldal");
define("_NOPREVIOUS","Nincs el�z� oldal");
define("_NOTLOCAL","Nem helyi");
define("_NUMBER","Sz�m");
define("_OFALL","mind a");
define("_OK","Rendben");
define("_OLDSIZE","R�gi m�ret");
define("_ORIGINAL","Eredeti");
define("_OTHERS","Tov�bbi");
define("_OWNER","Tulajdonos");
define("_PARENT","Forr�s");
define("_PATHHIDE","El�r�si �t rejve marad");
define("_PERCENT","Sz�zal�k");
define("_POPULAR","N�pszer�");
define("_POPULARITY","N�pszer�s�g");
define("_POPULARITY1","Kev�sb� n�pszer� fent");
define("_POPULARITY2","Kev�sb� n�pszer� lent");
define("_PREVIOUS","El�z� oldal");
define("_PURPOSED","Sz�nd�k");
define("_REPORTBROKEN","Hib�s link");
define("_REQUESTDOWNLOADMOD","Let�lt�s m�dos�t�si k�relem");
define("_RESSORTED","Aktu�lis sorrend");
define("_SAVECHANGES","V�ltoz�s ment�s");
define("_SEARCHRESULTS4","Keres�si eredm�ny");
define("_SECURITYBROKEN","Biztons�gi okokb�l ideiglenesen t�roljuk a felhaszn�l�neved �s az IP c�med is.");
define("_SELECTPAGE","V�lassz oldalt");
define("_SENDREQUEST","k�r�st k�ld");
define("_SHOW","Megmutat");
define("_SHOWTOP","Megjelen�t");
define("_SORTDOWNLOADSBY","Rendez�s");
define("_STATUS","�llapot");
define("_SUBIP","Bek�ld� IP-je");
define("_SUBMITTER","Bek�ld�");
define("_TEAM","Csapat.");
define("_THANKS4YOURSUBMISSION","K�sz�nj�k a beny�jt�st!");
define("_THANKSBROKEN","K�sz�nj�k, hogy seg�tesz fenntartani a linkt�runk m�k�d�s�t.");
define("_THANKSFORINFO","K�sz�nj�k az inform�ci�t.");
define("_THEREARE","Ebben");
define("_TITLE","Megnevez�s");
define("_TITLEAZ","Megnevez�s (A to Z)");
define("_TITLEZA","Megnevez�s (Z to A)");
define("_TO","Hov�");
define("_TOTALNEWDOWNLOADS","�sszes �j let�lt�s");
define("_UNKNOWN","Ismeretlen");
define("_UPDIRECTORY","Relat�v el�r�si �t");
define("_URL","URL");
define("_USEUPLOAD","f�jl felt�lt�shez");
define("_USUBCATEGORIES","Alkateg�ria");
define("_VALIDATEDOWNLOADS","Let�lt�s �rv�nyes�t�s");
define("_VALIDATESIZES","F�jl m�ret �rv�nyes�t�s");
define("_VALIDATINGCAT","Kateg�ria �rv�nyes�t�s");
define("_VERSION","Verzi�");
define("_VISIT","megtekint");
define("_WAITINGDOWNLOADS","V�rakoz� let�lt�s");
define("_YOUCANBROWSEUS","Let�lt�sket megtek�ntheted:");
define("_YOURDOWNLOADAT","Let�lt�sed");
define("_ADDADOWNLOAD","�j Let�lt�s Hozz�ad�sa");
define("_INSTRUCTIONS","Utas�t�sok");
define("_DPOSTPENDING","�sszes bek�ld�tt let�lt�s ellen�rz�se.");
define("_USERANDIP","Felhaszn�l�i n�v �s IP  r�gzitve, ne �lj vissza a rendszerrel.");
define("_GONEXT","Tov�bb");
define("_DL_TOU","Haszn�lati felt�telek ");
define("_BAD_URLS","A let�lt�sn�l nem szabad a kiszolg�l�d URL cim�t haszn�lni.");
define("_NEWLINE","�j sor kezd�s�hez haszn�ld a");
define("_CONVERTBR","<BR> tagot");
define("_ADDTHISFILE","Hozz�ad�s");
?>