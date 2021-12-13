<?php

class Patcher extends Controller{
    /*
    COMMAND GUIDE

    type: datatable
    command: [SQL]
    description: [text]

    type: file
    command: [add/delete/download] [add_file_location/delete_file_location/source_location] [-/-/destination_location]
    description: [text]

    type: folder
    command: [add/delete] [directory_location]
    description: [text]
    */

    // public $meta_structure = array(
    //     array(
    //         'type' => "database",
    //         'command' => "Insert Into `menus` (`name`) Values ('Main Menu Link 2')",
    //         'description' => "Adding Menu"
    //     ),
    //     array(
    //         'type' => "file",
    //         'command' => "delete temp/patch.php",
    //         'description' => "Deleting a file"
    //     ),
    //     array(
    //         'type' => "folder",
    //         'command' => "add temp/samplefile3",
    //         'description' => "Insert Into `menus` (`name`) Values ('Main Menu Link 2')"
    //     ),
    // );
    public $sample_patch = array(
        //BACKUP
        array(
            'type' => "file",
            'command' => "backup admin\assets\js\controllers\pages.js",
            'description' => "backup: admin\assets\js\controllers\pages.js",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\views\pages\edit.php",
            'description' => "backup: admin\views\pages\edit.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\controllers\pages.php",
            'description' => "backup: admin\controllers\pages.php",
        ),
        array(
            'type' => "file",
            'command' => "backup controllers\page.php",
            'description' => "backup: controllers\page.php",
        ),
        array(
            'type' => "file",
            'command' => "backup libraries\CMS_Functions.php",
            'description' => "backup: libraries\CMS_Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\models\pages_model.php",
            'description' => "backup: admin\models\pages_model.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\libraries\Functions.php",
            'description' => "backup: admin\libraries\Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "backup libraries\Functions.php",
            'description' => "backup: libraries\Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "backup libraries\Url.php",
            'description' => "backup: libraries\Url.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\views\footer.php",
            'description' => "backup: admin\views\footer.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\controllers\settings.php",
            'description' => "backup: admin\controllers\settings.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\models\settings_model.php",
            'description' => "backup: admin\models\settings_model.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\views\general-settings\general-settings.php",
            'description' => "backup: admin\views\general-settings\general-settings.php",
        ),
        array(
            'type' => "file",
            'command' => "backup admin\assets\js\controllers\settings-permalink.js",
            'description' => "backup: admin\assets\js\controllers\settings-permalink.js",
        ),

        //DOWNLOAD
        array(
            'type' => "file",
            'command' => "download admin\assets\js\controllers\pages.js admin\assets\js\controllers\pages.js",
            'description' => "Download: admin\assets\js\controllers\pages.js",
        ),
        array(
            'type' => "file",
            'command' => "download admin\views\pages\edit.php admin\views\pages\edit.php",
            'description' => "Download: admin\views\pages\edit.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\controllers\pages.php admin\controllers\pages.php",
            'description' => "Download: admin\controllers\pages.php",
        ),
        array(
            'type' => "file",
            'command' => "download controllers\page.php controllers\page.php",
            'description' => "Download: controllers\page.php",
        ),
        array(
            'type' => "file",
            'command' => "download libraries\CMS_Functions.php libraries\CMS_Functions.php",
            'description' => "Download: libraries\CMS_Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\models\pages_model.php admin\models\pages_model.php",
            'description' => "Download: admin\models\pages_model.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\libraries\Functions.php admin\libraries\Functions.php",
            'description' => "Download: admin\libraries\Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "download libraries\Functions.php libraries\Functions.php",
            'description' => "Download: libraries\Functions.php",
        ),
        array(
            'type' => "file",
            'command' => "download libraries\Url.php libraries\Url.php",
            'description' => "Download: libraries\Url.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\views\footer.php admin\views\footer.php",
            'description' => "Download: admin\views\footer.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\controllers\settings.php admin\controllers\settings.php",
            'description' => "Download: admin\controllers\settings.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\models\settings_model.php admin\models\settings_model.php",
            'description' => "Download: admin\models\settings_model.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\views\general-settings\general-settings.php admin\views\general-settings\general-settings.php",
            'description' => "Download: admin\views\general-settings\general-settings.php",
        ),
        array(
            'type' => "file",
            'command' => "download admin\assets\js\controllers\settings-permalink.js admin\assets\js\controllers\settings-permalink.js",
            'description' => "Download: admin\assets\js\controllers\settings-permalink.js",
        ),
    );

    public $baseurl;

    function __construct(){
        header_json();
        $this->baseurl = URL . "/";
    }
    function index(){
        header_json(); print_r(serialize($this->sample_patch)); exit();
    }
    function download(){
        if (isGet("action")) {
            if (get('action') == 'get') {
                if (isGet('type')){
                    if (get('type') == 'file') {
                        if (isGet('file')) {
                            $file = ROOT . trim(get('file'), "/");
                            if (is_file($file)) {
                                // header("Content-Type: text/html");
                                $myfile = fopen($file, "r") or die("Unable to open file!");
                                echo fread($myfile,filesize($file));
                                fclose($myfile);
                            }
                        }
                    }
                }
            }
        }
    }
    function get(){
        $last_update_id = isGet('last_update_id') ? get('last_update_id') : 0;

        $updates = $this->get_updates( $last_update_id );
        $output = array("count"=> count($updates), "detail" => $updates);

        echo json_encode( $output );
    }

    function get_updates($patch_id = 0){
        $available_patch = $this->model->db->select("Select * From `patch` Where `id` > '{$patch_id}'");
        return $available_patch;
    }
}