<?php

/**
 * Description of markup
 *
 * @author Faizan Ayubi
 */

namespace Shared {

    class Markup {

        public function __construct() {
            // do nothing
        }

        public function __clone() {
            // do nothing
        }

        public static function errors($array, $key, $separator = "<br />", $before = "<br />", $after = "") {
            if (isset($array[$key])) {
                return $before . join($separator, $array[$key]) . $after;
            }
            return "";
        }

        public static function pagination($page) {
            if (strpos(URL, "?")) {
                $request = explode("?", URL);
                if (strpos($request[1], "&")) {
                    parse_str($request[1], $params);
                }

                $params["page"] = $page;
                return $request[0]."?".http_build_query($params);
            } else {
                $params["page"] = $page;
                return URL."?".http_build_query($params);
            }
            return "";
        }

        public static function generateSalt($length = 22) {
            $unique_random_string = md5(uniqid(mt_rand(), true));
            $base64_string = base64_encode($unique_random_string);
            $modified_base64_string = str_replace('+', '.', $base64_string);

            $salt = substr($modified_base64_string, 0, $length);
            return $salt;
        }

        public static function encrypt($password) {
            $hash_format = "$2y$10$";
            $salt = self::generateSalt();
            $format_and_salt = $hash_format . $salt;
            $hash = crypt($password, $format_and_salt);
            return $hash;
        }

        public static function checkHash($password, $existingHash) {
            //existing hash contains format and salt or start
           $hash = crypt($password, $existingHash);
           if ($hash == $existingHash) {
               return true;
           } else {
               return false;
           }
        }

    }

}