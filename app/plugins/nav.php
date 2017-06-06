<?php
    /**
     * ver      : 0.2.1
     * date     : 2011-01-18
     * update   : Yohei Yoshikawa
     *
     *  2011.05.24 ver 0.2.1
     *   - _is_page_selected()
     *      add is_highlight_except_action parameter
     *
     **/

    require_once 'CsvLite.php';
    function _get_params() {
        return $GLOBALS['controller']->params;
    }

    function menus_from_csv($csv_name) {
        $file_path = BASE_DIR."db/menus/{$csv_name}.csv";
        $menu_csv = new CsvLite($file_path);
        $menu_csv->sort_key = 'sort_order';
        return $menu_csv->results();
    }

    function _is_page_selected($page, $params=null) {
        $params = _get_params();
        $controller = $params['controller'];
        $action = $params['action'];

        $page_controller = $page['controller'];
        $page_action = $page['action'];
        if (is_null($controller)) $controller = $GLOBALS['controller']->name;

        if ($controller == 'root' && $page_controller == $controller) {
            if (!$action) $action = 'index';
            return ($page_action == $action);
        } else {
            if ($page['is_highlight_except_action']) {
                return ($page_controller == $controller);
            } else {
                //echo($page_controller.' = '.$controller.'<br>');
                return ($page_controller == $controller && $page_action == $action);
            }
        }
    }

    function img_tag($values, $is_image_dir=true) {
        foreach ($values as $key => $value) {
            if ($value) {
                if ($key == 'src' && $is_image_dir) {
                    $value = image("/{$value}");
                }
                $attribute.= " {$key}=\"{$value}\"";
            }
        }
        $tag = "<img{$attribute} />";
        return $tag;
    }

    function menu_links_li($name, $_params=null) {
        $params = _get_params();
        $pages = menus_from_csv($name);
        if (is_array($pages)) {
            foreach ($pages as $key => $page) {
                $li_tag.= nav_li_link($page, $params);
            }
            if (!$_params['class']) $_params['class'] = 'nav navbar-nav';
            $attribute.= " class = \"{$_params['class']}\"";

            $tag.= "<ul{$attribute}>\n{$li_tag}\n</ul>\n";
        }
        echo($tag);
    }

    function menu_links($name) {
        $params = _get_params();
        $pages = menus_from_csv($name);
        if (is_array($pages)) {
            foreach ($pages as $key => $page) {
                echo(nav_link($page));
            }
        }
    }

    function nav_li_link($page, $params=null) {
        $params = _get_params();
        $selected = _is_page_selected($page, $params);
        $class = $page['class'];
        $id = $page['id'];
        if ($selected) $class = 'active';
        $tag = nav_link($page, $params);
        $tag = "<li id=\"{$id}\" class=\"{$class}\">{$tag}</li>\n";
        return $tag;
    }

    function nav_link($page, $params=null) {
        $params = _get_params();
        $values = nav($page, $params);
        if ((bool) $page['is_image']) {
            $caption = img_tag($values['img']);
        } else {
            if ($values['page']['label']) $caption = $values['page']['label'];
            if ($values['page']['alt']) $caption = $values['page']['alt'];
        }
        if ($page['id']) $id.= " id = \"{$page['id']}\"";
        $tag = "<a href=\"{$values['page']['url']}\"{$id}>{$caption}</a>";
        return $tag;
    }

    function nav($page, $params=null) {
        $params = _get_params();
        //url
        if (is_null($params['controller'])) $params['controller'] = $GLOBALS['controller']->name;
        $_params['controller'] = $page['controller'];
        $_params['action'] = $page['action'];

        if (!$_params['controller']) {
            $page['url'] = '#';
        } else {
            $page['url'] = url_for($_params, (bool) $page['ssl']);
        }

        //img link
        $selected = _is_page_selected($page, $params);
        if ((bool) $page['is_image']) {
            $src = $page['src'];

            $src = FileManager::getFileName($page['src']);
            $ext = FileManager::getFileExt($page['src']);
            if ($selected) {
                $img['src'] = "{$src}_on.{$ext}";
            } else {
                $img['src'] = "{$src}.{$ext}";
                $img['class'] = 'swap';
            }
            if ($img['label']) $img['label'] = $page['label'];
            if ($img['alt']) $img['alt'] = $page['alt'];
        //text link
        } else {
            if ($selected) {
                //$page['class'] = 'current_page_item';
                $page['class'] = 'active';
            }
        }

        if ($page['rel']) {
            $page['rel'] = "rel = \"{$page['rel']}\"";
        }
        $values['page'] = $page;
        $values['img'] = $img;

        return $values;
    }

    //mailto spam
    function mailto($address, $message = null, $subject = null, $body = null) {
        if (is_null($message)) $message = $address;
        $text  = "<a href=\"mailto:{$address}";
        if ($subject || $body) {
            $text .= "?subject=" . urlencode($subject) . "&body=" . urlencode($body);
        }
        $text .= "\">";
        $text .= $message;
        $text .= "</a>";

        $offset = 3 % 95;

        $cryptString = '';
        $length = strlen($text);

        for ($i=0; $i < $length; $i++) {
            $current_chr = substr($text, $i, 1);
            $num = ord($current_chr);
            if ($num < 128) {
                $inter = $num + $offset;
                if ($inter > 127) {
                    $inter = ($inter - 32) % 95 + 32;
                }
                $enc_char =  chr($inter);
                $cryptString .= ($enc_char == '\\' ? '\\\\' : $enc_char);
            } else {
                $cryptString .= $current_chr;
            }
        }

        srand((float) microtime() * 10000000);
        $letters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $rnd = $letters[array_rand($letters)] . md5(time());
        $script = '<script type="text/javascript">/*<![CDATA[*/'
            . 'var a,s,n;'
            . 'function ' . $rnd . '(s){'
                . 'r="";'
                . 'for(i=0;i<s.length;i++){'
                    . 'n=s.charCodeAt(i);'
                    . 'if(n<128){'
                        . 'n=n-' . $offset . ';'
                        . 'if(n<32){'
                            . 'n=127+(n-32);'
                        . '}'
                    . '}'
                    . 'r+=String.fromCharCode(n);'
                . '}'
                . 'return r;'
            . '}'
            . 'a="' . str_replace('"', '\\"', $cryptString) . '";'
            . 'document.write(' . $rnd . '(a));'
            . '//]]></script>';
        return $script;
    }

?>
