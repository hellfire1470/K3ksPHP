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

/**
 * DBCONN: Handled die Verbindung zur Datenbank
 * @author k3ks.de
 */

namespace K3ksPHP\Database {

    class DbConnection {

        private static $_sConnection;

        /**
         * Returned eine bestehende MySQL-Verbindung
         * @return (mysqli)Connection
         */
        public static function GetConnection() {
            if (is_null(self::$_sConnection)) {
                self::_sInitialize();
            }
            if (self::$_sConnection instanceof \mysqli) {
                return self::$_sConnection;
            }
            throw new Exception("Failed to connect to mysql");
        }

        private static function _sInitialize() {
            register_shutdown_function('K3ksPHP\Database\DbConnection::sDisconnect');

            self::$_sConnection = new \mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB, MYSQL_PORT);

            if (self::$_sConnection->connect_errno) {
                throw new Exception('Failed to connect to MySQL: (' . self::$_sConnection->connect_errno . ') ' . self::$_sConnection->connect_error);
            }
        }

        /**
         * Beendet die Verbindung zum MySQL Server
         */
        static function sDisconnect() {
            if (self::$_sConnection instanceof \mysqli) {
                self::$_sConnection->close();
            }
        }

        /**
         *
         * @param String $sql is the SQL statement. use ? instead of parameter. Example: "select * from TABLE where x = ?"
         * @param Array $params Key is the type (s = string, i = integer, d = double, b = blob).
         * Value is value. Example: array of type DbTypeValue
         * @return Array Results of statement
         */
        static function ExecuteSQL($sql, $params = []) {
            $conn = self::GetConnection();

            $stmt = $conn->prepare($sql);


            if (!empty($conn->error))
                echo $conn->error;

            $types  = [];
            $values = [];

            foreach ($params as $typeValue) {
                if (!$typeValue instanceof DbTypeValue) {
                    throw new \Exception('Parameter has to be TypeOf DbTypeValue');
                }
                $types[]  = $typeValue->GetType();
                $values[] = $typeValue->GetValue();
            }

            if (sizeof($params) > 0) {
                $stmt->bind_param(join('', $types), ...$values);
            }

            $stmt->execute();

            $result  = $stmt->get_result();
            $results = [];
            if (!is_bool($result)) {
                while ($myrow = $result->fetch_assoc()) {
                    array_push($results, $myrow);
                }

                $result->free();

                return $results;
            }
            return $result;
        }

    }

}