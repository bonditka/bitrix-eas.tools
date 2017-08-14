<?php
/**
 * Description of arrayHelper
 *
 * @author BONDitka
 */
namespace Eas\Tools;

class arrayHelper {
    public static function ksort_recursive(&$array) {
        ksort($array);
        foreach (array_keys($array) as $k) {
            if (is_array($array[$k])) {
                static::ksort_recursive($array[$k]);
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
                $merged [$key] = static::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }
        return $merged;
    }

    public static function array_diff_assoc_recursive($array1, $array2) {
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = static::array_diff_assoc_recursive($value, $array2[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!array_key_exists($key, $array2) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? 0 : $difference;
    }

    public static function array_search_recursive($needle, $haystack) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value OR ( is_array($value) && static::array_search_recursive($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

}