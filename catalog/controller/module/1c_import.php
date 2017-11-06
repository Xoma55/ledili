<?php

class ControllerModule1cImport extends Controller
{
    public $tmpFiles = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        require_once ROOT . '/alcora/1c-exchange/OneCExchange.php';
        require_once ROOT . '/alcora/1c-exchange/OneCEntity.php';
        require_once ROOT . '/alcora/1c-exchange/OneCCategory.php';
        require_once ROOT . '/alcora/1c-exchange/OneCProduct.php';
        require_once ROOT . '/alcora/1c-exchange/OneCManufacturer.php';
        require_once ROOT . '/alcora/1c-exchange/OneCAttribute.php';
        require_once ROOT . '/alcora/1c-exchange/OneCImageHelper.php';
    }

    public function approveImport()
    {
        //todo-in modify this
        return true;
    }

    public function getXmlDumpPath()
    {
        return ROOT . '/system/storage/upload/import.zip';
    }

    public function getImagePathList()
    {
        return glob(ROOT . "/system/storage/upload/*.jpg");
    }

    public function process()
    {
        if (!$this->approveImport())
            die('Import is not approved');

        $xml = $this->getXmlDump();

        $exChange = new OneCExchange($this->registry);

        if (isset($_GET['noSeoUrls']))
            $exChange->conditionToCreateSeoUrl = false;

        $result = $exChange->doImport($xml);

        $newImages = $this->getImagePathList();

        if (!empty($newImages))
            $exChange->updateImages($newImages);


        foreach ($this->tmpFiles as $file)
            unlink($file);

        if ($result)
            die('import finished');

        die($exChange->getErrorsString());
    }

    public function getXmlDump()
    {
        $path = $this->getXmlDumpPath();

        $this->tmpFiles[] = $path;

        $importFileName = 'import.xml';
        $importFilePath = dirname($path) . '/' . $importFileName;

        if (!file_exists($path))
            die('XML-dump don\'t exists in \'' . $path . '\'');

        $zip = new ZipArchive();
        if ($zip->open($path)) {
            $zip->renameIndex(0, $importFileName);
            $zip->extractTo(dirname($path));
        }
        else {
            die('Can not open archive in \'' . $path . '\'');
        }

        if (!file_exists($importFilePath))
            die('import file does not exists in ' . $importFilePath);

        $this->tmpFiles[] = $importFilePath;

        return file_get_contents($importFilePath);
    }

}