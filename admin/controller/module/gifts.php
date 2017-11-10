<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 09.11.17
 * Time: 15:11
 */


class ControllerModuleGifts extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('module/gifts');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/jquery/jquery.mask.min.js');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('gifts', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_total'] = $this->language->get('entry_total');
        $data['entry_fee'] = $this->language->get('entry_fee');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_discount_list'] = $this->language->get('entry_discount_list');
        $data['entry_group'] = $this->language->get('entry_group');
        $data['entry_modal_threshold'] = $this->language->get('entry_modal_threshold');
        $data['entry_modal_discount'] = $this->language->get('entry_modal_discount');
        $data['entry_modal_ac'] = $this->language->get('entry_modal_ac');

        $data['title_threshold'] = $this->language->get('title_threshold');
        $data['title_discount'] = $this->language->get('title_discount');
        $data['title_action'] = $this->language->get('title_action');
        $data['title_product_list'] = $this->language->get('title_product_list');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_edit'] = $this->language->get('button_edit');

        $data['modal_title_threshold'] = $this->language->get('modal_title_threshold');
        $data['modal_title_list'] = $this->language->get('modal_title_list');

        $data['help_pd_group'] = $this->language->get('help_pd_group');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_total'),
            'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('module/gifts', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('module/gifts', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

        /*

        if (isset($this->request->post['gifts_module'])) {
            $data['gifts_total'] = $this->request->post['gifts_total'];
        } else {
            $data['gifts_total'] = $this->config->get('gifts_total');
        }

        if (isset($this->request->post['gifts_fee'])) {
            $data['gifts_fee'] = $this->request->post['gifts_fee'];
        } else {
            $data['gifts_fee'] = $this->config->get('gifts_fee');
        }

        */

        $data['gifts_threshold']='';
        $data['gifts_modal_ac']='';
        $data['title_modal']='*';

        $data['token']= $this->session->data['token'];

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        if (isset($this->request->post['gifts_status'])) {
            $data['gifts_status'] = $this->request->post['gifts_status'];
        } else {
            $data['gifts_status'] = $this->config->get('gifts_status');
        }

        /*
        if (isset($this->request->post['gifts_sort_order'])) {
            $data['gifts_sort_order'] = $this->request->post['gifts_sort_order'];
        } else {
            $data['gifts_sort_order'] = $this->config->get('gifts_sort_order');
        }

        $this->load->model('extended/gifts');
        $data['discount_list']=$this->model_extended_gifts->getDiscountList();

        $this->load->model('customer/customer_group');
        $data['groups'] = $this->model_customer_customer_group->getCustomerGroups();
        //echo '<pre>'; var_dump($data['groups']); die();

        */
        $data['modal_table_data']=$this->listData();

        $this->load->model('extended/gifts');
        $this->load->model('catalog/product');
        $results=$this->model_extended_gifts->getDiscountList();

        $discount_list=[];
        foreach ($results as $result) {

            $threshold_info=$this->model_extended_gifts->getThresholdData($result['threshold_id']);

            if($threshold_info) {

                $list = '';
                foreach ($threshold_info as $item) {
                    $product_info = $this->model_catalog_product->getProduct($item['product_id']);
                    if($product_info) {
                        $list .= '<span style="display: block">' . $product_info['name'] . '</span>';
                    }
                }

                $discount_list[] = [
                    'threshold_id' => $result['threshold_id'],
                    'threshold' => $result['threshold'],
                    'list' => $list
                ];
            }
        }

        $data['discount_list']=$discount_list;





        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/gifts.tpl', $data));
    }

    public function listData($threshold_id=0) {

        $text_no_results = $this->language->get('text_no_results');

        $output='';

        $this->load->model('extended/gifts');
        $this->load->model('catalog/product');

        if(!isset($this->session->data['modal_data_list'])) {

            $this->session->data['modal_data_list']=[];

            $results = $this->model_extended_gifts->getProductsByThresholdId($threshold_id);

            if($results) {
                foreach ($results as $result) {
                    $product_info = $this->model_catalog_product->getProduct($result['product_id']);
                    if($product_info) {
                        $this->session->data['modal_data_list'][] = [
                            'name' => $product_info['name'],
                            'product_id' => $product_info['product_id']
                        ];
                    }
                }
            }
        }

        if($this->session->data['modal_data_list']) {

            foreach ($this->session->data['modal_data_list'] as $item) {
                $output .= '<tr>';
                $output .= '<td>'.$item['name'];
                $output .= '<span class="pull-right"><a onclick="delGift(\''.$item['product_id'].'\');"><i class="fa fa-times-circle" aria-hidden="true" style="font-size: 18px;color:#ff4e28;"></i></a></span></td>';
                $output .= '</tr>';
            }
        } else {
            $output.='
                <tr>                    
                    <td class="text-center" colspan="1">'.$text_no_results.'</td>
                </tr>';
        }

        return $output;
    }

    public function add() {

        $json=[];
        $error='';

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $threshold = isset($this->request->post['gifts_threshold']) ? $this->request->post['gifts_threshold'] : 0;

            if ($threshold != 0 && (isset($this->session->data['modal_data_list']) && !empty($this->session->data['modal_data_list']))) {

                $this->load->model('extended/gifts');
                $this->model_extended_gifts->add($threshold,$this->session->data['modal_data_list']);

                if (isset($this->session->data['modal_data_list'])) unset($this->session->data['modal_data_list']);
            } else {
                $error = 'Заполните необходимые данные';
            }

            $json=[
                'data'=>$this->listData(),
                'error'=>$error
            ];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function autocomplete() {

        $json = array();
        $selectedGifts = [];

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('extended/gifts');

            foreach ($this->session->data['modal_data_list'] as $item) {
                $selectedGifts[] = $item['product_id'];
            }

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start'       => 0,
                'limit'       => 10,
                'filter_selected' => !empty($selectedGifts)?'('.implode(',',$selectedGifts).')':''
            );

            $results = $this->model_extended_gifts->getProducts($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'product_id' => $result['product_id']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function up() {

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $product_id=isset($this->request->post['product_id'])?$this->request->post['product_id']:0;

            if($product_id) {
                $this->load->model('catalog/product');

                $product_info=$this->model_catalog_product->getProduct($product_id);

                $this->session->data['modal_data_list'][]=[
                    'name'=>$product_info['name'],
                    'product_id'=>$product_info['product_id']
                ];
                echo $this->listData();
            }
        }
    }

    public function delete() {

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $product_id=isset($this->request->post['product_id'])?$this->request->post['product_id']:0;

            if($product_id) {
                $i=0;
                foreach ($this->session->data['modal_data_list'] as $item) {
                    if($item['product_id']==$product_id) array_splice($this->session->data['modal_data_list'],$i,1);
                    $i++;
                }

                echo $this->listData();
            }

        }
    }

    public function edit() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $threshold_id=isset($this->request->post['threshold_id'])?$this->request->post['threshold_id']:0;

            if($threshold_id!=0) {
                if(isset($this->session->data['modal_data_list'])) unset($this->session->data['modal_data_list']);

                echo $this->listData($threshold_id);
            }

        }

    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'module/gifts')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}