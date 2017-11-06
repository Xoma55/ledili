<?php
/**
 * Created by PhpStorm.
 * User: ag
 * Date: 03.12.16
 * Time: 12:31
 */

namespace Cache;

class ExtFile extends File
{
//    static $cnt = 0;

    public function get($key)
    {
        $files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

//        self::$cnt++;

        if ($files && file_exists($files[0]))
        {
            $handle = fopen($files[0], 'r');

            flock($handle, LOCK_SH);

            $data = fread($handle, filesize($files[0]));

            flock($handle, LOCK_UN);

            fclose($handle);

            return json_decode($data, true);
        }

        return false;
    }
}