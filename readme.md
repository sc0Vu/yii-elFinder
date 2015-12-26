<h2>yii-elfinder usage instructions</h2>
<ol>
<li>set server options in controller</li>
<li>
<pre><code>
    public function actions()
    {
        $webRoot = Yii::getPathOfAlias('webroot');
        $baseUrl = Yii::app()->request->baseUrl;
        return array(
            'connector' => array(
                'class' => 'extensions.elFinder.ElFinderConnectorAction',
                'serverSettings' => array(
                    'bind' => array(
                        'download rm duplicate'=>array($this, 'log'), // bind to log
                        ),
                    'roots' => array(
					// one folder
                        array(
                            'driver'        => 'LocalFileSystem',
                            'path'          => $webRoot . '/xxxxx/',
                            'URL'           => $baseUrl . '/xxxxx/',
                            'tmpPath'       => your server tmp directory,
                            'uploadDeny'    => array('all'),
                            'alias'         => folder alias,
                            'attributes'    => array(
                                array(
                                    'pattern' => '/(test)$/',
                                    'read' => true,
                                    'write' => false,
                                    'locked' => false,
                                    'hidden' => false,
                                    ),
                                ),
                            ),
					// another folder
                        array(
                            'driver'        => 'LocalFileSystem',
                            'path'          => another folder path,
                            'URL'           => another url,
                            'tmpPath'       => Your server tmp directory,
                            'uploadDeny'    => array('all'),
                            'uploadAllow'   => array('image', 'text', 'application'),
                       
                        )
                    ),
                )
            ),
        );
    }
</code></pre></li>
<li>set log function(<a href="https://github.com/Studio-42/elFinder/wiki/Logging" target="_blank">detail</a>)</li>
<li><pre><code>
    public function log($cmd, $result, $args, $elfinder)
    {      
        .......
    }
</code></pre></li>
<li>use in view
<pre><code>
    $this->widget('extensions.elFinder.ElFinderWidget', array(
        'clientSettings' => array(
          'resizable' => false,
          'width' => '100%',
          'notifyDelay' => '100',
          'customData' => $customeData,
          'commands' => $commands,
          'lang' => 'zh_TW',
          ),
        'connectorRoute' => 'user/filemanager/connector',
        'htmlOptions' => array('id'=>'ElFinder'), // htmlOptions to elfinder tag
        'async'=>true,  // async get elfinder js
        'defer'=>true,  // defer load elfinder js
        )
</code></pre></li>
<li>5.render file <a href="https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands" target="_blank">commands</a>
<pre><code>
    public function actionElfinder()
    {
    // check priority
    $commands = array('rm download ');
    if (Yii::app()->getRequest()->enableCsrfValidation) {
        $customData = array($csrfTokenName, $csrfToken);
    }
    $this->render(file, compact('commands', 'customeData'));
</code></pre></li>
<li><a href="https://github.com/Studio-42/elFinder" target="_blank">Check elFinder wiki.</a></li>
</ol>