<?php

class OneCExchange
{

    private $registry;

    private $errors = array();

    public $imageHelper;

    public $langId  = '2';
    public $storeId = '0';

    public $conditionToCreateSeoUrl = true; // если выставить в false - первый импорт длится 20 сек (у товаров одинаковые названия)


    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->_prepareToImport();

        $product = new OneCProduct($this->registry, $this);

        $this->imageHelper = new OneCImageHelper($this->registry, $product->getAdminHelper());
    }

    public function updateImages($list)
    {
        foreach ($list as $image)
            $this->imageHelper->processImage($image);
    }

    private function _prepareToImport()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(60 * 15);
    }

    public function getErrorsString()
    {
        return 'Errors: ' . implode(',', $this->errors);
    }

    public function doImport($xmlDump)
    {
        $data = $this->readXml($xmlDump);

        if (empty($data)) {
            return false;
        }

        // update categories
        if (!empty($data->Группы->Группа)) {
            $this->updateCategories($data->Группы->Группа);
        }

        // update products
        if (!empty($data->Товары->Товар)) {
            $this->updateProducts($data->Товары->Товар);
        }

        return true;
    }

    protected function updateCategories($data)
    {
        foreach ($data as $importCategory) {
            $category = new OneCCategory($this->registry, $this);
            $category->processData($importCategory);
            $category->save();
        }
    }

    protected function updateProducts($data)
    {
        foreach ($data as $importProduct) {
            $product = new OneCProduct($this->registry, $this);
            $product->processData($importProduct);
            $product->save();
        }
    }

    protected function readXml($xml)
    {
        if (!is_string($xml)) {
            $this->errors[] = 'xml document is not correct';

            return '';
        }

        $data = simplexml_load_string($xml);

        if ($data === false) {
            $this->errors[] = 'cannot read xml document';

            return '';
        }

        return $data;

    }


}