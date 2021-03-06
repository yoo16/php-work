<?php
/**
 * PwSession 
 *
 * @author  Yohei Yoshikawa
 *
 * Copyright (c) 2017 Yohei Yoshikawa (https://github.com/yoo16/)
 */

if (!defined('APP_NAME')) exit('Not found APP NAME');

class PwSession {

   /**
    * load
    *
    * @param  string $key
    * @param  int $sid
    * @param  object $default_value
    * @return object
     **/ 
    static function load($key, $sid = 0, $default_value = null) {
        if (!$sid) $sid = 0;
        if (isset($_REQUEST[$key])) PwSession::set($key, $_REQUEST[$key], $sid);
        $value = PwSession::get($key, $sid);
        if (is_null($value)) $value = $default_value;
        return $value;
    }

   /**
    * get
    *
    * @param string $key
    * @param int $sid
    * @return object
    */
    static function get($key, $sid = 0) {
        if (!$sid) $sid = 0;
        $sid = (int) $sid;
        $value = null;
        if (isset($_SESSION[APP_NAME][$sid][$key])) $value = $_SESSION[APP_NAME][$sid][$key];
        return $value;
    }

   /**
    * set session
    *
    * @param string $key
    * @param mixed $value
    * @param int $sid
    * @return void
    */
    static function set($key, $value, $sid = 0) {
        if (!$sid) $sid = 0;
        if (!$key) return;
        $sid = (int) $sid;
        $_SESSION[APP_NAME][$sid][$key] = $value;
    }

   /**
    * load with session key
    *
    * @param  string $session_key
    * @param  string $key
    * @param  int $sid
    * @return object
     **/ 
    static function loadWithKey($session_key, $key, $sid = 0) {
        if (!$sid) $sid = 0;
        if (isset($_REQUEST[$key])) PwSession::setWithKey($session_key, $key, $_REQUEST[$key], $sid);
        return PwSession::getWithKey($session_key, $key, $sid);
    }

   /**
    * get with key
    *
    * @param string $session_key
    * @param string $key
    * @param int $sid
    * @return object
    */
    static function getWithKey($session_key, $key, $sid = 0) {
        $value = null;
        if (isset($_SESSION[APP_NAME][$sid][$session_key])) {
            if (isset($_SESSION[APP_NAME][$sid][$session_key][$key])) {
                $value = $_SESSION[APP_NAME][$sid][$session_key][$key];
            }
        }
        return $value;
    }

   /**
    * set session
    *
    * @param string $session_key
    * @param string $key
    * @param object $value
    * @return void
    */
    static function setWithKey($session_key, $key, $value, $sid = 0) {
        if (!$sid) $sid = 0;
        $_SESSION[APP_NAME][$sid][$session_key][$key] = $value;
    }

   /**
    * flush
    *
    * @return void
    */
    static function flush() {
        unset($_SESSION[APP_NAME]);
    }

   /**
    * clear session
    *
    * @param string $key
    * @param string $session_key
    * @return void
    */
    static function clear($key, $sid = 0) {
        if (!$sid) $sid = 0;
        unset($_SESSION[APP_NAME][$sid][$key]);
    }

   /**
    * clear session
    *
    * @param string $session_key
    * @return void
    */
    static function clearWithKey($session_key, $key, $sid = 0) {
        if (!$sid) $sid = 0;
        unset($_SESSION[APP_NAME][$sid][$session_key][$key]);
    }

   /**
    * clear session
    *
    * @param string $session_key
    * @return void
    */
    static function flushWithKey($session_key, $sid = 0) {
        if (!$sid) $sid = 0;
        unset($_SESSION[APP_NAME][$sid][$session_key]);
    }

   /**
    * get errors
    *
    * @return void
    */
    static function getErrors() {
        return self::get('errors'); 
    }

   /**
    * set errors
    *
    * @param array $errors
    * @return void
    */
    static function setErrors($errors) {
        self::set('errors', $errors); 
    }

   /**
    * flush errors
    *
    * @return void
    */
    static function flushErrors() {
        self::clear('errors'); 
    }
}