<?php
/**
 * PgsqlEntity 
 *
 * @author  Yohei Yoshikawa
 *
 * Copyright (c) 2013 Yohei Yoshikawa (http://yoo-s.com/)
 */
 
if (!defined('PG_INFO')) exit('not found PG_INFO');

require_once 'Entity.php';

class PgsqlEntity extends Entity {
    var $extra_columns = false;
    var $group_columns = false;
    var $joins = array();
    var $pg_info = PG_INFO;

    function connection() {
        return pg_connect($this->pg_info);
    }

    function query($sql) {
        $this->sql = $sql;
        $pg = $this->connection();
        if (defined('DEBUG') && DEBUG) error_log("<SQL> {$sql}");
        return pg_query($pg, $sql);
    }
    
    function fetch_rows($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $rows = pg_fetch_all($rs);
            return $rows;
        } else {
            return;
        }
    }

    function fetch_row($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $row = pg_fetch_array($rs, null, PGSQL_ASSOC);
            return ($row) ? $row : null;
        } else {
            return;
        }
    }

    function fetch_result($sql) {
        $rs = $this->query($sql);
        if ($rs) {
            $result = pg_fetch_result($rs, 0, 0);
            return ($result) ? $result : null;
        } else {
            return false;
        }
    }

    public function fetch($id, $params=null) {
        if (!$id) return;
        $this->conditions[] = "{$this->id_column} = {$id}";
        $this->selectOne($params);
        return $value;
    }

    public function select($params = null) {
        $sql = $this->selectSql($params);
        $values = $this->fetch_rows($sql);
        $values = $this->castRows($values, $params);
        unset($this->id);
        return $values;
    }

    public function selectOne($params = null) {
        $sql = $this->selectSql($params);
        $value = $this->fetch_row($sql);

        $this->value = $this->castRow($value);
        if (is_array($this->value) && isset($this->value[$this->id_column])) {
            $this->id = (int) $this->value[$this->id_column];
        }
        $this->_value = $this->value;
        return $this->value;
    }

    public function insert() {
        $sql = $this->insertSql();

        if ($this->is_none_id_column) {
            $result = $this->query($sql);
            return true;
        } else {
            $result = $this->fetch_result($sql);
            if ($result) {
                $this->id = (int) $result;
                $this->value[$this->id_column] = $this->id;
                return true;
            } else {
                return false;
            }
        }
    }

    public function update() {
        $sql = $this->updateSql();
        if (!$sql) return false;

        $result = $this->query($sql);
        if ($result !== false) {
            $this->_value = $this->value;
            return true;
        } else {
            return false;
        }
    }

    public function delete($id = null) {
        if (is_int($id)) $this->id = $id;
        if (is_int($this->id)) {
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

    private function whereSql($params = null) {
        if ($params['conditions']) $this->conditions = $params['conditions'];
        if ($condition = $this->sqlConditions($this->conditions)) $sql = " WHERE {$condition}";
        return $sql;
    }

    private function orderBySql($params = null) {
        if ($params['orders']) $this->orders = $params['orders'];
        if ($order = $this->sqlOrders($this->orders)) $sql = " ORDER BY {$order}";
        return $sql;
    }

    private function limitSql() {
        if (!is_int($this->limit)) return;
        $sql = " LIMIT {$this->limit}";
        return $sql;
    }

    private function offsetSql() {
        if (!is_int($this->offset)) return;
        $sql = " OFFSET {$this->offset}";
        return $sql;
    }

    private function selectSql($params = null) {
        $sql = "SELECT {$this->name}.* FROM {$this->name}";
        $sql.= $this->whereSql($params);
        $sql.= $this->orderBySql($params);
        $sql.= $this->offsetSql($params);
        $sql.= $this->limitSql($params);
        $sql.= ";";
        return $sql;
    }

    private function selectCountSql($params = null) {
        $sql = "SELECT count({$this->name}.*) FROM {$this->name}";
        $sql.= $this->whereSql($params);
        // GROUP BY {$group_str}) AS t";
        $sql.= ";";
        return $sql;
    }

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

    private function updateSql() {
        $changes = $this->changes();
        if (!$changes) return;

        foreach ($changes as $key => $org_value) {
            $value = $this->sqlValue($this->value[$key]);
            $set_values[] = "{$key} = {$value}";
        }
        if (isset($this->columns['updated_at'])) $set_values = "updated_at = current_timestamp";

        $set_value = implode(',', $set_values);

        if (!$this->conditions) $this->conditions[] = "{$this->id_column} = {$this->id}";
        $condition = $this->sqlConditions($this->conditions);
        $sql = "UPDATE {$this->name} SET {$set_value} WHERE {$condition};";

        return $sql;
    }

    private function deleteSql() {
        if (!$this->value) return;
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
        if ($orders) {
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

}