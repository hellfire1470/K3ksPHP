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

require_once 'ICreatable.php';

abstract class RuleType {

    const UNIQUE      = 'UNIQUE KEY';
    const PRIMARY_KEY = 'PRIMARY KEY';

}

/**
 * Description of DbFieldRule
 *
 * @author Alexander
 */
class Rule implements ICreatable {

    private $_ruleType;
    private $_name;
    private $_data;

    //put your code here
    public function __construct($ruleType, $data, $name = null) {
        $this->_ruleType = $ruleType;
        if (empty($name)) {
            $this->_name = uniqid();
        }
        $this->_data = $data;
    }

    public function GetCreate() {
        $typestr = $this->_ruleType . ' ' . ($this->_ruleType == RuleType::PRIMARY_KEY ? '' : $this->GetName());
        $typestr .= '(' . join(',', $this->_data) . ')';
        return $typestr;
    }

    public function GetName() {
        return $this->_name;
    }

}
