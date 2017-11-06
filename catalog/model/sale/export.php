<?php

/* Данная модель генерит XML файл текущего(произведённого) заказа используя $order_id и отправляет данные на
 * указанную почту и сохраняет файл по пути system/storage/upload/orders/order_$order_id. Так же выполняет проверку,
 * если письмо отправилось без ошибок, то файл удаляется из системы, чтоб её не засорять.
 * Вызывается наша модель в файле catalog/controller/checkout/oct_fastorder.php в районе 2935 строки
 */

class ModelSaleExport extends Model
{
    private function registryModels()
    {
        require ROOT.'/admin/model/sale/order.php';
        require ROOT.'/admin/model/marketing/affiliate.php';
        require ROOT.'/admin/model/localisation/language.php';

        $this->registry->set('model_sale_order', new ModelSaleOrder($this->registry));
        $this->registry->set('model_marketing_affiliate', new ModelMarketingAffiliate($this->registry));
        $this->registry->set('model_localisation_language', new ModelLocalisationLanguage($this->registry));
    }

    public function createXMLOfCurrentOrder($order_id)
    {
        $this->registryModels();

        $result = $this->model_sale_order->getOrder($order_id);

        // создаём структуру XML-файла
        $xml = new DOMDocument("1.0", "utf-8");

        $xmlPath = ROOT.'/system/storage/upload/orders/order_' . $order_id . '.xml';

        $order_info = $this->model_sale_order->getOrder($order_id);

        $xml_order           = $xml->createElement('Заказ');
        $xml_order_num       = $xml->createElement('НомерЗаказа', $result['order_id']);
        $xml_order_date      = $xml->createElement('ДатаЗаказа', $result['date_added']);
        $xml_order_status    = $xml->createElement('Отправка', $result['order_status_id']);
        $xml_order_customer  = $xml->createElement('Клиент');
        $xml_order_code      = $xml->createElement('Код1С');
        $xml_order_name      = $xml->createElement('НаименованиеПолное', $result['firstname']);
        $xml_order_telephone = $xml->createElement('Телефон', $order_info['telephone']);
        $xml_order_items     = $xml->createElement('Товары');

        // Основная оболочка
        $xml->appendChild($xml_order);

        // Заказ
        $xml_order->appendChild($xml_order_num);
        $xml_order->appendChild($xml_order_date);
        $xml_order->appendChild($xml_order_status);
        $xml_order->appendChild($xml_order_customer);
        $xml_order->appendChild($xml_order_items);

        // Клиент
        $xml_order_customer->appendChild($xml_order_code);
        $xml_order_customer->appendChild($xml_order_name);
        $xml_order_customer->appendChild($xml_order_telephone);

        $products = $this->model_sale_order->getOrderProducts($result['order_id']);

        foreach ($products as $product) {

            $xml_order_item  = $xml->createElement('Товар');
            $xml_order_icode = $xml->createElement('Код', $product['model']);
            $xml_order_count = $xml->createElement('Количество', $product['quantity']);
            $xml_order_price = $xml->createElement('Цена', $product['price']);

            // Товары
            $xml_order_items->appendChild($xml_order_item);

            // Товар
            $xml_order_item->appendChild($xml_order_icode);
            $xml_order_item->appendChild($xml_order_count);
            $xml_order_item->appendChild($xml_order_price);
        }

        $xml->formatOutput = true;

        // сохраняем данные заказа в файл system/storage/upload/order_№ЗАКАЗА.xml, если последний не создан - создаём

        $res = file_put_contents($xmlPath, $xml->saveXML());

        if ($res === false) {
            trigger_error('Can`t save XML file', E_USER_ERROR);
            exit();
        }

        // настраиваем письмо и отправляем файл на указанную почту

        $files = array('orders.xml' => file_get_contents($xmlPath));

        $to = 'admin@isc.net.ua';
        $from = 'office@isc.net.ua';
        $subject = 'Заказы магазина ' . $this->config->get('config_name');
        $body = 'Файл с заказами в приложении к письму';

        // если письмо было отправлено, - удаляём файл текущего заказа из системы
        if ($this->KMail($to, $from, $subject, $body, $files)) {
//            unlink($xmlPath);
            echo 'Заказ №' . $result['order_id'] . ' отправлен на email: ' . $to . ', файл удалён из системы', '<br>';
        }
        else {
            echo 'Произошла ошибка', '<br>';
        }
    }

    public function KMail($to, $from, $subj, $text, $files = null, $isHTML = false){
        $boundary = "------------".strtoupper(md5(uniqid(rand())));
        $headers  = "From: ".$from."\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: multipart/mixed; " .
            " boundary=\"" . $this->boundary . "\"\r\n" .
            "X-Mailer: PHP/" . phpversion();
        if (!$isHTML){
            $type = 'text/plain';
        }else{
            $type = 'text/html';
        }
        $body =  $boundary."\r\n\r\n
            Content-Type:".$type."; charset=utf-8\r\n
            Content-Transfer-Encoding: 8bit\r\n\r\n
            ".$text."\r\n\r\n";
        if (!empty($files)){
            foreach($files as $filename => $filecontent){
                $body .= $boundary."\r\n
                Content-Type: application/octet-stream;name=\"".base64_encode($filename)."\"\r\n
                Content-Transfer-Encoding:base64\r\n
                Content-Disposition:attachment;filename=\"".base64_encode($filename)."\"\r\n\r\n
                ".chunk_split(base64_encode($filecontent));

            }
        }
        return mail($to, $subj, $body, $headers);
    }
}