<?php

class ControllerCommonSeoPro extends Controller
{
    private $cache_data = null;

    private $_routesToCreateSeoUrls = array(
        'product/product',
        'product/category',
        'product/manufacturer/info',
        'pavblog/blog'
    );


    public function __construct($registry) {

        parent::__construct($registry);

        $this->cache_data = $this->cache->get('seo_pro');

        if (empty($this->cache_data))
            $this->_refreshSeoCache();

        $this->registry->set('seoPro', $this);
    }

    private function _refreshSeoCache()
    {
        $query = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias ORDER BY url_alias_id");
        $this->cache_data = array();
        foreach ($query->rows as $row) {
            if (isset($this->cache_data['keywords'][$row['keyword']])){
                $this->cache_data['keywords'][$row['query']] = $this->cache_data['keywords'][$row['keyword']];
                continue;
            }
            $this->cache_data['keywords'][$row['keyword']] = $row['query'];
            $this->cache_data['queries'][$row['query']] = $row['keyword'];
        }

        $this->cache->set('seo_pro', $this->cache_data);
    }

    public function index()
    {
        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
            // OCFilter start
            if (!is_null($this->registry->get('ocfilter'))) {
                $this->url->addRewrite($this->registry->get('ocfilter'));
            }
            // OCFilter end
        } else {
            return;
        }

