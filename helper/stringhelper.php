<?php

class StringHelper {

    /**
     * @param $p_sFormatted
     * @return int|null|string
     */
    public static function return_bytes($p_sFormatted) {
        $p_sFormatted = (string)$p_sFormatted;
        $aUnits = [
            'B'=>0,
            'KB'=>1,
            'MB'=>2,
            'GB'=>3,
            'TB'=>4,
            'PB'=>5,
            'EB'=>6,
            'ZB'=>7,
            'YB'=>8
        ];
        foreach(str_split($p_sFormatted) as $index=>$letter) {
            if(ctype_alpha($letter)) {
                $sUnit = strtoupper(trim(substr($p_sFormatted, $index)));
                $iUnits = trim(substr($p_sFormatted, 0, $index));
                break;
            }
        }
        if(empty($sUnit)) {
            return (int)$p_sFormatted;
        }
        if(!in_array($sUnit, array_keys($aUnits))) {
            return null;
        }
        return $iUnits * pow(1024, $aUnits[$sUnit]);
    }

    /**
     * @param $string
     * @return string
     */
    public static function camelcase_to_dash($string) {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $string));
    }
}