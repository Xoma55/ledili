<?php

abstract class OneCEntity
{
    protected $exchange;
    protected $registry;

    protected static $isPreparedToImport = array();
    protected static $adminHelpers = array();
    protected static $webTo1cData = array();
    protected static $oneCToWebData = array();
    protected $entityName = '';

    protected $relTable = '';
    protected $webField = '';
    protected $oneCField = '';
    protected $urlAliasQueryPrefix = '';

    protected $pathToAdminHelper = '';
    protected $adminHelperClassName = '';
    protected $adminHelperInsertMethodName = '';

    protected $storeId;
    protected $langId;

    protected $importData;


    public function __construct($registry, OneCExchange $exchange)
    {
        $this->exchange = $exchange;
        $this->registry = $registry;
        $this->langId = $exchange->langId;
        $this->storeId = $exchange->storeId;

        $this->setDbNames();

        if (empty(self::$isPreparedToImport[$this->entityName]))
            $this->prepareToImport();

    }

    public function getAdminHelper()
    {
        return self::$adminHelpers[$this->entityName];
    }

    protected function setDbNames()
    {
        if (empty($this->entityName))
            throw new Exception('Entity name is not set');

        if (empty($this->relTable))
            $this->relTable = $this->entityName . '_to_1c';

        if (empty($this->webField))
            $this->webField = $this->entityName . '_id';

        if (empty($this->oneCField))
            $this->oneCField = '1c_' . $this->entityName . '_id';
    }

    protected function insertToWebDb($data, $oneCId)
    {
        $webId = self::$adminHelpers[$this->entityName]->{$this->adminHelperInsertMethodName}($data);

        $this->updateRelation($webId, $oneCId);

        $this->createSeoUrl($webId);

        return $webId;
    }

    protected function createSeoUrl($webId)
    {
        if(empty($this->ruSeoKeyword) || !$this->exchange->conditionToCreateSeoUrl)
            return;

        $query = $this->webField . '=' . $webId;

        $this->seoPro->addSeoUrl($query, $this->ruSeoKeyword);
    }

    protected function updateRelation($webId, $oneCId)
    {
        $webId = $this->db->escape($webId);
        $oneCId = $this->db->escape($oneCId);

        $sql = 'INSERT INTO 
                    `' . DB_PREFIX . $this->relTable .'`
                SET 
                    ' . $this->webField . ' = "' . $webId . '", 
                    ' . $this->oneCField . ' = "' . $oneCId . '"';
        $this->db->query($sql);

        self::$webTo1cData[$this->entityName][$webId] = $oneCId;
        self::$oneCToWebData[$this->entityName][$oneCId] = $webId;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    abstract public function processData(SimpleXMLElement $data);

    public static function getWebId($entityName, $oneCId)
    {
        if(isset(self::$oneCToWebData[$entityName][$oneCId]))
            return self::$oneCToWebData[$entityName][$oneCId];

        return false;
    }

    protected function isNewRecord()
    {
        return !in_array($this->importData['id'], self::$webTo1cData[$this->entityName]);
    }

    abstract protected function insert();

    public function save()
    {
        if ($this->isNewRecord())
            $this->insert();
        else
            $this->update();
    }

    protected function update()
    {
        //todo-in update?
    }

    protected function prepareToImport()
    {
        $this->checkDB();

        $this->loadWebData();

        $this->loadAdminHelper();

        self::$isPreparedToImport[$this->entityName] = true;
    }

    protected function loadAdminHelper()
    {
        require ROOT . $this->pathToAdminHelper;
        self::$adminHelpers[$this->entityName] = new $this->adminHelperClassName($this->registry);
    }

    protected function loadWebData()
    {
        $sql = 'SELECT * FROM `' . DB_PREFIX . $this->relTable . '`';
        $records = $this->db->query($sql)->rows;
        self::$webTo1cData[$this->entityName] = array();
        if (!empty($records))
            foreach ($records as $record) {
                self::$webTo1cData[$this->entityName][$record[$this->webField]] = $record[$this->oneCField];
                self::$oneCToWebData[$this->entityName][$record[$this->oneCField]] = $record[$this->webField];
            }
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
        }
    }
}