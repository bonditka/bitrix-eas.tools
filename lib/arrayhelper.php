<?php
/**
 * Description of textMain
 *
 * @author BONDitka
 */
namespace Eas\Tools;

class arrayHelper {
    public static function ksort_recursive(&$array) {
        ksort($array);
        foreach (array_keys($array) as $k) {
            if (is_array($array[$k])) {
                self::ksort_recursive($array[$k]);
            }
        }
    }

    public static function array_search_by_key($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value)
                $results[] = $array;

            foreach ($array as $subarray)
                $results = array_merge($results, textMain::array_search_by_key($subarray, $key, $value));
        }

        return $results;
    }

    public static function array_merge_recursive_distinct(array &$array1, array &$array2) {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }
        return $merged;
    }
}