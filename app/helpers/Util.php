<?php
/**
 * Some help function
 */

class Util {

    public static function config($name=null, $value=null) {
        static $data = array();
        if (is_array($name)) {
            $data = array_merge($data, $name);
            return;
        }
        if ($name===null) return $data;
        if ($value===null) {
            if (!isset($data[$name])) {
                throw new Exception("not found $name");
            }
            return $data[$name];
        }
        $data[$name] = $value;
    }

    public static function arrayOnly($array, $keys) {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    public static function route($class_method) {
        list($class, $method) = explode('@', $class_method);
        return function() use ($class, $method) {
            return call_user_func_array(array(new $class(), $method), func_get_args());
        };
    }

}

