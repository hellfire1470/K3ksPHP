<?php

/*
 * The MIT License
 *
 * Copyright 2018 Alexander.
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

namespace K3ksPHP\Database;

/**
 * Description of DbField
 *
 * @author Alexander
 */
abstract class DbFieldType {

    const INTEGER = 'INT';
    const VARCHAR = 'VARCHAR';
    const TEXT    = 'TEXT';

}

abstract class DbFieldAttribute {

    const UNIQUE         = 'UNIQUE';
    const PRIMARY_KEY    = 'PRIMARY KEY';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';
    const NOT_NULL       = 'NOT NULL';

}

class DbField {

    private $_name;
    private $_type;
    private $_size;
    //TODO: ADD ATTRIBUTES OF FIELDS
    private $_attributes;

    //put your code here
    public function __construct($name, $dbFieldType, $size = null, $attributes = null) {
        $this->_name       = $name;
        $this->_type       = $dbFieldType;
        $this->_size       = $size;
        $this->_attributes = !empty($attributes) ? $attributes : [];
    }

    private function _GetCreateAttributes() {
        $createAttr = '';
        if ($this->_attributes != null) {
            foreach ($this->_attributes as $attr) {
                if ($attr instanceof DbFieldAttribute) {
                    $createAttr .= $attr . ' ';
                }
            }
        }
        return $createAttr;
    }

    public function GetFieldCreate() {
        $typestr = $this->_type;
        if ($this->_type !== DbFieldType::TEXT) {
            $typestr .= '(' . $this->_size . ') ' . $this->_GetCreateAttributes();
        }
        return $typestr;
    }

    public function GetName() {
        return $this->_name;
    }

    public function GetFieldType() {
        return $this->_type;
    }

    public function GetFieldSize() {
        return $this->_size;
    }

    public function HasAttr($attr) {
        return in_array($attr, $this->_attributes);
    }

    public function GetAttr() {
        return $this->_attributes;
    }

}
