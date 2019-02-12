<?php
/**
 * application helper 
 * 
 * Copyright (c) 2013 Yohei Yoshikawa (https://github.com/yoo16/)
 */

/**
 * undefineLabel
 *
 * @param string $key
 * @param string $value
 * @return string
 */
function undefineLabel($key, $value) {
    $tag = '';
    if (!defined($key)) $tag = undefineLabelTag();
    if (defined($key)) $tag.= $value;
    return $tag;
}

/**
 * undefineLabel
 *
 * @return string
 */
function undefineLabelTag() {
    $tag = '<label class="badge badge-danger">Undefined</label>';
    return $tag;
}

/**
 * css Class
 *
 * @param string $key
 * @param object $selected
 * @param string $class_name
 * @return string
 */
function cssClass($key, $selected, $class_name) {
    if ($key == $selected) return $class_name;
}