<?php

class ValidateString
{

    public function check_node_id($nodeId): bool
    {
        $node_arr = array(1, 2, 3);
        if (in_array($nodeId, $node_arr)) {
            return true;
        }
        return false;
    }

    public function check_province_id($provinceId): bool
    {
        $provinceId_arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18);
        if (in_array($provinceId, $provinceId_arr)) {
            return true;
        }
        return false;
    }

    public static function checkNodeId($nodeId): bool
    {
        $node_arr = array(1, 2, 3);
        if (in_array($nodeId, $node_arr)) {
            return true;
        }
        return false;
    }

    public static function checkProvince($provinceId): bool
    {
        $provinceId_arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18);
        if (in_array($provinceId, $provinceId_arr)) {
            return true;
        }
        return false;
    }
    
    public static function checkTireType($tireType): bool
    {
        $tireType_arr = array(1,2,3,4);
        if (in_array($tireType, $tireType_arr)) {
            return true;
        }
        return false;
    }
}
