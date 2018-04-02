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

    class DbObjRow implements IDbObjRow {

        private $_attr;
        private $_meta;

        public function __construct($attr, $meta = null) {
            $this->_attr = $attr;
            $this->_meta = $meta;
        }

        public function Create() {

        }

        public function Delete() {

        }

        public function GetAttr($attr) {
            if ($this->HasAttr($attr)) {
                return $this->_attr[$attr];
            }
            return null;
        }

        public function GetAttrs() {
            return $this->_attr;
        }

        public function GetMeta($meta = null) {

        }

        public function HasAttr($attr) {
            return array_key_exists($attr, $this->_attr);
        }

        public function HasMeta($meta) {

        }

        public function IsLoaded() {

        }

        public function RemoveMeta($meta) {

        }

        public function Save() {

        }

        public function SetAttr($attr, $value = null) {

        }

        public function SetMeta($meta, $value = null) {

        }

    }

}