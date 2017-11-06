
<?php

class ControllerExtendedOneclickModal extends Controller {
    public function index() {

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extended/oneclick/modal.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/extended/oneclick/modal.tpl');
        } else {
            return $this->load->view('default/template/extended/oneclick/modal.tpl');
        }

    }
}