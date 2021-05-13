# Valeyard IRC-SQL-GateWay
 
 A bot written in PHP which attempts to link IRC with SQL database, allowing for integration between platforms.

 So far this bot supports:
  - [Reading and writing to Anope SQLdb tables (Anope + db_sql/db_sql_live required)](https://github.com/ValwareIRC/Valeyard/tree/main/src/anope)
  - [Writing various stats and information from UnrealIRCd to its own SQL tables](https://github.com/ValwareIRC/Valeyard/tree/main/src/unrealircd)
  - [Reading and writing to WordPress SQLdb user tables (more to come)](https://github.com/ValwareIRC/Valeyard/tree/main/src/wordpress)



 ## *instructions:*

 $ `cd /home/`

 $ `git clone https://github.com/ValwareIRC/Valeyard.git`

 $ `cp example.config.php gateway.config.php`

 $ `nano gateway.config.php`

 Config should be pretty self-explanatory

 Ctrl+X, Y to save



 GateWay should always be run from a screen with non-root priviledges using PHP-CLI

 You can start a screen like this:

 $ `screen`

 You can resume the screen like this:

 $ `screen -r`

 when inside the screen, navigate to /home/Valeyard and run

 $ `php gateway.php`

 to detatch from the screen, press Ctrl+A and then Ctrl+D


## *modules*

 If you want to install any modules, you can load them from within src/module.php
 
 Example:
 `include "third/ayyy.php";`

 please add underneath core files or your module may not work as expected.
 
 *Please keep in mind this is a work in progress*

Working example: [irc.valware.uk #gateway](ircs://irc.valware.uk/#gateway)

If you are using my code, I love you soooooo MUCH.

Please feel free to leave any feedback, ideas or even advice in my email:

v.a.pond@outlook.com

Stay awesome!
