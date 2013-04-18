<?php

/**
 *
 *
 */

class attrs
{
    /* TC Kimlik numarasının algoritmasını kontrol eder
     * 
     * @param int $TCKN
     * @return boolean
     */
    function checkTCKN($TCKN)
    {
        if (strlen($TCKN) != 11) {
            return false;
        }

        $nums = str_split($TCKN);

        foreach ($nums as $key => $value):
            if ($key <= 8):
                ($key % 2 == 0) ? $single_total += $value : $couple_total += $value;
                $ten_total += $value;
            endif;
        endforeach;

        if (((($single_total * 7) - $couple_total) % 10) != $nums[9]) {
            return false;
        }

        $ten_total += $nums[9];

        if (($ten_total % 10) != $nums[10]) {
            return false;
        }

        return true;
    }


}