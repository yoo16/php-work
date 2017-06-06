<?php 
/**
 * @author  Yohei Yoshikawa
 *
 * Copyright (c) 2013 Yohei Yoshikawa (http://yoo-s.com/)
 */

function parse_dsn($dsn) {
    if (is_array($dsn)) {
        return($dsn);
    }

    $parsed = array(
        'phptype'  => FALSE,
        'dbsyntax' => FALSE,
        'username' => FALSE,
        'password' => FALSE,
        'protocol' => FALSE,
        'hostspec' => FALSE,
        'port'     => FALSE,
        'socket'   => FALSE,
        'database' => FALSE
    );

    // Find phptype and dbsyntax
    if (($pos = strpos($dsn, '://')) !== FALSE) {
        $str = substr($dsn, 0, $pos);
        $dsn = substr($dsn, $pos + 3);
    } else {
        $str = $dsn;
        $dsn = NULL;
    }

    // Get phptype and dbsyntax
    // $str => phptype(dbsyntax)
    if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
        $parsed['phptype']  = $arr[1];
        $parsed['dbsyntax'] = (empty($arr[2])) ? $arr[1] : $arr[2];
    } else {
        $parsed['phptype']  = $str;
        $parsed['dbsyntax'] = $str;
    }

    if (empty($dsn)) {
        return($parsed);
    }

    // Get (if found): username and password
    // $dsn => username:password@protocol+hostspec/database
    if (($at = strrpos($dsn,'@')) !== FALSE) {
        $str = substr($dsn, 0, $at);
        $dsn = substr($dsn, $at + 1);
        if (($pos = strpos($str, ':')) !== FALSE) {
            $parsed['username'] = rawurldecode(substr($str, 0, $pos));
            $parsed['password'] = rawurldecode(substr($str, $pos + 1));
        } else {
            $parsed['username'] = rawurldecode($str);
        }
    }

    // Find protocol and hostspec

    // $dsn => proto(proto_opts)/database
    if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
        $proto       = $match[1];
        $proto_opts  = (!empty($match[2])) ? $match[2] : FALSE;
        $dsn         = $match[3];

    // $dsn => protocol+hostspec/database (old format)
    } else {
        if (strpos($dsn, '+') !== FALSE) {
            list($proto, $dsn) = explode('+', $dsn, 2);
        }
        if (strpos($dsn, '/') !== FALSE) {
            list($proto_opts, $dsn) = explode('/', $dsn, 2);
        } else {
            $proto_opts = $dsn;
            $dsn = NULL;
        }
    }

    // process the different protocol options
    $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
    $proto_opts = rawurldecode($proto_opts);
    if ($parsed['protocol'] == 'tcp') {
        if (strpos($proto_opts, ':') !== FALSE) {
            list($parsed['hostspec'], $parsed['port']) =
                                                 explode(':', $proto_opts);
        } else {
            $parsed['hostspec'] = $proto_opts;
        }
    } elseif ($parsed['protocol'] == 'unix') {
        $parsed['socket'] = $proto_opts;
    }

    // Get dabase if any
    // $dsn => database
    if (!empty($dsn)) {
        // /database
        if (($pos = strpos($dsn, '?')) === FALSE) {
            $parsed['database'] = $dsn;
        // /database?param1=value1&param2=value2
        } else {
            $parsed['database'] = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 1);
            if (strpos($dsn, '&') !== FALSE) {
                $opts = explode('&', $dsn);
            } else { // database?param1=value1
                $opts = array($dsn);
            }
            foreach ($opts as $opt) {
                list($key, $value) = explode('=', $opt);
                if (!isset($parsed[$key])) { // don't allow params overwrite
                    $parsed[$key] = rawurldecode($value);
                }
            }
        }
    }

    return($parsed);
}
?>
