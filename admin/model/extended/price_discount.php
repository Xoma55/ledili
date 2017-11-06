<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 06.11.17
 * Time: 10:21
 */

class ModelExtendedPriceDiscount extends Model {

    public function getDiscountList() {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "price_discount` ORDER BY threshold");
        return $query->rows;
    }

    public function delete($row_id) {
        $query = $this->db->query("DELETE FROM `" . DB_PREFIX . "price_discount` WHERE row_id='".(int)$row_id."'");
    }

    public function add($data) {
        $query = $this->db->query("INSERT INTO `" . DB_PREFIX . "price_discount` SET threshold='".(int)$data['price_discount_threshold']."',discount='".(int)$data['price_discount_modal_discount']."'");
    }

    public function edit($data) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "price_discount` SET threshold='".(int)$data['price_discount_threshold']."',discount='".(int)$data['price_discount_modal_discount']."' WHERE row_id='".(int)$data['row_id']."'");
    }

}