# IRC-SQL-GateWay
 
 A bot written in PHP which attempts to link IRC with SQL database, allowing for integration between platforms.

 So far only supports UnrealIRCd and the config is set up to use wordpress with profilegrid installed.

 sorry bout that! watch this space...


 ## *instructions:

 $ `cd /home/`

 $ `git clone https://github.com/ValwareIRC/IRC-SQL-GateWay.git`

 $ `cp example.config.php gateway.config.php`

 $ `nano gateway.config.php`

 Config should be pretty self-explanatory

 Ctrl+X, Y to save



 GateWay should always be run from a screen with non-root priviledges using PHP-CLI

 You can start a screen like this:

 $ `screen`

 You can resume the screen like this:

 $ `screen -r`

 when inside the screen, navigate to /home/IRC-SQL-GateWay/ and run

 $ `php gateway.php`

 to detatch from the screen, press Ctrl+A and then Ctrl+D


## *modules

 If you want to install any modules, you can load them from within src/module.php

 please add underneath core files or your module may not work as expected.
 
 *Please keep in mind this is a work in progress*
