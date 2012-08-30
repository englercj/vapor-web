<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class VaporCommon {    
    static public function ensureFile($file, $dir = null) {
        if($dir == null) {
            $dir = APP . 'Config';
        }
        
        $path = $dir . DS . $file;
        $default = $path . '.default';

        $f = new File($path);
        $d = new File($default);

        if(!$f->exists())
            $d->copy($path);
        
        return null;
    }
}
