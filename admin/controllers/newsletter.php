<?php
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/Curl.php";
include ROOT . "libraries/plugins/php-curl-class-master/src/Curl/CaseInsensitiveArray.php";

use Curl\Curl;

class Newsletter extends Controller{

    public $item_type;
    public $api_key;

    function __construct(){
        parent::__construct();
        Session::handleLogin();
        $this->item_type = 'newsletter-email';
        $this->set_api_key();
    }

    function index(){
        $this->view->render('header');
        $this->view->render('newsletter/index');
        $this->view->render('footer');
    }
    function mailchimp(){
        $this->view->set('js_files',array("newsletter-mailchimp"));
        $this->view->render('header');
        $this->view->render('newsletter/mailchimp');
        $this->view->render('footer');
    }
    function processor(){
        if (isPost('action')) {
            if (post('action') == 'add-email') {
                $email = post('email');
                $id = post('id');
                $email_id = $this->add_email($id, $email);
                $output = array("status" => 'error', 'data' => 0);
                if ($email_id) {
                    $output = array("status" => 'success', 'data' => $email_id);
                }
                echo json_encode($output);
            }elseif (post('action') == 'delete-email') {
                $id = post('id');
                $result = $this->model->db->query("Delete From `cms_items` Where `id` = '{$id}' and type = '{$this->item_type}'");
                echo $result;
            }elseif (post('action') == 'load-api-key') {
                echo $this->load_mailchimp_api_key();
            }elseif (post('action') == 'load-settings') {
                echo json_encode($this->load_mailchimp_settings());
            }elseif (post('action') == 'fresh-mailchimp-list') {
                $this->mailchimp_reset_cache_list();
                echo "refreshed";
            }elseif (post('action') == 'save-newsletter-enable-status') {
                $enable_status = post('status');
                $newsletter_id = post('id');
                $result = $this->save_mailchimp_newsletter_status( $newsletter_id, $enable_status );
                echo $result;
            }elseif (post('action') == 'save-mailchimp-settings') {                
                $data = json_decode(post('data'));
                $api_key = post('key');

                $reset_list = $this->load_mailchimp_api_key() != $api_key ? true : false;

                $api_result = $this->save_mailchimp_option( 'mailchimp-api-key', $api_key );
                $label_result = $this->save_mailchimp_option( 'mailchimp-checkbox-label', $data->subscription_label );
                $check_default_result = $this->save_mailchimp_option( 'mailchimp-checkbox-default', $data->precheck );
                $autoupdate_result = $this->save_mailchimp_option( 'mailchimp-autoupdate', $data->autoupdate );

                if ($reset_list) {
                    $this->set_api_key( $api_key );
                    $this->mailchimp_reset_cache_list();
                }

                echo json_encode(array(
                    'key' => $api_result,
                    'label' => $label_result,
                    'default' => $check_default_result,
                    'autoupdate' => $autoupdate_result,
                ));
            }elseif (post('action') == 'save-api-key') {
                $api_key = post('key');
                $result = $this->save_mailchimp_api_key( $api_key );
                echo $result;
            }
        }elseif(isGet('action')){
            if (get('action') == 'load-subscribers') {
                $this->load_newsletter_table();
            }elseif (get('action') == 'load-mailchimp-list') {
                $this->load_mailchimp_list_table();
            }
        }
    }

    private function set_api_key( $api_key = "" ){
        if (isset($api_key) && $api_key !="") {
            $this->api_key = $api_key;
        }else{
            $this->api_key = $this->load_mailchimp_api_key();
        }
    }
    private function load_mailchimp_api_key(){
        return get_system_option(array("option_name" => "mailchimp-api-key"));
    }
    private function load_mailchimp_settings(){
        $return = array(
            "api_key" => get_system_option(array("option_name" => "mailchimp-api-key")),
            "auto_update" => get_system_option(array("option_name" => "mailchimp-autoupdate")),
            "checkbox_label" => get_system_option(array("option_name" => "mailchimp-checkbox-label")),
            "default_value" => get_system_option(array("option_name" => "mailchimp-checkbox-default")),
        );

        return $return;
    }
    private function load_newsletter_table(){
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => 1,
            "iTotalDisplayRecords" => 1,
            "aaData" => array()
            );

        $sql = "Select * From `cms_items` Where `type` = '{$this->item_type}'";

        $columns = array(
            'id',
            'value',
            'meta',
            );

        $output = datatable_processor($columns, "id", "", $sql);

