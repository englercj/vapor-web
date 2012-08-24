<?php

class LessFilter extends AssetFilter {

  protected $_settings = array(
                               'extensions' => array('.less', '.less.css'),
                               'file' => 'lessc'
                               );

  public function input($fileName, $content) {
    App::import('Vendor', $this->_settings['file']);

    foreach ($this->_settings['extensions'] as $extension) {
      if (strtolower(substr($fileName, -strlen($extension))) == $extension) {
        $lessc = new lessc();
        return $lessc->compileFile($fileName);
      }
    }
    return $content;
  }
}
?>