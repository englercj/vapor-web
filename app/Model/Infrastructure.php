<?php

App::uses('AppModel', 'Model');

/**
 * Infrastructure Model
 *
 */
class Infrastructure extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'infrastructure';

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

}
