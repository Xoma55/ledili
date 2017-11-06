<?php

class OneCProduct extends OneCEntity
{
    const CUSTOMER_LARGE_WHOLESALE_ID = '8';
    const CUSTOMER_WHOLESALE_ID = '9';
    const CUSTOMER_SMALL_WHOLESALE_ID = '10';

    const IN_STOCK_STATUS_ID = '7';
    const OUT_OF_STOCK_STATUS_ID = '5';

    static protected $isCustomerGroupsChecked = false;

    protected $entityName = 'product';

    protected $importCustomerGroupPrices = array();

    protected $pathToAdminHelper = '/admin/model/catalog/product.php';
    protected $adminHelperClassName = 'ModelCatalogProduct';
    protected $adminHelperInsertMethodName = 'addProduct';

    /**
     * обновляем поля: наличие, цены,
     */
    protected function update()
    {
        $productId = (int)OneCEntity::getWebId($this->entityName, $this->importData['id']);
        $sql = '
            UPDATE ' . DB_PREFIX . 'product 
            SET stock_status_id = ' . self::IN_STOCK_STATUS_ID . ', price = "' . $this->db->escape($this->importData['price']) . '"
            WHERE product_id = ' . $productId . '
            LIMIT 1
        ';
        $this->db->query($sql);


        foreach ($this->importCustomerGroupPrices as $special) {
            $sql = "
                UPDATE " . DB_PREFIX . "product_special 
                SET price = '" . (float)$special['price'] . "'
                WHERE product_id = '{$productId}' AND customer_group_id = '" . (int)$special['customer_group_id'] . "'
                LIMIT 1
            ";

            $this->db->query($sql);
        }

    }

    protected function insert()
    {
        if (empty($this->importData))
            return;

        $categoryId = OneCEntity::getWebId('category', $this->importData['categoryId']);

        // обрабатываем производителей
        $manufacturerId = OneCEntity::getWebId('manufacturer', $this->importData['manufacturerId']);

        if ($manufacturerId === false) {
            $xml = simplexml_load_string('<?xml version=\'1.0\'?><document></document>');
            $xml->addAttribute('id', $this->importData['manufacturerId']);
            $xml->addAttribute('name', $this->importData['manufacturerName']);

            $manufacturer = new OneCManufacturer($this->registry, $this->exchange);
            $manufacturer->processData($xml);
            $manufacturer->save();

            $manufacturerId = OneCEntity::getWebId('manufacturer', $this->importData['manufacturerId']);
        }

        // обрабатываем attributes
        $productAttributes = array();
        $uniqueAttrs = array(); //были замечены дубли
        $attrs = $this->importData['attributes'];
        if(!empty($attrs->Характеристика)) {
            foreach ($attrs->Характеристика as $attr) {

                $attrName = (string)$attr->Наименование;
                $attrValue = (string)$attr->Значение;

                if (in_array($attrName, $uniqueAttrs))
                    continue;

                $uniqueAttrs[] = $attrName;

                $attrId = OneCEntity::getWebId('attribute', $attrName);

                if ($attrId === false) {
                    // такого аттрибута еще нет
                    $xml = simplexml_load_string('<?xml version=\'1.0\'?><document></document>');
                    $xml->addAttribute('id', $attrName);
                    $xml->addAttribute('name', $attrName);

                    $newAttr = new OneCAttribute($this->registry, $this->exchange);
                    $newAttr->processData($xml);
                    $newAttr->save();

                    $attrId = OneCEntity::getWebId('attribute', $attrName);

                }

                $productAttributes[] = array(
                    'attribute_id' => $attrId,
                    'product_attribute_description' => array(
                        $this->langId => array(
                            'text' => $attrValue
                        )
                    )
                );
            }
        }



        $data = array(
            'model' => $this->importData['model'],
            'sku' => $this->importData['catalogId'],
            'upc' => '',
            'ean' => $this->importData['barcode'],
            'jan' => '',
            'isbn' => $this->importData['ourCatalogId'],
            'mpn' => '',
            'location' => '',
            'quantity' => 1000,
            'minimum' => '',
            'subtract' => '',
            'stock_status_id' => $this->importData['stockStatus'],
            'date_available' => '',
            'manufacturer_id' => $manufacturerId,
            'shipping' => '',
            'price' => $this->importData['price'],
            'points' => '',
            'weight' => '',
            'weight_class_id' => '',
            'length' => '',
            'width' => '',
            'height' => '',
            'length_class_id' => '',
            'status' => '1',
            'tax_class_id' => '',
            'sort_order' => '1',
            'product_store' => array($this->storeId),
            'product_description' => array(
                $this->langId => array(
                    'name' => $this->importData['name'],
                    'comment' => $this->importData['comment'],
                    'description' => $this->importData['applicability'],
                    'tag' => '',
                    'meta_title' => $this->importData['name'],
                    'meta_description' => '',
                    'meta_keyword' => '',
                )
            ),
            'product_special' => $this->importCustomerGroupPrices,
            'product_category' => array($categoryId),
            'main_category_id' => $categoryId,
        );
        if (!empty($productAttributes))
            $data['product_attribute'] = $productAttributes;

        $this->insertToWebDb($data, $this->importData['id']);

    }

