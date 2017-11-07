<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 07.11.17
 * Time: 9:43
 */

class ModelExtendedPriceDiscount extends Model {

    public function getPriceDiscounts() {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_discount ORDER BY threshold DESC");

        return $query->rows;

    }

}