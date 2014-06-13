<?php
/**
 * The class autoloader configuration.
 *
 * If you're not a PHP enthusiast, PHP can automatically load class definitions so you don't have to have
 *  a billion include statements at the start of every file. Unfortunately, it's a bit finicky.
 * Note that it lowercases classnames before calling the autoload function, which was a source of much
 *  unexpected pain when moving from OS X to Linux.
 */


// Your custom class dir
define('CLASS_DIR', 'classes/');

// Add your class dir to include path
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);

// You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
//spl_autoload_extensions('.class.php');

// Use default autoload implementation
spl_autoload_register('spl_autoload');
// By the docs, you shouldn't have to provide the name of the default function here (spl_autoload)
//  but the autoloader doesn't work under phpunit without it, for some reason. :/
// From: http://stackoverflow.com/a/8919306/180184