    public function processData(SimpleXMLElement $data)
    {
        $this->importData = array(
            'id' => (string)$data->Код,
            'name' => (string)$data->Наименование,
            'model' => (string)$data->Модель,
            'manufacturerName' => (string)$data->Фирма,
            'manufacturerId' => (string)$data->ФирмаКод,
            'barcode' => (string)$data->ШтрихКод,
            'categoryId' => (string)$data->КодГруппы,
            'stockStatus' => self::IN_STOCK_STATUS_ID,
            'catalogId' => (string)$data->КаталНомер,
            'price' => (string)$data->Цены->Розничная,
            'attributes' => $data->Характеристики,
            'ourCatalogId' => (string)$data->КаталНомерНаш,
            'applicability' => (string)$data->Применяемость,
            'comment' => (string)$data->Комментарий,

            //'fullName' => (string)$data->ПолноеНаименование,
            //'kved' => (string)$data->КВЭД,
            //'categoryName' => (string)$data->ИмяГруппы, // точно лишняя инфо
        );

        $this->ruSeoKeyword = $this->importData['name'];

        // подготавляиваем цены для групп покупателей
        $prices = array();
        $largeWholesale = (string)$data->Цены->КрупноОптовая;
        $wholesale = (string)$data->Цены->Оптовая;
        $smallWholesale =  (string)$data->Цены->МелкоОптовая;

        $startDate = date('Y-m-d', time() - 60*60*24);
        if (!empty($largeWholesale))
            $prices[] = array(
                'customer_group_id' => self::CUSTOMER_LARGE_WHOLESALE_ID,
                'price' => $largeWholesale,
                'priority' => 1,
                'date_start' => $startDate,
                'date_end' => '2099-01-01'
            );

        if (!empty($wholesale))
            $prices[] = array(
                'customer_group_id' => self::CUSTOMER_WHOLESALE_ID,
                'price' => $wholesale,
                'priority' => 1,
                'date_start' => $startDate,
                'date_end' => '2099-01-01'
            );

        if (!empty($smallWholesale))
            $prices[] = array(
                'customer_group_id' => self::CUSTOMER_SMALL_WHOLESALE_ID,
                'price' => $smallWholesale,
                'priority' => 1,
                'date_start' => $startDate,
                'date_end' => '2099-01-01'
            );

        $this->importCustomerGroupPrices = $prices;

    }

    protected function checkDB()
    {
        parent::checkDB();

        $this->prepareProductStockStatus();

        if (!self::$isCustomerGroupsChecked)
            $this->checkCustomersGroup(array(
                self::CUSTOMER_LARGE_WHOLESALE_ID,
                self::CUSTOMER_WHOLESALE_ID,
                self::CUSTOMER_SMALL_WHOLESALE_ID
            ));
    }

    protected function checkCustomersGroup($ids)
    {
        $sql = 'SELECT customer_group_id 
                FROM ' . DB_PREFIX . 'customer_group 
                WHERE customer_group_id IN (' . implode(',', $ids) . ')';

        if (count($this->db->query($sql)->rows) !== count($ids))
            die('please, create and configure customers groups');
    }

    /**
     * если в импорте товар есть - значит он в наличии
     * если нет - значит нет
     * Итого: перед импортом ставим всем товарам - нет в наличии,
     * а в процессе обновляем что нужно
     */
    protected function prepareProductStockStatus()
    {
        $sql = '
            UPDATE ' . DB_PREFIX . 'product
            SET stock_status_id = ' . self::OUT_OF_STOCK_STATUS_ID . '
            WHERE product_id IN (
                SELECT product_id FROM ' . $this->relTable . '   
            )
        ';

        $this->db->query($sql);
    }
}