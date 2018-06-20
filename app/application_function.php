<?php
/**
 * application function 
 *
 * @author  Yohei Yoshikawa
 * 
 * Copyright (c) 2013 Yohei Yoshikawa (http://yoo-s.com/)
 */

/**
 * title for measure_unit
 *
 * @param string $measure_unit
 * @return void
 */
function titleForMeasureUnit($title, $measure_unit = 'mm') {
    if ($measure_unit) {
        return "{$title}[{$measure_unit}]";
    } else {
        return $title;
    }
}

/**
 * 計測値データフォーマット
 * 
 * @param  float $value
 * @param  int $rank
 * @param  string $unit
 * @return string
 */
function convertFormat($value, $rank, $unit='') {
    if (!is_numeric($value)) {
        return '';
    } elseif ($unit == 'rad') {
        $value = alterDigit($value);
        return $value;
    } else {
        return number_format($value, $rank, '.', '');
    }
}

/**
 *  水平合成計算
 *  
 *  @param  double  $x
 *  @param  double  $y
 *  @return  double  $value
 */
function horizonCalculate($x , $y) {
    if (!is_numeric($x) || !is_numeric($y)) {
        return null;
    } else {
        $value = sqrt(pow($x, 2) + pow($y, 2));
        return $value;
    }
}

/**
 *  全合成計算
 *  
 *  @param  double  $x
 *  @param  double  $y
 *  @param  double  $z
 *  @return  double  $value
 */
function totalCalculate($x ,$y ,$z) {
    if (!is_numeric($x) || !is_numeric($y) || !is_numeric($z)) {
        return null;
    } else {
        $value = sqrt(pow($x, 2) + pow($y, 2) + pow($z, 2));
        return $value;
    }
}

/**
* 時系列データ値有効数字変換
*
* @param   float $value
* @param   string $type
* @param   boolean $is_list_value
* @return  float
*/
function listValueFormat($value, $round=0, $is_list_value=false) {
    if (is_null($value) && $is_list_value) return '-';
    if (is_numeric($value)) {
        if ($round != 0) return number_format($value, $round);
        return $value;
    }
}

//TODO
/**
* 軸ラベル：日付フォーマットに変更
*
* @param integer $time
* @return string
*/
function dateCallback($time) {
    return Date('y/m/d', $time);
}

/**
* Y軸ラベル：数値フォーマット
*
* @param float $value
* @return string
*/
function numberCallback($value) {
    if (abs($value) < 0.00000000000001) {
        return 0;
    } else if (abs($value) >= 100000) {

    } else {
        $value = round($value, 3);
    }
    return $value;
}

/**
* Y軸ラベル：数値フォーマット反転
*
* @param float $value
* @return string
*/
function numberReverseCallback($value) {
    if (is_numeric($value) && $value != 0) $value*= -1;
    return numberCallback($value);
}

/**
* 深度Y軸ラベルフォーマット
*
* @param integer $value
* @return string
*/
function depthValueYCallback($value) {
    if (is_numeric($value)) {
        if ($value < 0) $value*= -1;
        if ($value != 0) {
            if ($value < 1) {
                return $value;
            } else {
                $flag = ($value * 10) % $value;
            }
        }
        if ($flag) {
            return number_format($value, 1);
        } else {
            return $value;
        }
    }
}

/**
* 深度Y軸ラベルフォーマット
*
* @param integer $value
* @return string
*/
function depthMeterYCallback($value) {
    $value = abs($value);
    $label = "{$value}m";
    return $label;
}