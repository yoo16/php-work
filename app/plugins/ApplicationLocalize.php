<?php
/**
 * ApplicationLocalize 
 *
 * @author  Yohei Yoshikawa
 * 
 * Copyright (c) 2013 Yohei Yoshikawa (http://yoo-s.com/)
 */
class ApplicationLocalize {

    /**
     * [autoload_model description]
     * 
     * @return [type] [description]
     */
    function __construct() {

    }

    /**
     * load
     *
     * @param 
     * @return Array
     **/
    function load() {
        if (defined('IS_LOCALE_LOCALIZE') && IS_LOCALE_LOCALIZE) {
            $locale = self::loadLocale();
            if (!$locale) {
                $locale = self::defaultLocale();
            }
            AppSession::setSession(0, 'locale', $locale);
            define('LOCALE', $locale);

            self::loadLocalizeFile($locale);
         } else {
            require_once 'helpers/localize/jp.php';
         }
    }

    function loadLocalizeFile($locale) {
        $localize_path = BASE_DIR."app/localize/{$locale}/localize.php";
        if (file_exists($localize_path)) {
           require_once $localize_path;
        }
    }

    /**
     * [load_model description]
     * 
     * @param
     * @return string 
     */
    function defaultLocale($locale) {
        if (defined('DEFAULT_LOCALE')) {
             $locale = DEFAULT_LOCALE;
         } else {
             $locale = 'ja';
         }
        return $locale;
    }

    /**
     * [load_model description]
     * 
     * @param
     * @return string 
     */
    function loadLocale() {
        if ($_GET['lang']) {
            if ($_GET['lang'] == 'default') {
                AppSession::clearSession(0, 'locale');
                unset($_GET['lang']);
            }
            AppSession::setSession(0, 'locale', $_GET['lang']);
            self::claerLocaleValues();
        }
        $locale = AppSession::getSession(0, 'locale');
        return $locale;
    }

    function claerLocaleValues() {
        AppSession::clearSession(0, 'option');
    }

}

?>
