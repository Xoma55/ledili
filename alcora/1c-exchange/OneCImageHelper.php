<?php

class OneCImageHelper
{
    private $imaheFolderName = 'image_1c';
    private $registry;
    private $adminHelper;

    public function __construct($registry, $adminHelper)
    {
        $this->registry = $registry;
        $this->adminHelper = $adminHelper;

        if (!file_exists($this->getImageFolderPath()))
            mkdir($this->getImageFolderPath());
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function getImageFolderPath()
    {
        return DIR_IMAGE . $this->imaheFolderName;
    }

    public function processImage($image)
    {
        $productId = $this->extractProductIdFromImagePath($image);
        $imageNum = $this->extractImageNumber($image);
        $productWebId = OneCEntity::getWebId('product', $productId);

        if (empty($productWebId))
            return;

        $imageName =  $productWebId . '_' . $imageNum . '.jpg';
        $imagePath = $this->imaheFolderName . '/' . $imageName;

        if ($imageNum == '1') {
            // обработка главного имага
            $this->updateProductMainPhoto($productWebId, $imagePath);
        } else {
            // дополнительный имаг
            $this->updateProductAdditionalPhoto($productWebId, $imagePath);
        }

        // перемещаем имаг
        $newFilePath = $this->getImageFolderPath() . '/' . $imageName;
        rename($image, $newFilePath);
    }

    public function extractProductIdFromImagePath($path)
    {
        $parts = explode('/', $path);

        $parts = explode('_', end($parts));

        return reset($parts);
    }

    public function extractImageNumber($path)
    {
        $parts = explode('/', $path);

        $parts = explode('_', end($parts));

        $parts = explode('.', end($parts));

        return reset($parts);
    }

    public function updateProductMainPhoto($productId, $imagePath)
    {
        $sql = '
            UPDATE ' . DB_PREFIX . 'product
            SET image = "' . $this->db->escape($imagePath) . '"
            WHERE product_id = ' . (int)$productId . '
            LIMIT 1
        ';

        $this->db->query($sql);
    }

    public function updateProductAdditionalPhoto($productId, $imagePath)
    {
        $productId = (int)$productId;
        $imagePath = $this->db->escape($imagePath);

        // если нет такого имага - инсертим
        $sql = 'SELECT product_image_id 
                FROM ' . DB_PREFIX . "product_image 
                WHERE product_id = {$productId} AND image='{$imagePath}'";

        if (!empty($this->db->query($sql)->row))
            return;

        $sql = '
            INSERT INTO 
                ' . DB_PREFIX . "product_image
            SET 
                product_id = {$productId},
                image = '{$imagePath}',
                sort_order = 0
        ";

        $this->db->query($sql);
    }

}