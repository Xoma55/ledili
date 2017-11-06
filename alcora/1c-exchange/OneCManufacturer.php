<?php

class OneCManufacturer extends OneCEntity
{
    protected $entityName = 'manufacturer';

    protected $pathToAdminHelper = '/admin/model/catalog/manufacturer.php';
    protected $adminHelperClassName = 'ModelCatalogManufacturer';
    protected $adminHelperInsertMethodName = 'addManufacturer';

    protected function insert()
    {
        if (empty($this->importData))
            return;

        $data = array(
            'name' => $this->importData['name'],
            'sort_order' => $this->importData['sort_order'],
            'manufacturer_store' => $this->importData['manufacturer_store'],
        );

        $this->insertToWebDb($data, $this->importData['id']);
    }

    public function processData(SimpleXMLElement $data)
    {
        $this->importData = array(
            'id' => (string)$data->attributes()->id,
            'name' => (string)$data->attributes()->name,
            'sort_order' => '1',
            'manufacturer_store' => array($this->storeId),
        );

        $this->ruSeoKeyword = $this->importData['name'];
    }

}