        foreach($output['aaData'] as $kk=>$vv){
            $output['aaData'][$kk][0] = '<div class="text-center"><label><input type="checkbox" class="item-checkbox ace" data-value="'. $vv[0] .'"><span class="lbl"></span></label></div>';
            $output['aaData'][$kk][1] = $vv[1];

            $output['aaData'][$kk][2] = '<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
            <button class="btn btn-minier btn-info table-btn-edit-email" data-rel="tooltip" data-placement="top" title="Newsletter" data-value="'. $vv[0] .'" data-email="'.$vv[1].'"><i class="icon-edit bigger-120"></i></button>
            <button class="btn btn-minier btn-danger table-btn-delete-email" data-rel="tooltip" data-placement="top" title="Delete" data-value="'. $vv[0] .'"><i class="icon-trash bigger-120"></i></button></div>';
        }

        echo json_encode($output);
    }
    private function load_mailchimp_list_table(){
        $output = array(
            "sEcho" => 1,
            "iTotalRecords" => 1,
            "iTotalDisplayRecords" => 1,
            "aaData" => array()
            );

        $sql = "Select * From `cms_items` Where `type` = 'mailchimp-list'";

        $columns = array(
            'id',
            'value',
            'meta',
            'status',
            );

        $output = datatable_processor($columns, "id", "", $sql);

        foreach($output['aaData'] as $kk=>$vv){
            $is_checked = $vv[3] == "Yes" ? 'checked=""' : '';

            $output['aaData'][$kk][0] = '<div class="text-center"><label><input type="checkbox" class="item-checkbox ace" data-value="'. $vv[0] .'" '.$is_checked.' ><span class="lbl"></span></label></div>';
            $output['aaData'][$kk][1] = $vv[1];

            $meta_data = json_decode($vv[2]);
            $output['aaData'][$kk][2] = isset($meta_data->id) ? $meta_data->id : "";
            $output['aaData'][$kk][3] = isset($meta_data->subscribers) ? $meta_data->subscribers : "0";
        }

        echo json_encode($output);
    }
    private function add_email($email_id=0, $email_address = ""){
        if ($email_address == "") {
            return 0;
        }

        $this->model->db->table = 'cms_items';

        if ($email_id == 0) {
            $email_id = $this->model->save(array(
                "type" => $this->item_type,
                "value" => $email_address,
                "meta" => "",
                ));
        }else{
            $email_id = $this->model->update(array(
                "id" => $email_id,
                "type" => $this->item_type,
                "value" => $email_address,
                "meta" => "",
                ));
        }

        return $email_id;
    }
    private function save_mailchimp_option($key="",$value=""){
        if ($key == '') { return false; }

        $sql = "INSERT INTO `system_options` (`option_name`, `option_value`, `meta_data`, `auto_load`) VALUES ('{$key}','{$value}','','no') ON DUPLICATE KEY UPDATE `option_value`='{$value}'";

        $result =  $this->model->db->query( $sql );
        return $result;
    }
    private function save_mailchimp_api_key($key = ""){
        $result =  $this->save_mailchimp_option('mailchimp-api-key', $key);

        $this->set_api_key( $key );
        $this->mailchimp_reset_cache_list();

        return $result;
    }
    private function save_mailchimp_newsletter_status($newsletter_id = "", $enable_status = ""){
        if ($newsletter_id == "") { return; }

        $this->model->db->table = 'cms_items';

        $result = $this->model->update(array(
            "id" => $newsletter_id,
            "status" => $enable_status == "Yes" ? "Yes" : "No",
        ));

        return $result;
    }
    private function mailchimp_get_lists(){
        $api_key = $this->api_key;
        $_temp = explode('-', $api_key);
        $data_center = isset($_temp[1]) ? $_temp[1] : '';
        $mailchimp_url = "https://{$data_center}.api.mailchimp.com/3.0";
        $mailchimp_url_command = "/lists?fields=lists.name,lists.id,lists.stats.member_count";

        $curl = new Curl();
        $curl->setBasicAuthentication('cmstestuser', $api_key);
        $curl->setHeader('Content-Type', 'application/json');

        $url = $mailchimp_url . $mailchimp_url_command;

        $curl->get( $url );

        $output = array();

        if (!$curl->error) {
            $output = isset($curl->response->lists) ? $curl->response->lists : array();
        }

        return $output;
    }
    private function mailchimp_reset_cache_list(){
        $this->model->db->query("Delete From `cms_items` Where `type`='mailchimp-list'");

        $new_lists = $this->mailchimp_get_lists();
        $this->model->db->table = "cms_items";
        foreach ($new_lists as $key => $value) {
            $meta = array(
                "id" => $value->id,
                "name" => $value->name,
                "subscribers" => $value->stats->member_count,
            );
            $data = arraY(
                "type" => 'mailchimp-list',
                "value" => $value->name,
                "meta" => json_encode($meta),
                "status" => "No",
            );
            $this->model->db->data = $data;
            $new_id = $this->model->save( $data );
        }

        return 'done';
    }
}
