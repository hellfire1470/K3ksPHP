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
include_once __DIR__."/config.php";
include_once __DIR__."/Database/Db.php";

use K3ksPHP\Database\DBObj as DB;

//$pbj = new DB();
$configTB = new DB("config", "id", ["id", "ckey", "cvalue"]);
$userTB = new DB("user", "id", ["id", "email", "username", "password", "firstname", "lastname", "sessionkey", "sessionkey_rndnum"]);
$usermetaTB = new DB("usermeta", "id", ["id", "user_id", "metakey", "metavalue"]);

$obj = $userTB->LoadAll("id = ?", ['i' => 1])[0];

var_dump($obj->GetAttrs());

echo "<br><br>";

$cons = $configTB->LoadAllByTag('cvalue', 'lol');

var_dump($cons);