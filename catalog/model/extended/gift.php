<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 13.11.17
 * Time: 11:02
 */

class ModelExtendedGift extends Model {

    public function getGiftsByTotal($total=0) {

        if($total!=0) {

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "price_gift WHERE threshold<='".(int)$total."' ORDER BY threshold DESC LIMIT 0,1");

            if($query) {
                $threshold=$query->row;
                $products = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_gift WHERE threshold_id='".(int)$threshold['threshold_id']."'");
                return $products->rows;
            }

        }

    }

}