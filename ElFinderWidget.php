<?php

/**
 * @author Peter Lai
 * @email alk03073135@gmail.com
 * @licence MIT
 */ 
class ElFinderWidget extends CWidget
{
    /**
     * Client settings.
     * More about https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
     *
     * elFinder client settings
     * @property array $clientSettings
     *
     * elFinder tag html options
     * @property array $htmlOptions
     *
     * async load elFinder javascript
     * @property aboolean $async
     *
     * defer load elFinder javascript
     * @property boolean $defer
     *
     * elFinder connector 
     * @property string $connectorRoute
     *
     * assets dir
     * @property string $assetsDir
     *
     * elFinder script id
     * @property string $elScriptName;
     */
    public $clientSettings = array();

    public $htmlOptions = array();

    public $async = true;

    public $defer = true;

    public $connectorRoute = false;

    private $assetsDir;

    private $elScriptName = '_elFinder';

    public function init()
    {
        if (empty($this->connectorRoute)) throw new CException('$connectorRoute must be set!');
        $this->clientSettings['lang'] = (isset($this->clientSettings['lang'])) ? $this->clientSettings['lang'] : Yii::app()->language;
        $this->clientSettings['url'] = Yii::app()->createUrl($this->connectorRoute);

        $dir = dirname(__FILE__) . '/assets';
        $this->assetsDir = Yii::app()->assetManager->publish($dir);
        $elFinderJs = (YII_DEBUG) ? $this->assetsDir . '/js/elfinder.full.js' : $this->assetsDir . '/js/elfinder.min.js';
        $cs = Yii::app()->getClientScript();

        // register css files
        // register elFinder asset
        $cs->registerCssFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css');
        $cs->registerCssFile($this->assetsDir.'/css/elfinder.min.css');
        $cs->registerCssFile($this->assetsDir.'/css/theme.css');

        // jQuery and jQuery UI
        // for jquery 1.9+ migrate old function
        // more information: https://github.com/jquery/jquery-migrate/
        $cs->registerCoreScript('jquery.ui');
        $cs->registerScriptFile('https://code.jquery.com/jquery-migrate-1.2.1.js', CClientScript::POS_HEAD, array('async'=>$this->async, 'defer'=>$this->defer));

        $cs->registerScriptFile($elFinderJs, CClientScript::POS_HEAD, array('async'=>$this->async, 'defer'=>$this->defer, 'id'=>$this->elScriptName));
    }

    public function run()
    {
        $this->htmlOptions['id'] = (isset($this->htmlOptions['id'])) ? $this->htmlOptions['id'] : $this->getId();
        $clientSettings = CJavaScript::encode($this->clientSettings);
        $langJs = $this->assetsDir.'/js/i18n/elfinder.'.$this->clientSettings['lang'].'.js';
        $cs = Yii::app()->getClientScript();
        if ($this->async === true) {
            $asyncJs = <<<JS
            (function (h) {
                function scriptOnload (e) {
                    var el = document.createElement("SCRIPT");
                    function loadElFinder() {
                        $('#{$this->htmlOptions['id']}').elfinder($clientSettings);
                    }
                    el.src = "{$langJs}";
                    el.id = "{$this->elScriptName}_lang";
                    el.addEventListener('load', loadElFinder, false);
                    h.appendChild(el);   
                }
                h.querySelector('#{$this->elScriptName}').addEventListener("load", scriptOnload, false);
            })(document.head);
JS;
            $cs->registerScript(get_class($this), $asyncJs,CClientScript::POS_HEAD);
        } else {
            $cs->registerScriptFile($this->assetsDir.'/js/i18n/elfinder.'.$this->clientSettings['lang'].'.js', CClientScript::POS_HEAD,  array('async'=>$this->async, 'defer'=>$this->defer));
            $cs->registerScript('elFinder#'.$this->htmlOptions['id'], "$('#".$this->htmlOptions['id']."').elfinder($clientSettings);", CClientScript::POS_LOAD);
        }
        echo CHtml::tag('div', $this->htmlOptions, '', true);
    }
}
