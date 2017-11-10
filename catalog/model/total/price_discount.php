<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 07.11.17
 * Time: 9:39
 */

class ModelTotalPriceDiscount extends Model {
    public function getTotal(&$total_data, &$total, &$taxes) {
        if ($user_id = $this->customer->isLogged()) {

            $priceDiscount=0;
            $sumForDiscount = 0;
            $subtraction = 0;

            $this->load->model('account/customer');
            $customer = $this->model_account_customer->getCustomer($user_id);

            $this->load->model('extended/price_discount');
            $discounts=$this->model_extended_price_discount->getPriceDiscounts();

            $cartSubTotal=$this->cart->getSubTotal();

            foreach ($discounts as $discount) {

                if($discount['threshold']>$cartSubTotal) {
                    continue;
                } else {
                    $priceDiscount=$discount['discount'];
                    break;
                }
            }

            //var_dump($customer['customer_group_id']==$this->config->get('price_discount_group_id'));
            //var_dump($priceDiscount);

            if($priceDiscount != 0 && $customer['customer_group_id']==$this->config->get('price_discount_group_id')){
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
                            if((int)$product_info['limitd']!=0 && $priceDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($priceDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }

                        }
                    }else{
                        foreach ($this->cart->getProducts() as $product) {
                            $sumForDiscount += $product['quantity']*$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);
                            if((int)$product_info['limitd']!=0 && $priceDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($priceDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
                        }
                    }
                } else {
                    if($this->config->get('total_customer_group_discount_special')){
                        foreach ($this->cart->getProducts() as $product) {
                            if($this->model_catalog_product->getProductSpecial($product['product_id'], $customer['customer_group_id'])){
                                continue;
                            }
                            $sumForDiscount += $product['quantity']*$product['price'];

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);
                            if((int)$product_info['limitd']!=0 && $priceDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($priceDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
                        }
                    }else{
                        foreach ($this->cart->getProducts() as $product) {
                            $sumForDiscount += $product['quantity']*$product['price'];

                            //13-10-2017
                            $product_info=$this->model_catalog_product->getProduct($product['product_id']);

                            if((int)$product_info['limitd']!=0 && $priceDiscount>(int)$product_info['limitd']) {
                                $subtraction += round((($product['quantity']*$product['price'])*(int)$product_info['limitd'])/100,0,PHP_ROUND_HALF_DOWN);
                            } else {
                                $subtraction += round(($product['quantity']*$product['price'])*($priceDiscount/100),0,PHP_ROUND_HALF_DOWN);
                            }
                        }
                    }
                }


                //$subtraction = $sumForDiscount*($priceDiscount/100);
                $total -= $subtraction;
            }

            //if($this->config->get('total_customer_group_discount_show') == 1 || ($priceDiscount != 0 && $this->config->get('total_customer_group_discount_show') == 2)){
            if($priceDiscount != 0){
                $this->load->language('total/price_discount');
                $total_data[] = array(
                    'code'       => 'price_discount',
                    'title'      => $this->language->get('text_total_discount'),
                    'text'       => $this->currency->format(-$subtraction),
                    'value'      => -$subtraction,
                    'sort_order' => $this->config->get('price_discount_sort_order')
                );
            }

        }
    }
}
