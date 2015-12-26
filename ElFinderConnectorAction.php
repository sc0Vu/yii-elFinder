<?php

/**
 * @author Peter Lai
 * @email alk03073135@gmail.com
 * @licence MIT
 */ 
class ElFinderConnectorAction extends CAction
{
    /**
     * More information https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
     * @property array settings
     */
    public $serverSettings = array();

    public function run()
    {
        // php/connector.php.dist
        $path = dirname(__FILE__).'/php/';
		include_once $path.'elFinderConnector.class.php';
        include_once $path.'elFinder.class.php';
        include_once $path.'elFinderVolumeDriver.class.php';
        include_once $path.'elFinderVolumeLocalFileSystem.class.php';

        $connection = new elFinderConnector(new elFinder($this->serverSettings));
        $connection->run();
    }
}
