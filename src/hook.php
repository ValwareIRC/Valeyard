<?php

// Captain hewk
class hook {

    private static $actions = array(
        'privmsg' => array(),
        'connect' => array(),
        'notice' => array(),
		'join' => array(),
		'part' => array(),
		'quit' => array(),
		'ctcp' => array(),
		'ctcpreply' => array(),
		'mode' => array(),
		'kick' => array(),
		'error' => array(),
		'auth' => array()
    );

    public static function run($hook, $args = array()) {
        if (!empty(self::$actions[$hook])) {
            foreach (self::$actions[$hook] as $f) {
                $f($args);
            }
        }
    }

    public static function func($hook, $function) {
        self::$actions[$hook][] = $function;
    }

}
?>