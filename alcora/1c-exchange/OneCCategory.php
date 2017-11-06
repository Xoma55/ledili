<?php

class OneCCategory extends OneCEntity
{
    const TOP_PARENT_ID = 60; // категория "Каталог товаров"

    protected $entityName = 'category';

    protected $pathToAdminHelper = '/admin/model/catalog/category.php';
    protected $adminHelperClassName = 'ModelCatalogCategory';
    protected $adminHelperInsertMethodName = 'addCategory';


    protected function insert()
    {
        $parentId = isset(self::$oneCToWebData[$this->entityName][$this->importData['parentId']]) ?
            self::$oneCToWebData[$this->entityName][$this->importData['parentId']] : 0;

        $data = array(
            'parent_id' => $parentId,
            'column' => '1',
            'sort_order' => '1',
            'status' => '1',
            'category_store' => array($this->storeId),
            'category_description' => array(
                $this->langId => array(
                    'name' => $this->importData['name'],
                    'description' => '',
                    'meta_title' => $this->importData['name'],
                    'meta_description' => '',
                    'meta_keyword' => ''
                )
            )
        );

        $this->insertToWebDb($data, $this->importData['id']);

    }

    public function processData(SimpleXMLElement $data)
    {
        $this->importData = array(
            'id' => (string)$data->Код,
            'parentId' => (string)$data->КодРодителя,
            'name' => (string)$data->Наименование,
        );

        $this->ruSeoKeyword = $this->importData['name'];
    }

    protected function checkDB()
    {
        $query = $this->db->query('SHOW TABLES LIKE "' . DB_PREFIX . $this->relTable . '"');

        if (!$query->num_rows) {
            $sql = 'CREATE TABLE
                    `' . DB_PREFIX . $this->relTable . '` (
                        `'. $this->webField . '` int(11) NOT NULL,
                        `'. $this->oneCField . '` varchar(255) NOT NULL,
                        PRIMARY KEY (`'. $this->webField . '`, `'. $this->oneCField . '`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8
                    ';
            $this->db->query($sql);

            $this->_addStartRelation();
        }
    }

    private function _addStartRelation()
    {
        $sql = 'INSERT INTO 
                  ' . DB_PREFIX . $this->relTable . ' 
               SET 
                  ' . $this->webField . ' = ' . self::TOP_PARENT_ID . ',
                  ' . $this->oneCField . ' = 0
               ';
        $this->db->query($sql);
    }
}