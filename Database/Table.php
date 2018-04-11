<?php

/*
 * The MIT License
 *
 * Copyright 2017 Alexander.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace K3ksPHP\Database {

    require_once __DIR__ . "/Field.php";

    class Table {

        private static $TAG  = "Table";
        private $_tableName;
        private $_creatables = [];

        /**
         *
         * @param String table_name Name of the table
         * @param Array of creatables
         */
        public function __construct($table_name, $creatables) {
            if (empty($table_name)) {
                throw new Exception("Error in " . static::$TAG . ": you have to use an unique table name");
            }
            if (empty($creatables)) {
                throw new Exception("Error in " . static::$TAG . ": fields are empty");
            }
            $this->_tableName = $table_name;
            foreach ($creatables as $creatable) {
                $this->_creatables[$creatable->GetName()] = $creatable;
            }
        }

        private function _JoinFields($seperator) {
            $joined = '';
            foreach ($this->_creatables as $field) {
                if ($field instanceof Field) {
                    $joined .= $field->GetName() . $seperator;
                }
            }
            return substr($joined, 0, strlen($joined) - 1);
        }

        private function _JoinCreate() {
            $joined = '';
            foreach ($this->_creatables as $iCreatable) {
                if ($iCreatable instanceof ICreatable) {
                    $joined .= $iCreatable->GetCreate() . ',';
                }
            }
            return substr($joined, 0, strlen($joined) - 1);
        }

        /**
         *
         * @param String $filter Exmple: "x = ? and y = ?"
         * @param Array $params of parameters
         * @return Array Array of DbRow types
         */
        public function LoadAll($filter = null, $params = []) {
            // TOOD: JOIN ALL PARAMS

            if (!is_array($params)) {
                $params = [$params];
            }

            $sql = "select " . $this->_JoinFields(',') . " from " . $this->_tableName;

            if (!is_null($filter)) {
                $sql .= " where " . $filter;
            }
            $dbParams = [];
            foreach ($params as $param) {
                $dbParams[] = new TypeValue($param);
            }

            $results = Connection::ExecuteSQL($sql, $dbParams);
            return $this->_DataToRow($results);
        }

        public function LoadAllByColumns($columns, $values) {
            $filter = '';
            foreach ($columns as $column) {
                $col    = Connection::GetConnection()->real_escape_string($column);
                $filter .= $col . ' = ? AND';
            }
            return $this->LoadAll(rtrim($filter, 'AND'), $values);
        }

        public function LoadAllByColumn($column, $value) {
            $col = Connection::GetConnection()->real_escape_string($column);
            return $this->LoadAll($col . " = ?", $value);
        }

        public function LoadByID($value) {
            return new DbObjInstance($value);
        }

        public function Create() {
            $sql = 'create table if not exists ' . $this->_tableName . '(' . $this->_JoinCreate() . ')';
            Connection::ExecuteSQL($sql);
        }

        private function _DataToRow($data) {
            $instances = [];

            foreach ($data as $singleRow) {
                $instance = new Row($singleRow);
                array_push($instances, $instance);
            }

            return $instances;
        }

        public function GetFieldByKey($key) {
            return $this->_creatables[$key];
        }

        /*
         * @param $key_value: array() of DbKeyValue
         * @param $replace: replaces current values
         */

        public function Set($key_values, $replace = false) {

            if (!is_array($key_values)) {
                $key_values = [$key_values];
            }

            $keys = [];
            $args = [];
            foreach ($key_values as $key_value) {
                $key    = $key_value->GetKey();
                $keys[] = $key;
                $args[] = '?';
            }

            $sql = ($replace ? 'replace' : 'insert') . " into " . $this->_tableName . " (" . join(',', $keys) . ") values( " . join(',', $args) . " )";

            return Connection::ExecuteSQL($sql, $key_values);
        }

    }

}