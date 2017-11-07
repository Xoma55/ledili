<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 06.11.17
 * Time: 9:37
 */

class ControllerTotalPriceDiscount extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('total/price_discount');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/jquery/jquery.mask.min.js');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('price_discount', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
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

        $data['title_threshold'] = $this->language->get('title_threshold');
        $data['title_discount'] = $this->language->get('title_discount');
        $data['title_action'] = $this->language->get('title_action');

        $data['help_total'] = $this->language->get('help_total');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_edit'] = $this->language->get('button_edit');

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
            'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('total/price_discount', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['action'] = $this->url->link('total/price_discount', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        /*

        if (isset($this->request->post['price_discount_total'])) {
            $data['price_discount_total'] = $this->request->post['price_discount_total'];
        } else {
            $data['price_discount_total'] = $this->config->get('price_discount_total');
        }

        if (isset($this->request->post['price_discount_fee'])) {
            $data['price_discount_fee'] = $this->request->post['price_discount_fee'];
        } else {
            $data['price_discount_fee'] = $this->config->get('price_discount_fee');
        }

        */

        $data['token']= $this->session->data['token'];
        $data['price_discount_threshold'] = '';
        $data['price_discount_modal_discount']='';
        $data['title_modal']='*';

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        if (isset($this->request->post['price_discount_tax_class_id'])) {
            $data['price_discount_tax_class_id'] = $this->request->post['price_discount_tax_class_id'];
        } else {
            $data['price_discount_tax_class_id'] = $this->config->get('price_discount_tax_class_id');
        }

        if (isset($this->request->post['price_discount_group_id'])) {
            $data['price_discount_group_id'] = $this->request->post['price_discount_group_id'];
        } else {
            $data['price_discount_group_id'] = $this->config->get('price_discount_group_id');
        }


        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['price_discount_status'])) {
            $data['price_discount_status'] = $this->request->post['price_discount_status'];
        } else {
            $data['price_discount_status'] = $this->config->get('price_discount_status');
        }

        if (isset($this->request->post['price_discount_sort_order'])) {
            $data['price_discount_sort_order'] = $this->request->post['price_discount_sort_order'];
        } else {
            $data['price_discount_sort_order'] = $this->config->get('price_discount_sort_order');
        }

        $this->load->model('extended/price_discount');
        $data['discount_list']=$this->model_extended_price_discount->getDiscountList();

        $this->load->model('customer/customer_group');
        $data['groups'] = $this->model_customer_customer_group->getCustomerGroups();
        //echo '<pre>'; var_dump($data['groups']); die();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('total/price_discount.tpl', $data));
    }

    public function action() {

        $error='';

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if($this->request->post['price_discount_threshold']=='' || $this->request->post['price_discount_modal_discount']=='') {
                $error='Поле Порог/Скидка не должно быть пустым';
            } else {
                $this->load->model('extended/price_discount');

                if($this->request->post['pd_mode']=='add') {
                    $this->model_extended_price_discount->add($this->request->post);
                } elseif ($this->request->post['pd_mode']=='edit') {
                    $this->model_extended_price_discount->edit($this->request->post);
                }
            }

            $json = ['error' => $error];

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }

    }

    public function delete() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('extended/price_discount');
            foreach ($this->request->post['selected'] as $row_id) {
                $this->model_extended_price_discount->delete($row_id);
            }

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode([]));
        }
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/price_discount')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}