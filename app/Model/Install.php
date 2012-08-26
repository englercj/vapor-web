<?php

class Install extends AppModel {

    public $name = 'Install';
    public $useTable = false;

    //Create database Schema
    function createSchema($ds) {
        $this->execSqlFile($ds, APP . 'Config' . DS . 'Sql' . DS . 'schema.sql');
        $this->execSqlFile($ds, APP . 'Config' . DS . 'Schema' . DS . 'db_acl.sql');
    }

    //Insert Static Data
    function insertStatic($ds) {
        $this->execSqlFile($ds ,APP . 'Config' . DS . 'Sql' . DS . 'engines.sql');
        $this->execSqlFile($ds, APP . 'Config' . DS . 'Sql' . DS . 'games.sql');
    }

    //Save database configuration
    function saveDb($data = array()) {
        return $this->saveX(array(
                    'Database/Mysql',
                    '%host%',
                    '%port%',
                    '%database%',
                    '%login%',
                    '%password%',
                    '%prefix%',
                    '%encoding%'
                        ), $data, 'database'
        );
    }

    //Save email configuration
    function saveEmail($data = array()) {
        return $this->saveX(array(
                    '%from%',
                    '%host%',
                    "'%port%'",
                    '%username%',
                    '%password%',
                        ), $data, 'email'
        );
    }

    function saveX($tokens, $data, $which) {
        $config = APP . 'Config/' . $which . '.php';
        $template = APP . 'Config/' . $which . '.php.default';
        
        $new_file = implode(file($template));

        $str = str_replace($tokens, $data, $new_file);

        //now, TOTALLY rewrite the file
        $fp = fopen($config, 'w');

        fwrite($fp, $str, strlen($str));

        return $str;
    }

    function execSqlFile($ds, $file) {
        $statements = file_get_contents($file);
        $statements = explode(';', $statements);

        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                $ds->rawQuery($statement);
            }
        }
    }

}