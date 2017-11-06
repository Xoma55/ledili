<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 04.07.17
 * Time: 11:07
 */

class ControllerExtendedOneclickOneclickOrder extends Controller {
    public function index() {

        if($this->request->server['REQUEST_METHOD'] == 'POST') {

            $postData=$this->request->post;

            $productName = isset($postData['prodname'])?htmlspecialchars_decode($postData['prodname']):'';
            $name = isset($postData['uname'])?$postData['uname']:'';
            $phone = isset($postData['uphone'])?$postData['uphone']:'';
            $price = isset($postData['uprice'])?preg_replace("/[^0-9]/",'',$postData['uprice']):0;
            $quantity = isset($postData['uquantity'])?$postData['uquantity']:1;
            $subject = isset($postData['title'])?$postData['title']:'';
            $product_id=isset($postData['uproduct_id'])?$postData['uproduct_id']:0;
            $type=isset($postData['type'])?$postData['type']:1;

            $city='Киев';
            $country='Украина';

            // -= Add order =-

            // Clear session data
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);

            // Payment address default
            $this->session->data['payment_address'] = [
                'customer_group_id'=>0,
                'address_id'     => '',
                'firstname'      => 'Оплата',
                'lastname'       => 'заказа',
                'city'           => $city,
                'zone_id'        => '',
                'zone'           => '',
                'zone_code'      => '',
                'country_id'     => $this->config->get('config_country_id'),
                'country'        => $country,
                'company'        => '',
                'address_1'      => '',
                'address_2'      => '',
                'postcode'       => '',
                'address_format' => ''
            ];

            // Payment method
            $total=$price*$quantity;
            $this->load->model('payment/cod');
            $this->session->data['payment_method']= $this->model_payment_cod->getMethod($this->session->data['payment_address'], $total);

            //Shipping address default
            $this->session->data['shipping_address']=[
                'country_id'=>$this->config->get('config_country_id'),
                'zone_id'=>'',
                'zone'=>'',
                'firstname'=>'Оплата',
                'lastname'=>'заказа',
                'company'=>'',
                'address_1'=>'',
                'address_2'=>'',
                'city'=>$city,
                'postcode'=>'',
                'address_format'=>'',
                'country'=>$country
            ];


            // Shipping method
            $this->load->model('shipping/pickup');
            $sm_res=$this->model_shipping_pickup->getQuote(0);
            $this->session->data['shipping_method']=$sm_res;

            $this->session->data['shipping_method']['cost']=$sm_res['quote']['pickup']['cost'];
            $this->session->data['shipping_method']['tax_class_id']=$sm_res['quote']['pickup']['tax_class_id'];

            // Order
            $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
            $order_data['store_id'] = $this->config->get('config_store_id');
            $order_data['store_name'] = $this->config->get('config_name');
            $order_data['store_url'] = HTTP_SERVER;

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

            $order_data['comment']=$subject;

            $order_data['payment_method'] = $this->session->data['payment_method']['title'];
            $order_data['payment_code'] = $this->session->data['payment_method']['code'];

            $order_data['shipping_firstname'] = $name;
            //$order_data['shipping_lastname'] = $name;
            $order_data['shipping_lastname'] = '';
            $order_data['shipping_company'] = '';
            $order_data['shipping_address_1'] = '';
            $order_data['shipping_address_2'] = '';
            $order_data['shipping_city'] = $city;
            $order_data['shipping_postcode'] = '';
            $order_data['shipping_zone'] = '';
            $order_data['shipping_zone_id'] = '0';
            $order_data['shipping_country'] = $country;
            $order_data['shipping_country_id'] = $this->config->get('config_country_id');
            $order_data['shipping_address_format'] = '';
            $order_data['shipping_custom_field'] = array();

            $order_data['customer_id'] = 0;
            $order_data['customer_group_id']=$this->config->get('config_customer_group_id');
            $order_data['firstname']=$name;
            //$order_data['lastname']=$name;
            $order_data['lastname']='';
            $order_data['email']='one@click.to';
            $order_data['telephone']=$phone;
            $order_data['newsletter']=false;
            $order_data['fax']='';
            $order_data['company']='';
            $order_data['address_1']='';
            $order_data['address_2']='';
            $order_data['postcode']='';
            $order_data['city']=$city;
            $order_data['country_id']=$this->config->get('config_country_id');
            $order_data['zone_id']='';
            $order_data['custom_field']='';

            $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
            $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];

            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';

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

            $this->load->model('catalog/product');
            $product_info = $this->model_catalog_product->getProduct($product_id);

            $order_data['products'][] = array(
                'product_id' => $product_info['product_id'],
                'name'       => $product_info['name'],
                'model'      => $product_info['model'],
                'option'     => [],
                'download'   => [],
                'quantity'   => $quantity,
                'subtract'   => $product_info['subtract'],
                'price'      => $product_info['price'],
                'total'      => $total,
                'tax'        => $this->tax->getTax($product_info['price'], $product_info['tax_class_id']),
                'reward'     => $product_info['reward']
            );

            $order_data['total']=$product_info['price']*$quantity;
            $order_data['totals']=[];

            $this->load->model('checkout/order');
            $order_id = $this->model_checkout_order->addOrder($order_data);
            //$this->model_checkout_order->setOrderStatus($order_id,1);
            $this->model_checkout_order->addOrderHistory($order_id,1,'Предзаказ');
            $this->model_checkout_order->setOrderType($order_id,$type);
            // Выравниваем кол-во если минус
            /*
            $product_info_after = $this->model_catalog_product->getProduct($product_id);

            if((int)$product_info_after['quantity']<0) {
                $this->model_catalog_product->updateQuantity($product_id);
            }
            */

            /*

            $time=date('d-m-Y H:i:s');
            $mess=''; // хз чё это
            $to = 'xmv54080@gmail.com';
            //$from='studio@flabers.com.ua';
            $text='';
            //$productName=$product_info['name'];

            $html='';
            if ($subject == 'Заказ в один клик' || $subject == 'Заказать в кредит') {

                $html .='<br><br>';
                $html .="<h2>$subject</h2>";
                $html .='<br>';
                $html .="<p>Имя: $name</p>";
                $html .="<p>Товар: $productName</p>";
                $html .="<p>Цена: $price x $quantity</p>";
                $html .="<p>Телефон: $phone</p>";
                $html .='<br>';
                $html .= $time;
                $html .='<br>';
                $html .= $mess;

            } else {
                $html .='<br><br>';
                $html .="<h2>$subject</h2>";
                $html .='<br>';
                $html .="<p>Имя: $name</p>";
                $html .="<p>Товар: $productName</p>";
                $html .="<p>Телефон: $phone</p>";
                $html .='<br>';
                $html .= $time;
                $html .='<br>';
                $html .= $mess;
            }

            $mail = new Mail();
            //$mail = new MailSp();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($to);
            $mail->setFrom($this->config->get('config_email'));
            //$mail->setSender(html_entity_decode($from, ENT_QUOTES, 'UTF-8'));
            $mail->setSender($this->config->get('config_email'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($html);
            $mail->setText($text);
            //$mail->send();

            */
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(['order_id'=>$order_id]));

        }
    }
}