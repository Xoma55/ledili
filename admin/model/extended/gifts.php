<?php
/**
 * Created by PhpStorm.
 * User: xoma55
 * Date: 09.11.17
 * Time: 16:35
 */

class ModelExtendedGifts extends Model {

    public function getDiscountList() {

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "price_gift` ORDER BY threshold");
        return $query->rows;

    }

    public function getProductsByThresholdId($threshold_id=0) {

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_gift` WHERE threshold_id='".(int)$threshold_id."'");
        return $query->rows;
    }

    public function getProducts($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
        }

        if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }

        if (!empty($data['filter_selected'])) {
            $sql .= " AND p.product_id NOT IN" . $this->db->escape($data['filter_selected']) ;
        }


        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function add($threshold,$data) {

        $query=$this->db->query("SELECT DISTINCT threshold_id FROM ".DB_PREFIX."price_gift WHERE threshold='".(int)$threshold."'");

        if ($query->num_rows) {
            $tData = $query->row;
            $threshold_id=$tData['threshold_id'];
            $this->db->query("DELETE FROM ".DB_PREFIX."product_gift WHERE threshold_id='".$threshold_id."'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "price_gift SET threshold='" . (int)$threshold . "'");
            $threshold_id=$this->db->getLastId();
        }

        foreach ($data as $item) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_gift SET threshold_id='" . (int)$threshold_id . "',product_id='" . (int)$item['product_id'] . "'");
        }
    }

    public function getThresholdData($threshold_id=0) {

        $query=$this->db->query("SELECT * FROM ".DB_PREFIX."product_gift WHERE threshold_id='".(int)$threshold_id."'");
        return $query->rows;

    }

    public function delete($threshold_id) {
        $this->db->query("DELETE FROM ".DB_PREFIX."price_gift WHERE threshold_id='".(int)$threshold_id."'");
        $this->db->query("DELETE FROM ".DB_PREFIX."product_gift WHERE threshold_id='".(int)$threshold_id."'");
    }

}