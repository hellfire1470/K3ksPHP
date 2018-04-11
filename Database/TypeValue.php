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
 * Description of DBKeyValue
 *
 * @author Alexander
 */
class TypeValue {

    private $_type;
    private $_value;

    //put your code here
    public function __construct($value, $type = null) {
        if ($type != null) {
            $this->_type = $type;
        } else {
            // TODO: detect blobs
            if (is_integer($value)) {
                $this->_type = 'i';
            } else if (is_double($value)) {
                $this->_type = 'd';
            } else {
                $this->_type = 's';
            }
        }
        $this->_value = $value;
    }

    public function GetType() {
        return $this->_type;
    }

    public function GetValue() {
        return $this->_value;
    }

}
