<?php
class ModelTotalTotalCustomerGroupDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if($user_id = $this->customer->isLogged()){

			$this->load->model('account/customer');
			$customer = $this->model_account_customer->getCustomer($user_id);
			$customerDiscount = 0;
			$sumForDiscount = 0;
			$subtraction = 0;

			$discounts = $this->config->get('total_customer_group_discount_customer_group_id');
			foreach ($discounts as $group_id => $discount){
				if($group_id == $customer['customer_group_id']){
					$customerDiscount = $discount;
					break;
				}
			}

			if($customerDiscount != 0){
				$this->load->model('catalog/product');
				if($this->config->get('total_customer_group_discount_tax')){
					if($this->config->get('total_customer_group_discount_special')){
						foreach ($this->cart->getProducts() as $product) {
							if($this->model_catalog_product->getProductSpecial($product['product_id'], $customer['customer_group_id'])){
								continue;
							}
							$sumForDiscount += $product['quantity']*$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
							//13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);
                            if((int)$product_info['limitd']!=0 && $customerDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($customerDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }

						}
					}else{
						foreach ($this->cart->getProducts() as $product) {
							$sumForDiscount += $product['quantity']*$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);
                            if((int)$product_info['limitd']!=0 && $customerDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($customerDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
						}
					}
				}else{
					if($this->config->get('total_customer_group_discount_special')){
						foreach ($this->cart->getProducts() as $product) {
							if($this->model_catalog_product->getProductSpecial($product['product_id'], $customer['customer_group_id'])){
								continue;
							}
							$sumForDiscount += $product['quantity']*$product['price'];

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);
                            if((int)$product_info['limitd']!=0 && $customerDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($customerDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
						}
					}else{
						foreach ($this->cart->getProducts() as $product) {
							$sumForDiscount += $product['quantity']*$product['price'];

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);

                            if((int)$product_info['limitd']!=0 && $customerDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($customerDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
						}
					}
				}

				//13-10-2017
				//$subtraction = $sumForDiscount*($customerDiscount/100);
				$total -= $subtraction;
			}


			if($this->config->get('total_customer_group_discount_show') == 1 || ($customerDiscount != 0 && $this->config->get('total_customer_group_discount_show') == 2)){
				$this->load->language('total/total_customer_group_discount');
				$total_data[] = array(
					'code'       => 'total_customer_group_discount',
					//'title'      => sprintf($this->language->get('text_total_discount'), $customerDiscount),
                    'title'      => str_replace('%s%%','',$this->language->get('text_total_discount')),
					'text'       => $this->currency->format(-$subtraction),
					'value'      => -$subtraction,
					'sort_order' => $this->config->get('total_customer_group_discount_sort_order')
				);
			}


		}
	}
}
?>