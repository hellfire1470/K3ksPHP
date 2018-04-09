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

    require_once __DIR__ . "/IDbTable.php";
    require_once __DIR__ . "/DbField.php";

    class DbTable implements IDbTable {

        private static $TAG = "DBObj";
        private $_tableName;
        private $_fields;
        private $_fieldsByName = [];

        /**
         *
         * @param String table_name Name of the table
         * @param String field_id Name of Primary Key
         * @return Array of DbFields
         */
        public function __construct($table_name, $fields) {
            if (empty($table_name)) {
                throw new Exception("Error in " . static::$TAG . ": you have to use an unique table name");
            }
            if (empty($fields)) {
                throw new Exception("Error in " . static::$TAG . ": fields are empty");
            }
            $this->_tableName = $table_name;
            $this->_fields = $fields;
            foreach ($fields as $field) {
                $this->_fieldsByName[$field->GetName()] = $field;
            }
        }

        private function _JoinFields($seperator, $withTypes = false) {
            $joined = '';
            foreach ($this->_fields as $field) {
                if ($field instanceof DbField) {
                    $joined .= $field->GetName() . ' ' . ($withTypes ? $field->GetFieldCreate() : '') . $seperator;
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
                $dbParams[] = new DbTypeValue($param);
            }

            $results = DbConnection::ExecuteSQL($sql, $dbParams);
            return $this->_DataToRow($results);
        }

        public function LoadAllByTag($tag, $value) {
            $_tag = DbConnection::GetConnection()->real_escape_string($tag);
            return $this->LoadAll($_tag . " = ?", $value);
        }

        public function LoadAllByColumn($column, $key) {
            $col = DbConnection::GetConnection()->real_escape_string($column);
            return $this->LoadAll($col . " = ?", $key);
        }

        public function LoadByID($value) {
            return new DbObjInstance($value);
        }

        public function LoadByMetaKey($key) {

        }

        public function LoadByTag($tag, $value) {

        }

        public function LoadByTags($keys) {

        }

        public function Create() {
            $sql = 'create table if not exists ' . $this->_tableName . '(' . $this->_JoinFields(',', true) . ')';
            DbConnection::ExecuteSQL($sql);
        }

        private function _DataToRow($data) {
            $instances = [];

            foreach ($data as $singleRow) {
                $instance = new DbRow($singleRow);
                array_push($instances, $instance);
            }

            return $instances;
        }

        public function GetFieldByKey($key) {
            return $this->_fieldsByName[$key];
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
                $key = $key_value->GetKey();
                $keys[] = $key;
                $args[] = '?';
            }

            $sql = ($replace ? 'replace' : 'insert') . " into " . $this->_tableName . " (" . join(',', $keys) . ") values( " . join(',', $args) . " )";

            return DbConnection::ExecuteSQL($sql, $key_values);
        }

    }

}