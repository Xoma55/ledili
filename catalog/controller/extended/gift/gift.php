<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 13.11.17
 * Time: 11:00
 */

class ControllerExtendedGiftGift extends Controller {
    public function index() {

        $width=190;
        $heigh=145;

        $this->load->language('extended/gift');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_tax'] = $this->language->get('text_tax');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_preorder'] = $this->language->get('button_preorder');

        $this->load->model('catalog/product');
        $this->load->model('extended/gift');

        $this->load->model('tool/image');

        $data['products'] = array();

        $total=780;

        //$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
        $results = $this->model_extended_gift->getGiftsByTotal($total);

        if ($results) {
            foreach ($results as $result) {

                $product_info=$this->model_catalog_product->getProduct($result['product_id']);

                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $width, $heigh);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $width, $heigh);
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $product_info['rating'];
                } else {
                    $rating = false;
                }

                $data['products'][] = array(
                    'product_code'  => $product_info['model'],
                    'product_id'  => $product_info['product_id'],
                    'preorder'    => $product_info['preorder'],
                    'thumb'       => $image,
                    'name'        => $product_info['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'stock_status_id' => $product_info['stock_status_id'],
                    'stock_status' => $product_info['stock_status'],
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                );
            }

            $data['position']='column_left';

            //03-10-2017
            //$data['preorder_data']=$this->load->controller('extended/oneclick/modal');

//            echo '<pre>';
//            var_dump($data['products']); die();

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extended/gift/gift.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/extended/gift/gift.tpl', $data);
            } else {
                return $this->load->view('default/template/extended/gift/gift.tpl', $data);
            }
        }
    }
}
