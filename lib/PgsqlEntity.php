<?php
/**
 * PgsqlEntity 
 *
 * @copyright  Copyright (c) 2017 Yohei Yoshikawa (http://yoo-s.com/)
 */
if (!defined('PG_INFO')) exit('not found PG_INFO');

require_once 'Entity.php';

class PgsqlEntity extends Entity {
    var $extra_columns = false;
    var $group_columns = false;
    var $joins = array();
    var $pg_info = PG_INFO;
    var $values = null;
    var $value = null;

    /**
     * initDb
     * 
     * @return array
     */
    static function initDb() {
        $path = BASE_DIR."script/init_db";
        $cmd = "php {$path} 2>&1";
        exec($cmd, $output, $return);

        $results['cmd'] = $cmd;
        $results['output'] = $output;
        $results['return'] = $return;
        return $results;
    }

    /**
     * createDatabase
     * 
     * @param  array $values
     * @return array
     */
    static function createDatabase($values) {
        if (!$values) return;
        
        $database_name = $values['dbname'];
        if (!$database_name) return;

        $database_user = $values['user']? $values['user'] : 'postgres';
        $host = $values['host']? $values['host'] : 'localhost';
        $port = $values['port']? $values['port'] : '5432';

        $cmd = "createdb -U {$database_user} -E UTF8 --host {$host} --port {$port} {$database_name} 2>&1";

        exec($cmd, $output, $return);

        $results['cmd'] = $cmd;
        $results['output'] = $output;
        $results['return'] = $return;
        return $results;
    }

    /**
     * pgInfo
     * 
     * @return array
     */
    static function pgInfo() {
        if (!defined('PG_INFO')) return;
        $values = explode(' ', PG_INFO);
        foreach ($values as $value) {
            if (is_numeric(strpos($value, 'dbname='))) {
                $results['dbname'] = trim(str_replace('dbname=', '', $value));
            }
            if (is_numeric(strpos($value, 'user='))) {
                $results['user'] = trim(str_replace('user=', '', $value));
            }
            if (is_numeric(strpos($value, 'port='))) {
                $results['port'] = trim(str_replace('port=', '', $value));
            }
            if (is_numeric(strpos($value, 'host='))) {
                $results['host'] = trim(str_replace('host=', '', $value));
            }
        }
        $results['pg_info'] = PG_INFO;
        return $results;
    }

    /**
     * connection
     * 
     * @return resource
     */
    function connection() {
        return pg_connect($this->pg_info);
    }

    /**
     * connection
     * 
     * @return resource
     */
    function query($sql) {
        $this->sql = $sql;
        $pg = $this->connection();
        if (defined('DEBUG') && DEBUG) error_log("<SQL> {$sql}");
        return pg_query($pg, $sql);
    }
    
