<?php

class OneCAttribute extends OneCEntity
{
    protected $entityName = 'attribute';

    protected $pathToAdminHelper           = '/admin/model/catalog/attribute.php';
    protected $adminHelperClassName        = 'ModelCatalogAttribute';
    protected $adminHelperInsertMethodName = 'addAttribute';

    const DEFAULT_1С_ATTR_GROUP_ID = '7';

    protected function insert()
    {
        if (empty($this->importData))
            return;

        $data = array(
            'attribute_group_id' => self::DEFAULT_1С_ATTR_GROUP_ID,
            'sort_order' => '1',
            'attribute_description' => array(
                $this->langId => array(
                    'name' => $this->importData['name']
                )
            )
        );

        $this->insertToWebDb($data, $this->importData['id']);
    }

    public function processData(SimpleXMLElement $data)
    {
        $this->importData = array(
            'id' => (string)$data->attributes()->id,
            'name' => (string)$data->attributes()->name,
        );
    }

    protected function createSeoUrl($webId)
    {
        // no seo url for attribute
    }

    protected function checkDB()
    {
        parent::checkDB();

        $this->checkDefaultAttrGroupId();
    }

    protected function checkDefaultAttrGroupId()
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'attribute_group 
                WHERE attribute_group_id = ' . self::DEFAULT_1С_ATTR_GROUP_ID . ' LIMIT 1';

        if (empty($this->db->query($sql)->row))
            die('create or configure default 1c attribute group');
    }
}