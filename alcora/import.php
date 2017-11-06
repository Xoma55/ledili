<?php
exit("Blocked");
/**
 * Created by PhpStorm.
 * User: vu
 * Date: 27.03.17
 * Time: 14:38
 * Description: import products and categories from OpenCart 1.5.* to OpenCart 2.*
 */
class import
{

    /**
     * Get data from first db
     *
     * @var object
    */
    private static $connFrom;

    /**
     * Get data from first db
     *
     * @var object
     */
    private static $connTo;

    /**
     * @var string
    */
    private static $_charset = 'utf8';

    /**
     * @var string
    */
    private $date;

    /**
     * @var integer
     */
    private $_statisticsCat = 0;

    /**
     * @var integer
     */
    private $_statisticsCatErrors = 0;

    /**
     * @var integer
     */
    private $_statisticsCatDesc = 0;

    /**
     * @var integer
     */
    private $_statisticsCatDescErrors = 0;

    /**
     * @var integer
     */
    private $_statisticsCatToStore = 0;

    /**
     * @var integer
     */
    private $_statisticsCatToStoreErrors = 0;

    /**
     * @var integer
     */
    private $_statisticsCatToLayout = 0;

    /**
     * @var integer
     */
    private $_statisticsCatToLayoutErrors = 0;

    /**
     * @var integer
     */
    private $_statisticsCatPath = 0;

    /**
     * @var integer
     */
    private $_statisticsCatPathErrors = 0;

    /**
     * @var integer
     */
    private $_statisticsProd = 0;

    /**
     * @var integer
     */
    private $_statisticsProdErrors = 0;

    /**
     * @var integer
     */
    private $_lanudage_id = 1;

    /**
     * @var integer
     */
    private $_store_id = 1;

    /**
     * @var integer
     */
    private $_layout_id = 1;

    /**
     * @var integer
     */
    public $level = 0;

    /**
     * @var array
     */
    public $_path = array();

    /**
     * All configuration with db
     *
     * @param  array $data
    */
    public function __construct($data = array())
    {

        static::$connFrom = new mysqli($data['from']['host'], $data['from']['user'], $data['from']['password'], $data['from']['db_name']);

        static::$connTo = new mysqli($data['to']['host'], $data['to']['user'], $data['to']['password'], $data['to']['db_name']);

        static::_checkConnect();

        static::_charsetDB();

        $this->date = date('Y-m-d H:i:s');

    }

    /**
     * Close connection DB
    */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.

        static::$connFrom->close();