    /**
     * fetch_rows
     * 
     * @return array
     */
    function fetch_rows($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $rows = pg_fetch_all($rs);
            return $rows;
        } else {
            return;
        }
    }

    /**
     * fetch_row
     * 
     * @return array
     */
    function fetch_row($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $row = pg_fetch_array($rs, null, PGSQL_ASSOC);
            return ($row) ? $row : null;
        } else {
            return;
        }
    }

    /**
     * fetch_result
     * 
     * @return string
     */
    function fetch_result($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $result = pg_fetch_result($rs, 0, 0);
            return (isset($result)) ? $result : null;
        } else {
            return null;
        }
    }

    /**
     * get
     * 
     * @param  int $id
     * @param  array $params
     * @return array
     */
    public function get($id, $params=null) {
        $this->values = null;
        if (!$id) return;
        $this->where("{$this->id_column} = {$id}")
             ->selectOne($params);
        return $this;
    }

    /**
     * fetch
     * 
     * @param  int $id
     * @param  array $params
     * @return array
     */
    public function fetch($id, $params=null) {
        $this->values = null;
        if (!$id) return;
        $this->where("{$this->id_column} = {$id}")
             ->selectOne($params);
        return $this->value;
    }

    /**
     * select
     * 
     * @param  array $params
     * @return array
     */
    public function select($params = null) {
        $sql = $this->selectSql($params);
        $values = $this->fetch_rows($sql);
        $this->values = $this->castRows($values, $params);
        unset($this->id);
        return $this->values;
    }

    /**
     * selectOne
     * 
     * @param  array $params
     * @return array
     */
    public function selectOne($params = null) {
        $this->values = null;
        $sql = $this->selectSql($params);
        $value = $this->fetch_row($sql);

        $this->value = $this->castRow($value);
        if (is_array($this->value) && isset($this->value[$this->id_column])) {
            $this->id = (int) $this->value[$this->id_column];
        }
        $this->_value = $this->value;
        return $this->value;
    }

    /**
     * insert
     * 
     * @param  array $posts
     * @return Class
     */
    public function insert($posts=null) {
        $this->id = null;
        $this->values = null;
        if ($posts) $this->takeValues($posts);

        $this->validate();
        if ($this->errors) return $this;

        $sql = $this->insertSql();
        if (!$sql) {
            $this->addError('sql', 'error');
            return $this;
        }

        if ($this->is_none_id_column) {
            $result = $this->query($sql);
            return $this;
        } else {
            $result = $this->fetch_result($sql);
            if ($result) {
                $this->id = (int) $result;
                $this->value[$this->id_column] = $this->id;
            } else {
                $this->addError('sql', 'error');
            }
        }
        return $this;
    }

    /**
     * insert
     * 
     * @param  array $posts
     * @param  int $id
     * @return Class
     */
    public function update($posts = null, $id = null) {
        $this->values = null;
        if ($id) $this->get($this->params['id']);
        if ($posts) $this->takeValues($posts);

        $this->validate();
        if ($this->errors) return $this;

        $sql = $this->updateSql();
        if (!$sql) {
            $this->addError('sql', 'error');
            return $this;
        }

        $result = $this->query($sql);
        if ($result !== false) {
            $this->_value = $this->value;
        } else {
            $this->addError('sql', 'error');
        }
        return $this;
    }

    public function delete($id = null) {
        $this->values = null;
        if (is_numeric($id)) $this->id = (int) $id;
        if (is_numeric($this->id)) {
            $this->conditions = null;
            $this->conditions[] = "{$this->id_column} = {$this->id}";
        }

        $sql = $this->deleteSql();
        $result = $this->query($sql);

        if ($result !== false) {
            unset($this->id);
            return true;
        }
        return false;
    }

    public function where($condition) {
        $this->conditions[] = $condition; 
        return $this;
    }

    public function order($column, $desc=false) {
        $value['column'] = $column;
        $value['desc'] = $desc;
        $this->orders[] = $value; 
        return $this;
    }

    public function initWhere($condition) {
        $this->condition = null;
        return $this->where($condition);
    }

    public function initOrder($order) {
        $this->order = null;
        return $this->orders($order);
    }

    public function limit($limit) {
        $this->limit = $limit; 
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset; 
        return $this;
    }

    function set_join($table, $conditions, $type = 'INNER') {
        $this->joins = array();
        $this->add_join($table, $conditions, $type);
    }

    function add_join($table, $conditions, $type = 'INNER') {
        if (is_array($conditions)) {
            foreach ($conditions as $i => $condition) {
                $conditions[$i] = "({$table}.{$condition})";
            }
            $conditions = implode(' AND ', $conditions);
        } else {
            $conditions = "{$table}.{$conditions}";
        }
        $this->joins[] = "{$type} JOIN {$table} ON {$conditions}";
    }

    function set_left_join($t, $c)  { $this->set_join($t, $c, 'LEFT'); }
    function set_right_join($t, $c) { $this->set_join($t, $c, 'RIGHT'); }
    function add_left_join($t, $c)  { $this->add_join($t, $c, 'LEFT'); }
    function add_right_join($t, $c) { $this->add_join($t, $c, 'RIGHT'); }

    /**
     * sqlValue
     * 
     * @param  Object $value
     * @return string
     */
    private function sqlValue($value) {
        if (is_null($value)) {
            return "NULL";
        } elseif (is_numeric($value)) {
            return (string) $value;
        } elseif (is_bool($value)) {
            return ($value) ? 'TRUE' : 'FALSE';
        } elseif (is_array($value)) {
            return "'" . pg_escape_string(json_encode($value)) . "'";
        } else {
            return "'" . pg_escape_string($value) . "'";
        }
    }

    /**
     * whereSql
     * 
     * @param  array $params
     * @return string
     */
    private function whereSql($params = null) {
        $sql = '';
        if ($params['conditions']) $this->conditions = $params['conditions'];
        if ($condition = $this->sqlConditions($this->conditions)) $sql = " WHERE {$condition}";
        return $sql;
    }

    /**
     * orderBySql
     * 
     * @param  array $params
     * @return string
     */
    private function orderBySql($params = null) {
        $sql = '';
        if ($params['orders']) $this->orders = $params['orders'];
        if (!isset($this->orders)) return;
        if ($order = $this->sqlOrders($this->orders)) $sql = " ORDER BY {$order}";
        return $sql;
    }

    /**
     * limitSql
     * 
     * @return string
     */
    private function limitSql() {
        $sql = '';
        if (!isset($this->limit)) return;
        if (!is_int($this->limit)) return;
        $sql = " LIMIT {$this->limit}";
        return $sql;
    }

    /**
     * offsetSql
     * 
     * @return string
     */
    private function offsetSql() {
        $sql = '';
        if (!isset($this->offset)) return;
        if (!is_int($this->offset)) return;
        $sql = " OFFSET {$this->offset}";
        return $sql;
    }

    /**
     * selectSql
     * 
     * @param  array $params
     * @return string
     */
    private function selectSql($params = null) {
        $sql = "SELECT {$this->name}.* FROM {$this->name}";
        $sql.= $this->whereSql($params);
        $sql.= $this->orderBySql($params);
        $sql.= $this->offsetSql($params);
        $sql.= $this->limitSql($params);
        $sql.= ";";
        return $sql;
    }

    /**
     * selectCountSql
     * 
     * @param  array $params
     * @return string
     */
    private function selectCountSql($params = null) {
        $sql = "SELECT count({$this->name}.*) FROM {$this->name}";
        $sql.= $this->whereSql($params);
        // GROUP BY {$group_str}) AS t";
        $sql.= ";";
        return $sql;
    }

    /**
     * insertSql
     * 
     * @return string
     */
    private function insertSql() {
        if (!$this->value) return;
        unset($this->value[$this->id_column]);
        unset($this->id);
        foreach ($this->columns as $key => $type) {
            $value = $this->sqlValue($this->value[$key]);
            if ($key == 'created_at') $value = 'current_timestamp';

            $columns[] = $key;
            $values[] = $value;
        }
        $column = implode(',', $columns);
        $value = implode(',', $values);

        $sql = "INSERT INTO {$this->name} ({$column}) VALUES ({$value});";
        $sql.= "SELECT currval('{$this->name}_id_seq');";
        return $sql;
    }

    /**
     * updateSql
     * 
     * @return string
     */
    private function updateSql() {
        $sql = '';
        $changes = $this->changes();
        if (!$changes) return;

        foreach ($changes as $key => $org_value) {
            $value = $this->sqlValue($this->value[$key]);
            $set_values[] = "{$key} = {$value}";
        }
        if (isset($this->columns['updated_at'])) $set_values[] = "updated_at = current_timestamp";
        if ($set_values) $set_value = implode(',', $set_values);

        if ($set_value) {
            if (!$this->conditions) $this->conditions[] = "{$this->id_column} = {$this->id}";
            $condition = $this->sqlConditions($this->conditions);
            $sql = "UPDATE {$this->name} SET {$set_value} WHERE {$condition};";
        }

        return $sql;
    }

    /**
     * deleteSql
     * 
     * @return string
     */
    private function deleteSql() {
        $sql = '';
        if (!$this->id) return;
        $where = $this->whereSql($params);
        if ($where) $sql = "DELETE FROM {$this->name} {$where};";
        return $sql;
    }


    //TODO
    function results_sql() {
        if (is_bool($this->group_columns) && $this->group_columns) {
            $this->group_columns = array_keys($this->columns);
            array_unshift($this->group_columns, "{$this->name}.{$this->id_column}");
        }
        if (empty($this->group_columns)) {
            $select_str = $this->name . '.*';
        } else {
            foreach ($this->group_columns as $i => $group_column) {
                if (!strpos($group_column, '.') && array_key_exists($group_column, $this->columns)) {
                    $this->group_columns[$i] = $this->name . "." . $group_column;
                    if (!empty($select_str)) $select_str .= ", ";
                    $select_str .=  $this->group_columns[$i];
                } elseif (!array_key_exists($group_column, $this->extra_columns)) {
                    if (!empty($select_str)) $select_str .= ", ";
                    $select_str .=  $group_column;
                }
            }
            $group_str = implode(', ', $this->group_columns);
        }

        if (is_array($this->extra_columns)) {
            foreach ($this->extra_columns as $extra_column => $def) {
                $extra_clause = substr($def, 2);
                if ($extra_clause) {
                    if (!empty($select_str)) $select_str .= ", ";
                    $select_str .= "{$extra_clause} AS {$extra_column}";
                }
            }
        }

        $from_str = ($this->from_sql) ? "({$this->from_sql}) AS {$this->name}" : $this->name;
        if (is_array($this->joins)) {
            foreach ($this->joins as $join) {
                $from_str .= " " . $join;
            }
        }

        $sql = "SELECT {$select_str} FROM {$from_str}";

        if (!empty($this->conditions)) {
            foreach ($this->conditions as $condition) {
                if (isset($conditions)) $conditions .= ' AND ';
                $conditions .= "({$condition})";
            }
        }
        if (isset($conditions)) $sql .= " WHERE {$conditions}";

        if (isset($group_str)) {
            $sql .= " GROUP BY {$group_str}";
        }

        if (!empty($this->orders)) {
            foreach ($this->orders as $order) {
                if (isset($orders)) $orders .= ', ';
                if (!strpos($order['column'], '.') && array_key_exists($order['column'], $this->columns)) {
                    $orders .= $this->name . '.' . $order['column'];
                } else {
                    $orders .= $order['column'];
                }
                if ($order['desc']) {
                    $orders .= ' DESC';
                }
            }
        }
        if (isset($orders)) $sql .= " ORDER BY {$orders}";
        return $sql;
    }

    public function count() {
        //TODO
        $from_str = ($this->from_sql) ? "({$this->from_sql}) AS {$this->name}" : $this->name;
        if (is_array($this->joins)) {
            foreach ($this->joins as $join) {
                $from_str .= " " . $join;
            }
        }

        if (is_bool($this->group_columns) && $this->group_columns) {
            $this->group_columns = array_keys($this->columns);
            array_unshift($this->group_columns, $this->id_column);
        }
        if (empty($this->group_columns)) {
            $select_str = "COUNT({$this->name}.{$this->id_column})";
        } else {
            $select_str = $this->group_columns[0];
            foreach ($this->group_columns as $i => $group_column) {
                if (!strpos($group_column, '.')) {
                    $this->group_columns[$i] = $this->name . "." . $group_column;
                }
            }
            $group_str = implode(', ', $this->group_columns);
        }

        $sql = $this->selectCountSql();
        $count = $this->fetch_result($sql); 
        if (is_null($count)) $count = 0;
        return $count;
    }

    function sqlConditions($conditions) {
        if (is_null($conditions)) return;
        if (is_int($conditions)) {
            $condition = "{$this->id_column} = {$conditions}";
        } elseif (is_string($conditions)) {
            $condition = $conditions;
        } elseif (is_array($conditions)) {
            $condition = implode(' AND ', $conditions);
        }
        return $condition;
    }

    function sqlOrders($orders) {
        if ($this->columns['sort_order']) $orders[] = array('column' => 'sort_order', 'desc' => false);
        if (!$orders) return;
        foreach ($orders as $order) {
            if (array_key_exists($order['column'], $this->columns)) {
                $asc_desc = ($order['desc']) ? 'DESC' : 'ASC';
                $_orders[] = "{$this->name}.{$order['column']} {$asc_desc}";
            }
        }
        $order = implode(', ', $_orders);
        return $order;
    }

}