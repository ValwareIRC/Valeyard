__This module provides SQL integration with [Anope](https://anope.org) tables__


#IMPORTANT#
In order for this plugin to work correctly, you will need to have Anope installed and configured 
to use db_sql or db_sql_live. For information, [click here](https://wiki.anope.org/index.php/2.0/Modules/m_sql)

Add the following line in src/modules.php

`include "src/anope/anope.php";`


Add the following line in your gateway.config.php according to your anope tables prefix

`'anopetable' => 'anope_',`

restart ya bot

