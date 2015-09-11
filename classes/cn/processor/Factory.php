<?php

namespace cn\processor;

class Factory {

    public static function make($item_data) {
        $callnumber = $item_data['call_number'];
        $location = $item_data['location'];

        $cd_check = preg_match("/^[a-zA-Z]{4}/",$callnumber);
        if ($cd_check!==FALSE && $cd_check===1) {
            return new CD($callnumber, $location);
        } else {
            $segments = explode(' ',$callnumber);
            $prefix = "";
            $number = "";
            $cutter = "";

            $type = "";
            foreach($segments as $segment) {
                if ($number === "") {
                    $seg_arr = str_split($segment);
                    if ($seg_arr[count($seg_arr)-1]==='.' || $seg_arr[0]==='.' || stripos($segment,'-') !== FALSE) {
                        $prefix .= "$segment ";
                        continue;
                    }

                    if (preg_match('/^[A-Z]+[0-9]+/',$segment)===1) {
                        $number = $segment;
                        $type = "lc";
                        continue;
                    }

                    if (preg_match('/^[0-9]{3}/',$segment)===1) {
                        $number = $segment;
                        $type = "dewey";
                        continue;
                    }

                    $prefix .= "$segment ";
                    continue;
                }

                $cutter .= "$segment ";
            }

            $prefix = trim($prefix);
            $number = trim($number);
            $cutter = trim ($cutter);

            if ($type === "lc") {
                return new LC($prefix,$number,$cutter);
            } else if ($type === "dewey") {
                return new Dewey($prefix,$number,$cutter);
            } else {
                return new Generic($segments);
            }
        }
    }
}