        // Decode URL
        if (!isset($this->request->get['_route_'])) {
            $this->validate();
        } else {
            $route_ = $route = $this->request->get['_route_'];
            unset($this->request->get['_route_']);
            $parts = explode('/', trim(utf8_strtolower($route), '/'));
            list($last_part) = explode('.', array_pop($parts));
            array_push($parts, $last_part);

            $rows = array();
            foreach ($parts as $keyword) {
                if (isset($this->cache_data['keywords'][$keyword])) {
                    $rows[] = array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]);
                }
            }

            if (isset($this->cache_data['keywords'][$route])) {
                $keyword = $route;
                $parts = array($keyword);
                $rows = array(array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]));
            }

            if (count($rows) == sizeof($parts)) {
                $queries = array();
                foreach ($rows as $row) {
                    $queries[utf8_strtolower($row['keyword'])] = $row['query'];
                }

                reset($parts);
                foreach ($parts as $part) {
                    if (!isset($queries[$part])) {
                        return false;
                    }
                    $url = explode('=', $queries[$part], 2);

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    } elseif (count($url) > 1) {
                        $this->request->get[$url[0]] = $url[1];
                    }
                }
            } else {
                $this->request->get['route'] = 'error/not_found';
            }

            if (isset($this->request->get['product_id'])) {
                $this->request->get['route'] = 'product/product';
                if (!isset($this->request->get['path'])) {
                    $path = $this->getPathByProduct($this->request->get['product_id']);
                    if ($path) {
                        $this->request->get['path'] = $path;
                    }
                }
            } elseif (isset($this->request->get['path'])) {
                $this->request->get['route'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['route'] = 'product/manufacturer/info';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/information';
            } elseif (isset($this->cache_data['queries'][$route_])) {
                header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
                $this->response->redirect($this->cache_data['queries'][$route_]);
            } else {
                if (isset($queries[$parts[0]])) {
                    $this->request->get['route'] = $queries[$parts[0]];
                }
            }

            $this->validate();

            if (isset($this->request->get['route'])) {
                return new Action($this->request->get['route']);
            }
        }
    }

    public function rewrite($link)
    {
        if (!$this->config->get('config_seo_url')) {
            return $link;
        }

        $seo_url = '';

        $component = parse_url(str_replace('&amp;', '&', $link));

        $data = array();
        parse_str($component['query'], $data);

        $route = $data['route'];
        unset($data['route']);

        switch ($route) {
            case 'product/product':
                if (isset($data['product_id'])) {
                    $tmp = $data;
                    $data = array();
                    if ($this->config->get('config_seo_url_include_path')) {
                        $data['path'] = $this->getPathByProduct($tmp['product_id']);
                        if (!$data['path']) {
                            return $link;
                        }
                    }
                    $data['product_id'] = $tmp['product_id'];
                    if (isset($tmp['tracking'])) {
                        $data['tracking'] = $tmp['tracking'];
                    }
                }
                break;

            case 'product/category':
                if (isset($data['path'])) {
                    $category = explode('_', $data['path']);
                    $category = end($category);
                    $data['path'] = $this->getPathByCategory($category);
                    if (!$data['path']) {
                        return $link;
                    }
                }
                break;

            case 'product/product/review':
            case 'information/information/info':
                return $link;
                break;

            default:
                break;
        }

        if ($component['scheme'] == 'https') {
            $link = $this->config->get('config_ssl');
        } else {
            $link = $this->config->get('config_url');
        }

        $link .= 'index.php?route=' . $route;

        if (count($data)) {
            $link .= '&amp;' . urldecode(http_build_query($data, '', '&amp;'));
        }

        $queries = array();
        if (!in_array($route, array('product/search'))) {
            foreach ($data as $key => $value) {
                switch ($key) {
                    case 'product_id':
                    case 'manufacturer_id':
                    case 'category_id':
                    case 'information_id':
                    case 'order_id':
                        $queries[] = $key . '=' . $value;
                        unset($data[$key]);
                        $postfix = 1;
                        break;

                    case 'path':
                        $categories = explode('_', $value);
                        foreach ($categories as $category) {
                            $queries[] = 'category_id=' . $category;
                        }
                        unset($data[$key]);
                        break;

                    default:
                        break;
                }
            }
        }

        if (empty($queries)) {
            $queries[] = $route;
        }

        $rows = array();
        foreach ($queries as $query) {
            if (isset($this->cache_data['queries'][$query])) {
                $rows[] = array('query' => $query, 'keyword' => $this->cache_data['queries'][$query]);
            }
        }

        if (count($rows) == count($queries)) {
            $aliases = array();
            foreach ($rows as $row) {
                $aliases[$row['query']] = $row['keyword'];
            }
            foreach ($queries as $query) {
                $seo_url .= '/' . rawurlencode($aliases[$query]);
            }
        }

        if ($seo_url == '') {
            return $link;
        }

        $seo_url = trim($seo_url, '/');

        if ($component['scheme'] == 'https') {
            $seo_url = $this->config->get('config_ssl') . $seo_url;
        } else {
            $seo_url = $this->config->get('config_url') . $seo_url;
        }

        if (isset($postfix)) {
            $seo_url .= trim($this->config->get('config_seo_url_postfix'));
        } else {
            $seo_url .= '/';
        }

        if (substr($seo_url, -2) == '//') {
            $seo_url = substr($seo_url, 0, -1);
        }

        if (count($data)) {
            $seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
        }

        return $seo_url;
    }

    private function getPathByProduct($product_id)
    {
        $product_id = (int)$product_id;
        if ($product_id < 1) {
            return false;
        }

        static $path = null;
        if (!isset($path)) {
            $path = $this->cache->get('product.seopath');
            if (!isset($path)) {
                $path = array();
            }
        }

        if (!isset($path[$product_id])) {
            $query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "' ORDER BY main_category DESC LIMIT 1");

            $path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);

            $this->cache->set('product.seopath', $path);
        }

        return $path[$product_id];
    }

    private function getPathByCategory($category_id)
    {
        $category_id = (int)$category_id;
        if ($category_id < 1) {
            return false;
        }

        static $path = null;
        if (!isset($path)) {
            $path = $this->cache->get('category.seopath');
            if (!isset($path)) {
                $path = array();
            }
        }

        if (!isset($path[$category_id])) {
            $max_level = 10;

            $sql = "SELECT CONCAT_WS('_'";
            for ($i = $max_level - 1; $i >= 0; --$i) {
                $sql .= ",t$i.category_id";
            }
            $sql .= ") AS path FROM " . DB_PREFIX . "category t0";
            for ($i = 1; $i < $max_level; ++$i) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i - 1) . ".parent_id)";
            }
            $sql .= " WHERE t0.category_id = '" . $category_id . "'";

            $query = $this->db->query($sql);

            $path[$category_id] = $query->num_rows ? $query->row['path'] : false;

            $this->cache->set('category.seopath', $path);
        }

        return $path[$category_id];
    }

    private function validate()
    {
        if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
            return;
        }
        if (ltrim($this->request->server['REQUEST_URI'], '/') == 'sitemap.xml') {
            $this->request->get['route'] = 'feed/google_sitemap';
            return;
        }

        if (empty($this->request->get['route'])) {
            $this->request->get['route'] = 'common/home';
        }

        if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return;
        }

        $url = $this->_buildUrlFromQuery();
        $seo = $this->_buildSeoUrlFromQuery();

        //если нет ЧПУшного урла для любого из єлементов - делаем его
        if ($this->_conditionToSetSeoUrl($seo))
            $seo = $this->_createSeoUrlPart();

        $this->_routeRedirects();

        if (rawurldecode(str_replace(FULL_HOST, '', $url)) != rawurldecode(str_replace(FULL_HOST, '', $seo))) {
            header($this->request->server['SERVER_PROTOCOL'] . ' 301 Moved Permanently');
            $this->response->redirect($seo, 301);
        }
    }

    private function _routeRedirects()
    {
        $redirects = array(
            'checkout/cart' => 'checkout/oct_fastorder'
        );

        if (isset($redirects[$this->request->get['route']]))
            $this->response->redirect($this->url->link($redirects[$this->request->get['route']]), 301);

    }

    private function _buildUrlFromQuery()
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $config_ssl = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);
            return str_replace('&amp;', '&', $config_ssl . ltrim($this->request->server['REQUEST_URI'], '/'));
        } else {
            $config_url = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);
            return str_replace('&amp;', '&', $config_url . ltrim($this->request->server['REQUEST_URI'], '/'));
        }
    }

    private function _buildSeoUrlFromQuery()
    {
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            return str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), true));
        } else {
            return str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), false));
        }
    }

    private function strpos_offset($needle, $haystack, $occurrence)
    {
        // explode the haystack
        $arr = explode($needle, $haystack);
        // check the needle is not out of bounds
        switch ($occurrence) {
            case $occurrence == 0:
                return false;
            case $occurrence > max(array_keys($arr)):
                return false;
            default:
                return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
        }
    }

    private function _conditionToSetSeoUrl($seoUrl)
    {
        if (
            empty($this->request->get)
            || empty($this->request->get['route'])
            || !in_array($this->request->get['route'], $this->_routesToCreateSeoUrls)
        ) return false;

        foreach ($this->request->get as $param)
            if (strpos($seoUrl, $param) === false)
                return false;

        return true;
    }

    private function _createSeoUrlPart()
    {
        // делаем  ЧПУ для категорий
        if (!empty($this->request->get['path'])) {
            $categories = explode('_', $this->request->get['path']);
            foreach ($categories as $catId) {
                $this->_createSeoUrlForCategory($catId);
            }
        }

        // делаем  ЧПУ для category_id
        if (!empty($this->request->get['category_id']))
            $this->_createSeoUrlForCategory($this->request->get['category_id']);

        // делаем  ЧПУ для product_id
        if (!empty($this->request->get['product_id']))
            $this->_createSeoUrlForProduct($this->request->get['product_id']);

        //делаем ЧПУ для блога новостей
        if ($this->request->get['route'] === 'pavblog/blog' && !empty($this->request->get['id']))
            $this->_creteSeoUrlForBlog($this->request->get['id']);

        //делаем ЧПУ для route='product/manufacturer/info'
        if ($this->request->get['route'] === 'product/manufacturer/info' && !empty($this->request->get['manufacturer_id']))
            $this->_creteSeoUrlForManufacturer($this->request->get['manufacturer_id']);



        $this->_refreshSeoCache();

        return $this->_buildSeoUrlFromQuery();
    }

    private function _creteSeoUrlForManufacturer($id)
    {
        $query = 'manufacturer_id=' . $id;

        //а нет ли уже ЧПУ
        if (!isset($this->cache_data['queries'][$query]) || $this->cache_data['queries'][$query] === 'common/home') {

            if (empty($this->model_catalog_manufacturer))
                $this->load->model('catalog/manufacturer');

            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($id);
            $this->addSeoUrl($query, $manufacturer['name']);
        }
    }

    private function _creteSeoUrlForBlog($id)
    {
        $query = 'pavblog/blog=' . $id;

        //а нет ли уже ЧПУ
        if (!isset($this->cache_data['queries'][$query]) || $this->cache_data['queries'][$query] === 'common/home') {

            if (empty($this->model_pavblog_blog))
                $this->load->model('pavblog/blog');

            $blog = $this->model_pavblog_blog->getInfo($id);
            $this->addSeoUrl($query, $blog['title']);
        }
    }

    private function _createSeoUrlForCategory($id)
    {
        $query = 'category_id=' . $id;

        //а нет ли уже ЧПУ
        if (!isset($this->cache_data['queries'][$query]) || $this->cache_data['queries'][$query] === 'common/home') {

            if (empty($this->model_catalog_category))
                $this->load->model('catalog/category');

            $category = $this->model_catalog_category->getCategory($id);
            $this->addSeoUrl($query, $category['name']);
        }
    }

    private function _createSeoUrlForProduct($id)
    {
        $query = 'product_id=' . $id;

        //а нет ли уже ЧПУ
        if (!isset($this->cache_data['queries'][$query]) || $this->cache_data['queries'][$query] === 'common/home') {

            if (empty($this->model_catalog_product))
                $this->load->model('catalog/product');

            $product = $this->model_catalog_product->getProduct($id);
            $this->addSeoUrl($query, $product['name']);
        }
    }


    public function addSeoUrl($query, $keywordRUS)
    {
        $query = $this->db->escape($query);
        $uniqueKeyword = $keyword = $this->db->escape(self::str2url($keywordRUS));

        $modificator = 0;
        while (in_array($uniqueKeyword, $this->cache_data['queries']))
            $uniqueKeyword = $keyword . ++$modificator;

        $keyword = $uniqueKeyword;

        //обновляем данніе про урлы
        if (isset($this->cache_data['queries'][$query]) && $this->cache_data['queries'][$query] === 'common/home') {
            //значит значение УРЛа у єтого єелемента пустое - деаем апдейт
            $sql = 'UPDATE ' . DB_PREFIX . "url_alias SET keyword='{$keyword}' WHERE `query`='{$query}' LIMIT 1";
            $this->db->query($sql);
        } else {
            //такого єлемента нет - делаем инсерт
            $sql = 'INSERT INTO ' . DB_PREFIX . "url_alias SET query='{$query}', keyword='{$keyword}'";
            $this->db->query($sql);

        }

        $this->cache_data['keywords'][$keyword] = $query;
        $this->cache_data['queries'][$query] = $keyword;
    }


    private function getQueryString($exclude = array())
    {
        if (!is_array($exclude)) {
            $exclude = array();
        }

        return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
    }

    public static function strTranslit($str) {

        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );

        return strtr($str, $converter);
    }

    public static function str2url($str) {

        $str = strtolower(self::strTranslit($str));

        $str = preg_replace('#[^-a-z0-9]+|_#u', '-', $str);

        $str = preg_replace('#[-]{2,}#u', '-', $str);

        $str = trim($str, "-");

        return $str;
    }
}

