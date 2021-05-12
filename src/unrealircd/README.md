__This module provides SQL regularly updated stats for unrealircd__

_Tables_
- channel (channel information)
- gstats (glines)
- shuns
- cmdstat (command statistics)
- stats (general server stats)


Add the following line in src/modules.php

include "src/unrealircd/unrealircd.php";



Add the following line in your gateway.config.php

'unrealtable' => 'unreal_',


restart ya bot

