__This module provides SQL integration with [WordPress](https://wordpress.com) tables__


Add the following line in src/modules.php

include "src/wordpress/wordpress.php";


Add the following line in your gateway.config.php according to your wordpress tables prefix

'wp-prefix' => 'wp_',

restart ya bot

