<?php

require 'config.php';
/*echo PLUGINS;*/

/*Load plugin files.*/
foreach (glob(PLUGINS . '*', GLOB_ONLYDIR) as $plugin_folder) {
    foreach (glob($plugin_folder . '/libraries/*.php') as $plugin_library) {  	
        require_once $plugin_library;
    }
}

$classes = LIBS;

$rows = explode('/',$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
if(in_array("includes", $rows))
	$classes = __DIR__.'/'.LIBS;

foreach (glob($classes. '*.php') as $class) {
    require_once $class;
}

// Import the extra function os each plugins
$_system_plugin_dir = scandir(__DIR__ . "/system_plugins");
$_system_plugin_dir = array_diff($_system_plugin_dir, array('.', '..'));
$_system_type = get_system_option('system_type');

foreach ($_system_plugin_dir as $key => $value) {
  if (($value == 'ecommerce' && $_system_type != 'ECOMMERCE') || ($value == 'ecatalog' && $_system_type != 'ECATALOG') ) {
    continue;
  }

  if (is_file(__DIR__ . "/system_plugins/".$value."/libraries/Library.php")) {
    require_once __DIR__ . "/system_plugins/".$value."/libraries/Library.php";
  }

	if (is_dir(__DIR__ . "/system_plugins/".$value."/frontend/libraries/extra-functions")) {
		foreach (glob( __DIR__ . "/system_plugins/".$value."/frontend/libraries/extra-functions/*.php") as $_plugin) {
			if (strpos($_plugin, "url-checker-2")) {
				continue;
			}
			require_once $_plugin;
		}
	}
}

$_active_theme_ = get_system_option(array("option_name"=>"frontend_theme"));
$_active_theme_ = $_active_theme_ != "" ? $_active_theme_ : ACTIVE_THEME;

include ROOT . '/views/themes/' . $_active_theme_ . '/cms-functions.php';


/* Initializing */
initialize_widget();