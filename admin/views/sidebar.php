<?php
require_once ROOT."libraries/plugins/admin-ui/ui-sidebar.php";
$ui_sidebar = new CMSUISidebar();

$system = check_system('system_type');
$system_type = isset($system['option_value']) ? $system['option_value'] : 'CMS';

$system_option = array(
	"system-type" => $system_type,
	);

$ui_sidebar->generateMenu( $system_option );
