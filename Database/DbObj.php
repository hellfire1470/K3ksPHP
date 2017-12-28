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

    class DBObj implements IDbObj {

        private static $TAG = "DBObj";
        private $_tableName;
        private $_fieldId;
        private $_fields;

        public function __construct($table_name, $field_id, $fields = null) {
            if (empty($field_id)) {
                throw new Exception("Error in " . static::$TAG . ": you have to use an unique id for your table");
            }
            if (empty($table_name)) {
                throw new Exception("Error in " . static::$TAG . ": you have to use an unique table name");
            }
            $this->_tableName = $table_name;
            $this->_fieldId = $field_id;
            $this->_fields = $fields;
        }

        /**
         * 
         * @param String $filter Exmple: "x = ? and y = ?"
         * @param Array $params Key is the type (s = string, i = integer, d = double, b = blob). 
         * Value is value. Example: ['s' => 'value', 'i' => 10]
         * @return Array Array of DbObjInstance types 
         */
        public function LoadAll($filter = null, $params = []) {
            $sql = "select " . join(",", $this->_fields) . " from ".$this->_tableName;
            
            if(!is_null($filter)){
                $sql .= " where ". $filter;
            }
            
            $results = DbConnection::ExecuteSQL($sql, $params);
            return $this->_DataToInstances($results);
        }

        public function LoadAllByTag($tag, $value) {
            return $this->LoadAll($tag." = ?", ['s' => $value]);
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
            
        }

        private function _FieldsToAttributes($data) {

            $attr = [];
            
            foreach ($this->_fields as $field) {
                $attr[$field] = $data[$field];
            }
            
            return $attr;
        }

        private function _DataToInstances($data){
            $instances = [];
            
            foreach($data as $singleRow){
                $instance = new DbObjInstance($this->_FieldsToAttributes($singleRow));
                array_push($instances, $instance);
            }
            
            return $instances;
        }
    }

}