<?php
class ControllerCheckoutFastOrderConfirm extends Controller {
	public function index() {

		$fastorder_data = $this->config->get('fastorder_data');

		$order_data = array();

		$order_data['totals'] = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('extension/extension');

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);

				$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
			}
		}

		$sort_order = array();

		foreach ($order_data['totals'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $order_data['totals']);

		$this->load->language('checkout/checkout');

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');

		if ($order_data['store_id']) {
			$order_data['store_url'] = $this->config->get('config_url');
		} else {
			$order_data['store_url'] = HTTP_SERVER;
		}

		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

			$order_data['customer_id'] = $this->customer->getId();
			$order_data['customer_group_id'] = $customer_info['customer_group_id'];
			$order_data['firstname'] = $customer_info['firstname'];
			$order_data['lastname'] = $customer_info['lastname'];
			$order_data['email'] = $customer_info['email'];
			$order_data['telephone'] = $customer_info['telephone'];
			$order_data['fax'] = $customer_info['fax'];
			$order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
		} elseif (isset($this->session->data['guest'])) {
			$order_data['customer_id'] = 0;
			$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['guest']['firstname'];
			$order_data['lastname'] = $this->session->data['guest']['lastname'];
			$order_data['email'] = $this->session->data['guest']['email'];
			$order_data['telephone'] = $this->session->data['guest']['telephone'];
			$order_data['fax'] = $this->session->data['guest']['fax'];
			$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
		}

		$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
		$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
		$order_data['payment_company'] = $this->session->data['payment_address']['company'];
		$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
		$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
		$order_data['payment_city'] = $this->session->data['payment_address']['city'];
		$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
		$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
		$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
		$order_data['payment_country'] = $this->session->data['payment_address']['country'];
		$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
		$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
		$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

		if (isset($this->session->data['payment_method']['title'])) {
			$order_data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$order_data['payment_method'] = '';
		}

		if (isset($this->session->data['payment_method']['code'])) {
			$order_data['payment_code'] = $this->session->data['payment_method']['code'];
		} else {
			$order_data['payment_code'] = '';
		}

		if ($this->cart->hasShipping()) {
			$order_data['shipping_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['shipping_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['shipping_company'] = $this->session->data['payment_address']['company'];
			$order_data['shipping_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['shipping_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['shipping_city'] = $this->session->data['payment_address']['city'];
			$order_data['shipping_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['shipping_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['shipping_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['shipping_country'] = $this->session->data['payment_address']['country'];
			$order_data['shipping_country_id'] = $this->session->data['payment_address']['country_id'];
			$order_data['shipping_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

			if (isset($this->session->data['shipping_method']['title'])) {
				$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$order_data['shipping_method'] = '';
			}

			if (isset($this->session->data['shipping_method']['code'])) {
				$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
			} else {
				$order_data['shipping_code'] = '';
			}
		} else {
			$order_data['shipping_firstname'] = '';
			$order_data['shipping_lastname'] = '';
			$order_data['shipping_company'] = '';
			$order_data['shipping_address_1'] = '';
			$order_data['shipping_address_2'] = '';
			$order_data['shipping_city'] = '';
			$order_data['shipping_postcode'] = '';
			$order_data['shipping_zone'] = '';
			$order_data['shipping_zone_id'] = '';
			$order_data['shipping_country'] = '';
			$order_data['shipping_country_id'] = '';
			$order_data['shipping_address_format'] = '';
			$order_data['shipping_method'] = '';
			$order_data['shipping_code'] = '';
		}

		$order_data['products'] = array();

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				$option_data[] = array(
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],
					'name'                    => $option['name'],
					'value'                   => $option['value'],
					'type'                    => $option['type']
				);
			}

			$order_data['products'][] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
				'reward'     => $product['reward']
			);
		}

		// Gift Voucher
		$order_data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$order_data['vouchers'][] = array(
					'description'      => $voucher['description'],
					'code'             => substr(md5(mt_rand()), 0, 10),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']
				);
			}
		}

		$order_data['comment'] = $this->session->data['comment'];
		$order_data['total'] = $total;

		if (isset($this->request->cookie['tracking'])) {
			$order_data['tracking'] = $this->request->cookie['tracking'];

			$subtotal = $this->cart->getSubTotal();

			// Affiliate
			$this->load->model('affiliate/affiliate');

			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

			if ($affiliate_info) {
				$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			// Marketing
			$this->load->model('checkout/marketing');

			$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

			if ($marketing_info) {
				$order_data['marketing_id'] = $marketing_info['marketing_id'];
			} else {
				$order_data['marketing_id'] = 0;
			}
		} else {
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';
		}

		$order_data['language_id'] = $this->config->get('config_language_id');
		$order_data['currency_id'] = $this->currency->getId();
		$order_data['currency_code'] = $this->currency->getCode();
		$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$order_data['forwarded_ip'] = '';
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$order_data['user_agent'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$order_data['accept_language'] = '';
		}

		$this->load->model('checkout/order');

		$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

		if ($fastorder_data['payment']) {
			$data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
		} else {
			$data['payment'] = "";
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/fastorder/confirm.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/fastorder/confirm.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/fastorder/confirm.tpl', $data));
		}
	}
}
