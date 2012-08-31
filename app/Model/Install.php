<?php

class Install extends AppModel {

    public $name = 'Install';
    public $useTable = false;

    //Create database Schema
    function createSchema($ds) {
        $this->execSqlFile($ds, APP . 'Config' . DS . 'Sql' . DS . 'schema.sql');
        $this->execSqlFile($ds, APP . 'Config' . DS . 'Schema' . DS . 'db_acl.sql');
    }

    //Save database configuration
    function saveDb($data = array()) {
        return $this->saveX(
                array(
                    'Database/Mysql',
                    '%host%',
                    '%port%',
                    '%database%',
                    '%login%',
                    '%password%',
                    '%prefix%',
                    '%encoding%'
                ), 
                $data,
                'database.php',
                'database.php.default'
            );
    }

    //Save email configuration
    function saveEmail($data = array()) {
        return $this->saveX(
                array(
                    '%from%',
                    '%host%',
                    "'%port%'",
                    '%username%',
                    '%password%',
                ),
                $data,
                'email.php',
                'email.php.default'
            );
    }
    
    //Save random Security Salt
    function saveSalt($salt = null) {
        if($salt == null) {
            //generate random salt
            $salt = $this->randString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 64);
        }
        
        $this->saveX(
            array('dqrfLYPeqno1Z5ulPDrXbkdInpG4YmqdJgqsTmaxCdArO8jRLlq81gAhkW1vaHuk'),
            array($salt),
            'core.php',
            'core.php'
        );
        
        return $salt;
    }
    
    //Save random Cipher Seed
    function saveCipherSeed($seed = null) {
        if($seed == null) {
            //generate random seed
            $seed = $this->randString('0123456789', 32);
        }
        
        $this->saveX(
            array('74656082139533326018816721372566'),
            array($seed),
            'core.php',
            'core.php'
        );
        
        return $seed;
    }

    function saveX($tokens, $data, $file, $file_default) {
        $config = APP . 'Config/' . $file;
        $template = APP . 'Config/' . $file_default;
        
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
    
    function randString($tokens, $length) {
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= $tokens[rand(0, strlen($tokens) - 1)];
        }
        
        return $string;
    }

}