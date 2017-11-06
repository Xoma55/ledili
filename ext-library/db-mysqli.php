<?php
/**
 * Created by PhpStorm.
 * User: ag
 * Date: 03.12.16
 * Time: 13:54
 */

namespace DB;

/**
 * Full replication from MySQLi class for extend
 */
final class ExtMySQLi
{
    const SLOW_QUERY_TIME = 2;

    private $link;

    public function __construct($hostname, $username, $password, $database, $port = '3306')
    {
        $this->link = new \mysqli($hostname, $username, $password, $database, $port);

        if ($this->link->connect_error) {
            trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
            exit();
        }

        $this->link->set_charset("utf8");
        $this->link->query("SET SQL_MODE = ''");
        // ag		$this->link->query("SET time_zone = 'Europe/Kiev'");
    }

    public function query($sql)
    {
        $s = microtime(true);

        $query = $this->link->query($sql);

        $queryTime = microtime(true) - $s;

        if($queryTime > self::SLOW_QUERY_TIME)
            trigger_error('Slow query: <br />Query time is: ' . $queryTime . '<br />' . $sql, E_USER_WARNING);

        if (!$this->link->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : array();
                $result->rows = $data;

                $query->close();

                return $result;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . $this->link->error . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
        }
    }

    public function escape($value)
    {
        return $this->link->real_escape_string($value);
    }

    public function countAffected()
    {
        return $this->link->affected_rows;
    }

    public function getLastId()
    {
        return $this->link->insert_id;
    }

    public function __destruct()
    {
        $this->link->close();
    }
}