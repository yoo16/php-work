<?php
/**
 * DB 
 * 
 * Copyright (c) 2017 Yohei Yoshikawa (http://yoo-s.com/)
 */

class DB {

    function __construct($params=null) {
    }

   /**
    * validate
    *
    * @param
    * @return void
    */
    static function validate() {
    }

   /**
    * table
    *
    * @param
    * @return Class
    */
    static function table($name) {
        $instance = new $name();
        return $instance;
    }

   /**
    * optionValues
    *
    * @param Array $params
    * @return Array
    */
    function optionValues($params) {
        $options = null;
        $column = $params['column'];
        $conditions = $params['conditions'];
        $orders = $params['orders'];
        $id_column = $params['id_column'];
        $label_separator = ($params['label_separator']) ? $params['label_separator'] : ' ';

        $instance = new self($params);
        if (is_null($column)) $column = $instance->id_column;
        if (!$id_column) $id_column = $instance->id_column;

        if (is_string($column)) $_column = explode(' ', $column);
        if ($_column && count($_column) > 0) $column = $_column;

        $instance->_add_conditions_and_orders($conditions, $orders);

        $values = $instance->results();
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (is_array($column)) {
                    $labels = null;
                    foreach ($column as $_column) {
                        $labels[] = $value[$_column];
                    }
                    $label = implode($label_separator, $labels);
                } else {
                    $label = $value[$column];
                }
                $options[$value[$id_column]] = $label;
            }
            return $options;
        }
    }

   /**
    * optionValuesForKey
    *
    * @param String $label_column
    * @param String $id_column
    * @return Array
    */
    function optionValuesForKey($label_column, $id_column=null) {
        $instance = new self($params);
        $params['id_column'] = $id_column;
        $params['column'] = $label_column;
        $option_values = $instance->optionValues($params);
        return $option_values;
    }

   /**
    * formOptions
    *
    * @param Array $params
    * @return Array
    */
    function formOptions($params) {
        $name = $params['name'];
        $column = $params['column'];
        $selected = $params['selected'];
        $conditions = $params['conditions'];
        $orders = $params['orders'];
        $id_column = $params['id_column'];
        $class_name = $params['class'];
        $js = $params['js'];
        $label_separator = ($params['label_separator']) ? $params['label_separator'] : ' ';

        $instance = new self($params);
        if (is_null($column)) $column = $instance->id_column;
        if (!$id_column) $id_column = $instance->id_column;

        $instance->_add_conditions_and_orders($conditions, $orders);
        $values = $instance->results();

        if (is_string($column)) $_column = explode(' ', $column);
        if ($_column && count($_column) > 0) $column = $_column;

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $_options['value'] = $value[$id_column];
                if (is_array($column)) {
                    $labels = null;
                    foreach ($column as $_column) {
                        $labels[] = $value[$_column];
                    }
                    $label = implode($label_separator, $labels);
                } else {
                    $label = $value[$column];
                }
                $_options['label'] = $label;
                $option_values[] = $_options;
            }
            $options['id'] = $params['id'];
            $options['unselect'] = $params['unselect'];
            $options['name'] = $name;
            $options['values'] = $option_values;
            $options['value_key'] = 'value';
            $options['label_key'] = 'label';
            $options['selected'] = $selected;
            $options['class'] = $class_name;
            $options['js'] = $js;
            return $options;
        }
    }

   /**
    * formOptionList
    *
    * @param Array $params
    * @return Array
    */
    function formOptionList($params) {
        $results = null;
        $option_values = null;
        $conditions = $params['conditions'];
        $orders = $params['orders'];
        $id_column = $params['id_column'];
        $class_name = $params['class'];
        $label_separator = ($params['label_separator']) ? $params['label_separator'] : ' ';

        $instance = new self($params);
        if (!$id_column) $id_column = $instance->id_column;

        $instance->_add_conditions_and_orders($conditions, $orders);
        $values = $instance->results();

        if (is_array($values)) {
            foreach ($values as $value) {
                foreach ($instance->columns as $column => $type) {
                    $_options['value'] = $value[$id_column];
                    if (is_array($column)) {
                        $labels = null;
                        foreach ($column as $_column) {
                            $labels[] = $value[$_column];
                        }
                        $label = implode($label_separator, $labels);
                    } else {
                        $label = $value[$column];
                    }
                    $_options['label'] = $label;
                    $option_values[$column][] = $_options;
                }
            }

            $options['class'] = $class_name;
            foreach ($instance->columns as $column => $type) {
                $options['id'] = $params['id'];
                $options['name'] = "{$instance->entity_name}[{$column}]";
                $options['values'] = $option_values[$column];
                $options['value_key'] = 'value';
                $options['label_key'] = 'label';
                $results[$column] = $options;
            }
            return $results;
        }
    }


   /**
    * formSpotId
    *
    * @param Array $params
    * @return Array
    */
    function formSpotId($params=null) {
        $params['name'] = 'sensor[spot_id]';
        if (!$params['column']) $params['column'] = '';
        $params['id'] = 'spot_id';
        $params['unselect'] = true;
        $values = Spot::formOptions($params);
        return $values;
    }




    //TODO 廃止予定 optionValuesに移行
   /**
    * options
    *
    * @param String $column
    * @param Array $conditions
    * @param Array $orders
    * @param String $id_column
    * @return Array
    */
    function options($column=null, $conditions=null, $orders=null, $id_column=null) {
        $instance = new self($params);
        if (is_null($column)) $column = $instance->id_column;
        if (!$id_column) $id_column = $instance->id_column;

        $instance->_add_conditions_and_orders($conditions, $orders);

        $values = $instance->results();
        $options = null;
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $options[$value[$id_column]] = $value[$column];
            }
            return $options;
        }
    }

    //TODO 廃止予定 formOptionsに移行
   /**
    * form_options
    *
    * @param String $name
    * @param String $column
    * @param Object $selected
    * @param Array $conditions
    * @param Array $orders
    * @return Array
    */
    function form_options($name, $column, $selected, $conditions=null, $orders=null) {
        $instance = new self($params);
        if (is_null($column)) $column = $instance->id_column;

        $instance->_add_conditions_and_orders($conditions, $orders);

        $values = $instance->results();
        $options_values = null;
        if (is_array($values)) {
            foreach ($values as $value) {
                $_options['value'] = $value[$instance->id_column];
                $_options['label'] = $value[$column];
                $option_values[] = $_options;
            }
            $options['name'] = $name;
            $options['values'] = $option_values;
            $options['value_key'] = 'value';
            $options['label_key'] = 'label';
            $options['selected'] = $selected;
            return $options;
        }
    }

   /**
    * create_form_for_csv
    *
    * @param Array $values
    * @return Array
    */
    function create_form_for_csv($values) {
        require_once 'application.php';
        return $values;
    }

   /**
    * default_value
    *
    * @param
    * @return Object
    */
    function default_value() {
        return $this->value;
    }

   /**
    * sessionValue
    *
    * @param Array $conditions
    * @param Array $values
    * @return Array
    */
    function sessionValue($session_name, $request_key, $sid=0, $session_type=null) {
        if ($_REQUEST[$request_key]) {
            $values = self::_getValue($_REQUEST[$request_key]);
            AppSession::setSession($sid, $session_name, $values, $session_type);
        }
        $values = AppSession::getSession($sid, $session_name, $session_type);
        return $values;
    }

   /**
    * _getValue
    *
    * @param Array $conditions
    * @param Array $values
    * @return Array
    */
    function _getValue($conditions, $values=null) {
        $instance = self::_get($conditions, $values);
        return $instance->value;
    }

   /**
    * _getNewValue
    *
    * @param Array $values
    * @return Array
    */
    function _getNewValue($values=null) {
        $instance = self::_new($values);
        return $instance->value;
    }

   /**
    * _get
    *
    * @param Array $conditions
    * @param Array $values
    * @return _Sensor
    */
    function _get($conditions, $values=null) {
        $_conditions = null;
        if (is_numeric($conditions)) {
            $_conditions = $conditions;
        } elseif (is_array($conditions)) {
            $_conditions = $conditions;
        }
        $instance = new self($params);
        if ($_conditions) $instance->fetch($_conditions);
        if ($values) {
            $instance->take_values($values);
            $instance->validate();
        }
        return $instance;
    }

   /**
    * _list
    *
    * @param Array $conditions
    * @param Array $orders
    * @param Array $params
    * @return Array
    */
    function _list($conditions=null, $orders=null, $params=null) {
        $instance = new self($params);
        $instance->_add_conditions_and_orders($conditions, $orders);
        $values = $instance->results($params['offset'], $params['limit']);
        if ($params['is_key'] || $params['key']) $values = self::valuesForArrayKey($values, $params['key']);
        return $values;
    }

   /**
    * _join_list
    *
    * @param String $model
    * @param String $join_column
    * @param Array $conditions
    * @param Array $orders
    * @param Array $params
    * @return Array
    */
    function _join_list_for_id($model, $join_column, $conditions=null, $orders=null, $params=null) {
        $instance = new self($params);
        $instance->_add_conditions_and_orders($conditions, $orders);
        $instance->_add_extra_columns($model);
        $join_column = "{$instance->name}.{$join_column}";
        $instance->add_left_join($model->name, "{$instance->id_column} = {$join_column}");

        $results = $instance->results($params['offset'], $params['limit']);
        return $results;
    }

   /**
    * _inner_join_list
    *
    * @param String $model
    * @param String $join_column
    * @param Array $conditions
    * @param Array $orders
    * @param Array $params
    * @return Array
    */
    function _inner_join_list($model, $join_column, $conditions=null, $orders=null, $params=null) {
        $instance = new self($params);
        $instance->_add_conditions_and_orders($conditions, $orders);
        $instance->_add_extra_columns($model);
        $join_column = "{$instance->name}.{$join_column}";
        $instance->add_join($model->name, "{$instance->id_column} = {$join_column}");

        $results = $instance->results($params['offset'], $params['limit']);
        return $results;
    }

   /**
    * _left_join_list
    *
    * @param String $model
    * @param String $join_column
    * @param Array $conditions
    * @param Array $orders
    * @param Array $params
    * @return Array
    */
    function _left_join_list($model, $join_column, $conditions=null, $orders=null, $params=null) {
        $instance = new self($params);
        $instance->_add_conditions_and_orders($conditions, $orders);
        $instance->_add_extra_columns($model);
        $join_column = "{$instance->name}.{$join_column}";
        $instance->add_left_join($model->name, "{$instance->id_column} = {$join_column}");

        $results = $instance->results($params['offset'], $params['limit']);
        return $results;
    }

   /**
    * _add_conditions_and_orders
    *
    * @param Array $conditions
    * @param Array $orders
    * @return Array
    */
    function _add_conditions_and_orders($conditions, $orders) {
        //conditions
        if (is_array($conditions)) {
            foreach ($conditions as $condition) {
                $this->add_where($condition);
            }
        } elseif($conditions) {
            $this->add_where($conditions);
        }

        //orders
        if (is_array($orders)) {
            foreach ($orders as $order) {
                $this->add_order($order[0], $order[1]);
            }
        }
        if ($this->columns['sort_order']) {
            $this->add_order('sort_order', false);
        }
        if (!$this->is_none_id_column && $this->id_column) {
            $this->add_order($this->id_column, false);
        }
    }

   /**
    * _add_extra_columns
    *
    * @param Array $model
    * @return Array
    */
    function _add_extra_columns($model) {
        if ($model) {
            foreach ($model->columns as $column_name => $column_type) {
                $extra_name = "{$model->name}_{$column_name}";
                $extra_value = "{$column_type}:{$model->name}.{$column_name}";
                $this->extra_columns[$extra_name] = $extra_value;
            }
        }
    }


   /**
    * _new
    *
    * @param Array $values
    * @return _Sensor
    */
    function _new($values=null) {
        $instance = new self($params);
        $instance->default_value();
        if ($values) {
            $instance->take_values($values);
        }
        $instance->validate();
        return $instance;
    }

   /**
    * _add
    *
    * @param Array $posts
    * @param Boolean $is_id
    * @return _Sensor
    */
    function _add($posts, $is_id=false) {
        if ($posts) {
            $instance = new self($params);
            $instance->default_value();
            if ($is_id) {
                $instance->columns[$instance->id_column] = 'i';
            }
            $instance->take_values($posts);
            $instance->validate();
            if ($is_id) {
                $instance->value[$instance->id_column] = $posts[$instance->id_column];
            }

            if (!$instance->errors) {
                if (!$instance->insert()) {
                    $instance->add_error('sensor', 'save error');
                }
            }
            return $instance;
        }
    }

   /**
    * _addValue
    *
    * @param Array $posts
    * @param Boolean $is_id
    * @return _Sensor
    */
    function _addValue($posts, $is_id=false) {
        $instance = self::_add($posts, $is_id);
        return $instance->value;
    }

   /**
    * _update
    *
    * @param Integer $id
    * @param Array $posts
    * @return _Sensor
    */
    function _update($id, $posts) {
        $id = sql_escape_string($id);
        $instance = new self($params);
        if ($id > 0 && $instance->fetch($id)) {
            $instance->take_values($posts);
            $instance->validate();
            if (!$instance->errors) {
                $instance->save();
            }
        }
        return $instance;
    }


   /**
    * _updateValue
    *
    * @param Integer $id
    * @param Array $posts
    * @return Array
    */
    function _updateValue($id, $posts) {
        $instance = self::_update($id, $posts);
        return $instance->value;
    }

   /**
    * _updateWithConditions
    *
    * @param Array  $conditions
    * @param Array    $posts
    * @return _Sensor
    */
    function _updateWithConditions($conditions, $posts) {
        $instance = new self($params);
        if ($posts && is_array($conditions) && $instance->fetch($conditions)) {
            $instance->take_values($posts);
            $instance->validate();
            if (!$instance->errors) {
                $instance->update($conditions);
            }
        }
        return $instance;
    }

   /**
    * _delete
    *
    * @param Integer $id
    * @return _Sensor
    */
    function _delete($id) {
        $instance = null;
        $id = sql_escape_string($id);
        if ($id > 0) {
            $instance = new self($params);
            if ($instance->fetch($id)) {
                return $instance->delete();
            }
        }
        return $instance;
    }

   /**
    * _deleteWithConditions
    *
    * @param Array $conditions
    * @return _Sensor
    */
    function _deleteWithConditions($conditions) {
        $instance = null;
        if (is_array($conditions)) {
            $instance = new self($params);
            foreach($conditions as $key => $condition) {
                $instance->add_where($condition);
            }
            return $instance->delete();
        }
        return $instance;
    }

   /**
    * update_sort_order
    *
    * @param Array $values
    * @return void
    */
    function update_sort_order($values) {
        if (is_array($values)) {
            foreach ($values as $id => $value) {
                $id = sql_escape_string($id);
                if (is_numeric($value)) {
                    $posts['sort_order'] = $value;
                    self::_update($id, $posts);
                }
            }
        }
    }

    /**
     * count
     *
     * @param Array $conditions
     * @return Float
     */
    function count($conditions=null) {
        $instance = new self($params);
        $column = ($instance->id_column) ? $instance->id_column : '*';
        $sql = "SELECT COUNT({$column}) FROM {$instance->name}";
        if (is_array($conditions)) $conditions = implode($conditions, ' AND ');
        if ($conditions) $sql.= " WHERE {$conditions}";
        $sql.= ";";
        $values = $instance->fetch_row($sql);
        return $values['count'];
    }

    /**
     * maxMinValue
     *
     * @param String $column
     * @param Array $conditions
     * @return Float
     */
    function maxMinValue($column, $conditions=null) {
        $instance = new self($params);
        $sql = "SELECT max({$column}), min({$column}) FROM {$instance->name}";
        if (is_array($conditions)) $conditions = implode($conditions, ' AND ');
        if ($conditions) $sql.= " WHERE {$conditions}";
        $sql.= ";";
        $values = $instance->fetch_row($sql);
        return $values;
    }

    /**
     * maxValue
     *
     * @param String $column
     * @param Array $conditions
     * @return Float
     */
    function maxValue($column, $conditions=null) {
        $instance = new self($params);
        $sql = "SELECT max({$column}) FROM {$instance->name}";
        if (is_array($conditions)) $conditions = implode($conditions, ' AND ');
        if ($conditions) $sql.= " WHERE {$conditions}";
        $values = $instance->fetch_row($sql);
        $max = $values['max'];
        return $max;
    }

    /**
     * minValue
     *
     * @param String $column
     * @param Array  $conditions
     * @return Float
     */
    function minValue($column, $conditions=null) {
        $instance = new self($params);
        $sql = "SELECT min({$column}) FROM {$instance->name}";
        if (is_array($conditions)) $conditions = implode($conditions, ' AND ');
        if ($conditions) $sql.= " WHERE {$conditions}";
        $values = $instance->fetch_row($sql);
        $min = $values['min'];
        return $min;
    }

    /**
     * getFirst
     *
     * @param Array  $conditions
     * @param Array  $orders
     * @return Array
     */
    function _getFirstValue($conditions=null, $orders=null) {
        $values = self::_list($conditions, $orders);
        if ($values) return $values[0];
    }

    /**
     * 連想配列から特定の配列を生成
     *
     * @param Array $values
     * @param String $key
     * @return Array
     **/
    function valueList($values, $key) {
        $results = null;
        if (is_array($values)) {
            foreach ($values as $value) {
                $results[] = $value[$key];
            }
        }
        return $results;
    }

    /**
     * SQL in 条件の生成
     *
     * @param Array $values
     * @param String $key
     * @return String
     **/
    function conditionForKey($values, $key='id') {
        $condition = null;
        if (is_array($values)) {
            $id = implode(',', $values);
            $id = sql_escape_string($id);
            $condition = "{$key} in ($id)";
        }
        return $condition;
    }

    /**
     * 配列を指定キーの値で変換
     *
     * @param Array $values
     * @param String $key
     * @return Array
     **/
    function valuesForArrayKey($values, $key=null) {
        $_values = null;
        if (!$key) {
            $instance = new self($params);
            $key = $instance->id_column;
        }
        if (is_array($values)) {
            foreach ($values as $value) {
                $key_value = $value[$key];
                $_values[$key_value] = $value;
            }
        }
        return $_values;
    }

    /**
     * 配列をキーで連結
     *
     * @param $join_model
     * @param $join_column
     * @param Array $values1
     * @param Array $values2
     * @param String $key1
     * @param String $key2
     * @return Array
     **/
    function joinValues($join_model, $join_column, $values1, $values2, $key1=null, $key2=null) {
        $values = null;
        $values1 = self::valuesForArrayKey($values1, $key1);
        $values2 = self::valuesForArrayKey($values2, $key2);

        if (is_array($values1)) {
            foreach ($values1 as $key_value => $value1) {
                $join_id = $value1[$join_column];
                $value2 = $values2[$join_id];
                $value1[$join_model] = $value2;
                $values[] = $value1;
            }
        }
        return $values;
    }

   /**
    * listWithSpot
    *
    * @param Array $values
    * @return Array
    */
    function listWithSpot($values ,$conditions=null, $orders=null, $params=null) {
        $conditions[] = "spot_id = '{$values['rid']}'";
        $results = self::_list($conditions, $orders, $params);
        return $results;
    }


    /**
     * IN問い合わせ
     *
     * @param array $params
     * @return Array
     **/
    function listForInCondition($params) {
        $conditions = $params['conditions'];
        $conditions[] = self::queryForIn($params);

        if ($params['orders']) $orders = $params['orders'];

        $values = self::_list($conditions, $orders, $params);
        return $values;
    }

    /**
     * INクエリー
     *
     * @param array $params
     * @return Array
     **/
    function queryForIn($params) {
        if ($params['sub_conditions']) $sub_condition = implode('AND ', $params['sub_conditions']);
        $main_column = $params['main_column'];
        $sub_column = $params['sub_column'];
        $sub_table = $params['sub_table'];
        $value = "{$main_column} IN (SELECT {$sub_column} FROM {$sub_table} WHERE {$sub_condition})";
        return $value;
    }

}

?>