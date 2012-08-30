<?php

/**
 * Base route overrides 
 */
	Router::connect('/', array('controller' => 'dashboard', 'action' => 'index'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
    
/**
 * Connect pages controller routes 
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
