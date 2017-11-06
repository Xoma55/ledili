<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {


		$this->load->language('checkout/success');



		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			// Add to activity log
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

        $this->load->model('account/success'); //for update customer group
        //for update customer group


        $a=$this->customer->getId();
        $customer_group_id = $this->model_account_success->getCustomer(isset($a)?$a:1);

        $customer_group_value = $this->model_account_success->getCustomerGroup($customer_group_id);

        $reach_value = $customer_group_value['reach_value'];

        $period = $customer_group_value['period'];

        $s_status = $customer_group_value['s_status'];

        $reach_customer_group_id = $customer_group_value['reach_customer_group_id'];

        $ross = $customer_group_value['reach_order_status_ids'];

        if (!$ross) {
            $ross = array();
        }

        $custotal=0;

        foreach ($ross as $reach_order_status_id) {
            $values = $this->model_account_success->getOrderTotalValue($reach_order_status_id,$period);
            foreach ($values as $value) {
                $custotal = $custotal + $value['total'];
            }
        }
        if ($s_status) {
            if ($custotal >= $reach_value) {
                $this->model_account_success->editCustomer($this->customer->getId(), $reach_customer_group_id);
            }
        }
        //end for


        if (isset($this->session->data['recent_order_id'])) {
            if ($this->customer->isLogged()) {
                if (!empty($this->session->data['recent_firstname'])) {
                    $data['text_message'] = sprintf($this->language->get('text_customer_ordervs_firstname'), $this->session->data['recent_firstname'], $this->session->data['recent_order_id'], $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'),  $this->url->link('information/contact'));
                } else {
                    $data['text_message'] = sprintf($this->language->get('text_customer_order'), $this->session->data['recent_order_id'], $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'),  $this->url->link('information/contact'));
                }
            } else {
                if (!empty($this->session->data['recent_firstname'])) {
                    $data['text_message'] = sprintf($this->language->get('text_guest_order_vs_firstname'), $this->session->data['recent_firstname'], $this->session->data['recent_order_id'], $this->url->link('information/contact'));
                } else {
                    $data['text_message'] = sprintf($this->language->get('text_guest_order'), $this->session->data['recent_order_id'], $this->url->link('information/contact'));
                }
            }
        } else {
            if ($this->customer->isLogged()) {
                $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
            } else {
                $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
            }
        }

        if (isset($order_history_comment) && $order_history_comment) {
            $data['text_message'] .= '<hr/>'.$order_history_comment;
        }

        // Prevent ocmod changes
        $data['button_continue'] = $f117= $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
}