        static::$connTo->close();
    }

    /**
     * @return int
     */
    public function getLayoutId()
    {
        return $this->_layout_id;
    }

    /**
     * @param int $layout_id
     */
    public function setLayoutId($layout_id)
    {
        $this->_layout_id = $layout_id;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_store_id;
    }

    /**
     * @param int $store_id
     */
    public function setStoreId($store_id)
    {
        $this->_store_id = $store_id;
    }

    /**
     * @return int
     */
    public function getLanudageId()
    {
        return $this->_lanudage_id;
    }

    /**
     * @param int $lanudage_id
     */
    public function setLanudageId($lanudage_id)
    {
        $this->_lanudage_id = $lanudage_id;
    }

    /**
     * Check connection DB
     * @return NULL
    */
    private static function _checkConnect()
    {
        if (static::$connFrom->connect_error)
            die("Connection failed to 'from' db: " . static::$connFrom->connect_error);

        if (static::$connTo->connect_error)
            die("Connection failed to second db: " . static::$connTo->connect_error);
    }

    /**
     * Set charsets to DB
     * @return NULL
    */
    private static function _charsetDB()
    {
        mysqli_set_charset(static::$connFrom, static::$_charset);
        mysqli_set_charset(static::$connTo, static::$_charset);
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        static::$_charset = $charset;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return static::$_charset;
    }

    # CLEAR CATEGORIES
    public function clearCategory()
    {
        $sql = "DELETE FROM category";

        static::$connTo->query("ALTER TABLE category AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearCategoryDesc()
    {
        $sql = "DELETE FROM category_description";

        static::$connTo->query("ALTER TABLE category_description AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearCategoryToStore()
    {
        $sql = "DELETE FROM category_to_store";

        static::$connTo->query("ALTER TABLE category_to_store AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearCategoryToLayout()
    {
        $sql = "DELETE FROM category_to_layout";

        static::$connTo->query("ALTER TABLE category_to_layout AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearCategoryPath()
    {
        $sql = "DELETE FROM category_path";

        static::$connTo->query("ALTER TABLE category_path AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }


    #CLEAR PRODUCTS
    public function clearProduct()
    {
        $sql = "DELETE FROM product";

        static::$connTo->query("ALTER TABLE product AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductDesc()
    {
        $sql = "DELETE FROM product_description";

        static::$connTo->query("ALTER TABLE product_description AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductToCategory()
    {
        $sql = "DELETE FROM product_to_category";

        static::$connTo->query("ALTER TABLE product_to_category AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductToStore()
    {
        $sql = "DELETE FROM product_to_store";

        static::$connTo->query("ALTER TABLE product_to_store AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductToLayout()
    {
        $sql = "DELETE FROM product_to_layout";

        static::$connTo->query("ALTER TABLE product_to_layout AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductAttribute()
    {
        $sql = "DELETE FROM product_attribute";

        static::$connTo->query("ALTER TABLE product_attribute AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductOption()
    {
        $sql = "DELETE FROM product_option";

        static::$connTo->query("ALTER TABLE product_option AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearProductOptionValue()
    {
        $sql = "DELETE FROM product_option_value";

        static::$connTo->query("ALTER TABLE product_option_value AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    /*public function clearProductValue()
    {
        $sql = "DELETE FROM product_option_value";

        static::$connTo->query("ALTER TABLE product_option_value AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }*/

    #CLEAR ATTRIBUTES
    public function clearAttribute()
    {
        $sql = "DELETE FROM attribute";

        static::$connTo->query("ALTER TABLE attribute AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearAttributeDescription()
    {
        $sql = "DELETE FROM attribute_description";

        static::$connTo->query("ALTER TABLE attribute_description AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearAttributeGroup()
    {
        $sql = "DELETE FROM attribute_group";

        static::$connTo->query("ALTER TABLE attribute_group AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    public function clearAttributeGroupDescription()
    {
        $sql = "DELETE FROM attribute_group_description";

        static::$connTo->query("ALTER TABLE attribute_group_description AUTO_INCREMENT = 1");

        return static::$connTo->query($sql) ? "Success" : static::$connTo->error;
    }

    /**
     * Select all data from category
    */
    public function getCategory()
    {
        $sql = "SELECT * FROM category";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while ($row = $result->fetch_assoc()){

            $this->insertCategory($row);
            $this->insertCategoryStore($row);
            $this->insertCategoryToLayout($row);

        }

    }

    /**
     * Set data for category
    */
    private function insertCategory($data)
    {
        $sql = "
        INSERT INTO category (`category_id`, `image`, `parent_id`, `top`, `column`, `sort_order`, `status`, `date_added`, `date_modified`) 
        VALUES ('" . (int)$data['category_id'] . "', '" . static::$connTo->real_escape_string($data['image']) . "', '" . (int)$data['parent_id'] . "', '" . (int)$data['top'] . ",', 
        '" . (int)$data['column'] . ",', '" . (int)$data['sort_order'] . ",', '" . (int)$data['status'] . ",', '" . static::$connTo->real_escape_string($this->date) . ",', 
        '" . static::$connTo->real_escape_string($this->date) . ",')";

        $this->_statisticsCat += 1;

        if(static::$connTo->query($sql))
        {

//            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

//            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;

            $this->_statisticsCatErrors += 1;
        }

    }

    /**
     * Select all data from category_description
    */
    public function getCategoryDesc()
    {
        $sql = "SELECT * FROM category_description WHERE language_id = 1";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while ($row = $result->fetch_assoc()){
            $this->insertCategoryDesc($row);

        }
    }

    /**
     * Set category description
     * @param array $data
    */
    private function insertCategoryDesc($data = array())
    {
        $sql = "INSERT INTO category_description(`category_id`, `language_id`, `name`) 
VALUES ('" . (int)$data['category_id'] . "', '" . (int)$this->_lanudage_id . "', '" . static::$connTo->real_escape_string($data['name']) . "')";

        $this->_statisticsCatDesc += 1;

        if(static::$connTo->query($sql))
        {

//            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

//            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsCatDescErrors += 1;
        }
    }


    /**
     * Set category to store
     * @param array $data
     */
    private function insertCategoryStore($data = array())
    {
        $sql = "INSERT INTO category_to_store(`category_id`, `store_id`) VALUES ('" . (int)$data['category_id'] . "', '0')";

        $this->_statisticsCatToStore += 1;

        if(static::$connTo->query($sql))
        {

//            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

//            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsCatToStoreErrors += 1;
        }

    }

    /**
     * Set category to layout
     * @param array $data
     */
    private function insertCategoryToLayout($data = array())
    {
        $sql = "INSERT INTO category_to_layout(`category_id`, `store_id`, `layout_id`) VALUES ('" . (int)$data['category_id'] . "', '0', '0')";

        $this->_statisticsCatToLayout += 1;

        if(static::$connTo->query($sql))
        {

//            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

//            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsCatToLayoutErrors+= 1;
        }

    }


    public $pathArr = array();


    public function getCategoryPath()
    {
        $sql = "SELECT * FROM category";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while ($row = $result->fetch_assoc()){
            $this->pathArr = array();

            $this->categoryPath($row['category_id']);
            $this->DataClosureTablePattern();


            $this->_path = array();

        }

    }

    /**
     * Create category path - from on parent id
     *
     * @params integer $id, integer $level
    */
    public function categoryPath($id = 0)
    {
        $sql = "SELECT parent_id, category_id FROM category WHERE category_id = '" . $id . "'";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;


        while ($row = $result->fetch_assoc())
        {

            $this->_path[] = array(
                $row
            );

            if(!empty($row['parent_id'])){
                 // search all parents
                $this->categoryPath($row['parent_id']);
            }


        }

    }

    /**
     * Used mysql pattern Closure Table Pattern
    */
    public function DataClosureTablePattern(){

        $lastID = 0;
        $level = 0;
        $pathArr = array();
        for($i = count($this->_path)-1; $i >= 0; $i--){
            $pathArr[] = array(
                'path_id' => $this->_path[$i][0]['category_id'],
                'level' => $level
            );

            $level++;

            $lastID = $this->_path[$i][0]['category_id'];
        }

        for($i=0; $i < count($pathArr); $i++){
            $this->insertPathCategory($lastID, $pathArr[$i]['path_id'], $i);
        }
    }

    private function insertPathCategory($current_id, $path, $level){

        $sql = "INSERT INTO category_path (`category_id`, `path_id`, `level`) VALUES('" . $current_id . "', '" . $path . "', '" . $level . "')";

        static::$connTo->query($sql);
    }


    /**
     * get all statistics about categories
    */
    public function getStatisticsCategories()
    {
        echo "<p> All statistics for category: " . $this->_statisticsCat . " <span style='color: red;'> Errors: " . $this->_statisticsCatErrors . "</span> <span style='color:green;'>Success: " . (int)($this->_statisticsCat - $this->_statisticsCatErrors) . "</span> </p>";
    }

    /**
     * get all statistics about products
     */
    public function getStatisticsProducts()
    {
        echo "<p> All statistics for products: " . $this->_statisticsProd . " <span style='color: red;'> Errors: " . $this->_statisticsProdErrors . "</span> <span style='color:green;'>Success: " . (int)($this->_statisticsProd - $this->_statisticsProdErrors) . "</span> </p>";
    }

    /**
     * get all statistics about attributtes
    */
    public function getStatitisticsAttribute()
    {

    }


    #Products

    public function getProducts()
    {
        $sql = "SELECT * FROM product";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProduct($row);
        }

    }

    private function insertProduct($data = array())
    {
        $sql = "
        INSERT INTO product (`product_id`, `model`, `sku`, `quantity`, `stock_status_id`, `image`, `shipping`, `price`, `weight`, `minimum`, `sort_order`, `status`, `date_added`, `date_modified`) 
        VALUES ('" . (int)$data['product_id'] . "', '" . (int)$data['model'] . "', '" . $data['sku'] . "', '" . $data['quantity'] . "', '" . $data['stock_status_id'] . "',
        '" . $data['image'] . "', '" . $data['shipping'] . "', '" . $data['price'] . "', '" . $data['weight'] . "', '" . $data['minimum'] . "', '" . $data['sort_order'] . "', 
        '" . $data['status'] . "', '" . $this->date . "', '" . $this->date . "');
        ";


        $this->_statisticsProd += 1;

        if(static::$connTo->query($sql))
        {

            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsProdErrors += 1;
        }

    }

    public function getProductDesc()
    {
        $sql = "SELECT * FROM product_description WHERE language_id = '1'";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductDesc($row);
        }
    }

    private function insertProductDesc($data = array())
    {
        $sql = "
        INSERT INTO product_description (`product_id`, `language_id`, `name`) 
        VALUES ('" . (int)$data['product_id'] . "', '" . (int)$this->_lanudage_id . "', '" . static::$connTo->real_escape_string($data['name']). "')
        ";

        $this->_statisticsProd += 1;

        if(static::$connTo->query($sql))
        {

            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsProdErrors += 1;
        }

    }

    public function getProductToCategory()
    {
        $sql = "SELECT * FROM product_to_category";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductToCategory($row);
        }
    }

    private function insertProductToCategory($data = array())
    {
        $sql = "
        INSERT INTO product_to_category (`product_id`, `category_id`, `main_category`) 
        VALUES ('" . (int)$data['product_id'] . "', '" . (int)$data['category_id'] . "', '" .(int)$data['main_category'] . "')
        ";

        $this->_statisticsProd += 1;

        if(static::$connTo->query($sql))
        {

            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsProdErrors += 1;
        }
    }

    public function getProductToStrore()
    {
        $sql = "SELECT * FROM product_to_store";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductToStroe($row);
        }
    }

    private function insertProductToStroe($data = array())
    {
        $sql = "INSERT INTO product_to_store (`product_id`, `store_id`) VALUES ('" . (int)$data['product_id'] . "', '0')";

        $this->_statisticsProd += 1;

        if(static::$connTo->query($sql))
        {

            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsProdErrors += 1;
        }
    }

    public function getProductAttribute()
    {
        $sql = "SELECT * FROM product_attribute";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductAttribute($row);
        }
    }

    private function insertProductAttribute($data = array())
    {
        $sql = "
        INSERT INTO product_attribute (`product_id`, `attribute_id`, `language_id`, `text`) 
        VALUES('" . $data['product_id'] . "', '" . $data['attribute_id'] . "', '" . (int)$this->_lanudage_id . "', '" . $data['text'] . "')";

        static::$connTo->query($sql);
    }

    public function getProductOption()
    {
        $sql = "SELECT * FROM product_option";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductOption($row);
        }
    }

    private function insertProductOption($data = array())
    {
        $sql ="
        INSERT INTO product_option (`product_option_id`, `product_id`, `option_id`, `value`, `required`) 
        VALUES ('" . $data['product_option_id'] . "', '" . $data['product_id'] . "', '" . $data['option_id'] . "',
        '" . $data['option_value'] . "', '" . $data['required'] . "')
        ";

        $this->_statisticsProd += 1;

        if(static::$connTo->query($sql))
        {

            echo "<p style='color:green;'>New category created successfully</p>";

        } else {

            echo "<p style='color:red;'></p>" . $sql . "- " . static::$connTo->error;;

            $this->_statisticsProdErrors += 1;
        }
    }

    public function getProductOptionValue()
    {
        $sql = "SELECT * FROM product_option_value";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductOptionValue($row);
        }
    }

    private function insertProductOptionValue($data = array())
    {
        $sql = "
        INSERT INTO product_option_value (`product_option_value_id`, `product_option_id`, `product_id`, `option_id`, `option_value_id`, `quantity`,
        `subtract`, `price`, `price_prefix`, `points`, `points_prefix`, `weight`, `weight_prefix`) 
        VALUES ('" . $data['product_option_value_id'] . "', '" . $data['product_option_id'] . "', '" . $data['product_id'] . "', '" . $data['option_id'] . "',
        '" . $data['option_value_id'] . "', '" . $data['quantity'] . "', '" . $data['subtract'] . "', '" . $data['price'] . "',
        '" . $data['price_prefix'] . "', '" . $data['points'] . "', '" . $data['points_prefix'] . "', '" . $data['weight'] . "',
        '" . $data['weight_prefix'] . "')
        ";

        static::$connTo->query($sql);
    }

    public function getProductLayout()
    {
        $sql = "SELECT * FROM product_to_layout";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertProductLayout($row);
        }
    }

    private function insertProductLayout($data = array())
    {
        $sql = "
        INSERT INTO product_to_layout (`product_id`, `store_id`, `layout_id`) 
        VALUES ('" . $data['product_id'] . "', '" . $data['store_id'] . "', '" . $data['layout_id'] . "')";

        static::$connTo->query($sql);
    }

    #attributes
    public function getAttribute(){
        $sql = "SELECT * FROM attribute";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertAttribute($row);
        }
    }

    /**
     * @param array $data
    */
    private function insertAttribute($data = array())
    {
        $sql = "INSERT INTO attribute (`attribute_id`, `attribute_group_id`, `sort_order`) 
        VALUES ('" . (int)$data['attribute_id'] . "', '" . (int)$data['attribute_group_id'] . "', '" . (int)$data['sort_order'] . "')";

        static::$connTo->query($sql);

    }

    public function getAttributeDescr()
    {
        $sql = "SELECT * FROM attribute_description";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertAttributeDescr($row);
        }
    }

    private function insertAttributeDescr($data = array())
    {
        $sql = "INSERT INTO attribute_description (`attribute_id`, `language_id`, `name`) 
        VALUES ('" . (int)$data['attribute_id'] . "', '" . (int)$this->_lanudage_id . "', '" . $data['name'] . "')";

        static::$connTo->query($sql);
    }

    public function getAttributeGroup()
    {
        $sql = "SELECT * FROM attribute_group";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertAttributeGroup($row);
        }
    }

    private function insertAttributeGroup($data = array())
    {
        $sql = "INSERT INTO attribute_group_description (`attribute_group_id`, `sort_order`) 
        VALUES ('" . (int)$data['attribute_group_id'] . "', '" . $data['sort_order'] . "')";

        static::$connTo->query($sql);
    }

    public function getAttributeGroupDescr()
    {
        $sql = "SELECT * FROM attribute_group_description";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertAttributeGroupDescr($row);
        }
    }

    private function insertAttributeGroupDescr($data = array())
    {
        $sql = "INSERT INTO attribute_group_description (`attribute_group_id`, `language_id`, `name`) 
        VALUES ('" . (int)$data['attribute_group_id'] . "', '" . $this->_lanudage_id . "', '" . $data['name'] . "')";

        static::$connTo->query($sql);
    }

    #options

    public function getOption()
    {
        $sql = "SELECT * FROM option";

        $result = static::$connFrom->query($sql);

        if($result->num_rows <= 0)
            return;

        while($row = $result->fetch_assoc()){
            $this->insertOption($row);
        }
    }

    private function insertOption($data = array())
    {
        $sql = "
        INSERT INTO option (`option_id`, `type`, `	sort_order`) 
        VALUES('" . $data['option_id'] . "', '" . $data['type'] . "', '" . $data['sort_order'] . "') 
        ";

        static::$connTo->query($sql);
    }

    public function getOptionValue()
    {

    }

    private function insertOptionValue()
    {

    }

    public function getOptionValueDesc()
    {

    }

    private function insertOptionValueDescr()
    {

    }

}


$_fromData = [
    'host'      => 'localhost',
    'user'      => 'root',
    'password'  => '',
    'db_name'   => 'kodi_ua',
];

$_toData = [
    'host'      => 'localhost',
    'user'      => 'root',
    'password'  => '',
    'db_name'   => 'ledili_dev',
];

$inputParams = [
    'from'     =>  $_fromData,
    'to'    =>  $_toData,
];

#input params - array
$obj = new import($inputParams);

$obj->setLanudageId(2);

#Attribute

echo $obj->clearAttribute();
echo $obj->clearAttributeDescription();
echo $obj->clearAttributeGroup();
echo $obj->clearAttributeGroupDescription();

$obj->getAttribute();
$obj->getAttributeDescr();
$obj->getAttributeGroup();
$obj->getAttributeGroupDescr();



#Category

echo $obj->clearCategory();
echo $obj->clearCategoryDesc();
echo $obj->clearCategoryToLayout();
echo $obj->clearCategoryToStore();
echo $obj->clearCategoryPath();

$obj->getCategoryPath();
$obj->getCategory();
$obj->getCategoryDesc();

#Products

echo $obj->clearProduct();
echo $obj->clearProductDesc();
echo $obj->clearProductToCategory();
echo $obj->clearProductToStore();
echo $obj->clearProductToLayout();
echo $obj->clearProductOption();
echo $obj->clearProductOptionValue();
echo $obj->clearProductAttribute();

$obj->getProducts();
$obj->getProductDesc();
$obj->getProductToCategory();
$obj->getProductToStrore();
$obj->getProductOption();

$obj->getProductAttribute();


//$obj->getStatisticsProducts();
