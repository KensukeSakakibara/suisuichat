<?php
/**
 * AbstractController
 *
 * @author Kensuke Sakakibara
 * @since 2016.11.02
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 * 
 * コントローラーから直接Tableへアクセスは禁止しています。
 * 必ずModelを経由してTableへアクセスしてください。
 * FatControllerを避けてください。
 * SessionはControllerのみで利用してください。
 */
namespace SuisuiChat\App\Controller;

abstract class AbstractController
{
    protected $_container;
    protected $_request;
    protected $_response;
    protected $_params;
    private   $_twig;
    protected $_viewData;
    
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        $this->_container = $container;
        $this->_request   = $container->request;
        $this->_response  = $container->response;
        $this->_params    = $container->request->getParams();
        $this->_twig      = $container->get('view');
        $this->_viewData  = array();
        
        // port番号を取得する
        $appType = $this->_getAppType();
        $port = $this->_container['settings']['application']['server'][$appType]['port'];
        $this->_viewData['port'] = $port;
    }
    
    /**
     * アプリのタイプを取得する
     * 
     * @return string live,staging,develop,localの4種類
     */
    protected function _getAppType()
    {
        $appType = 'local';
        $domainArray = $this->_container['settings']['application']['domain'];
        if ($domainArray['live'] == $_SERVER['HTTP_HOST']) {
            $appType = 'live';
        } elseif ($domainArray['staging'] == $_SERVER['HTTP_HOST']) {
            $appType = 'staging';
        } elseif ($domainArray['develop'] == $_SERVER['HTTP_HOST']) {
            $appType = 'develop';
        }
        return $appType;
    }
    
    /**
     * トランザクション用にDBハンドラを取得する
     * 
     * @return object DBハンドラ
     */
    protected function _getDbAdapter()
    {
        $dbh = $this->_container->get('db_master')->connection();
        return $dbh;
    }
    
    /**
     * 画面画面表示
     * 
     * @param string $template テンプレートのパス
     */
    protected function _render($template)
    {
        // コントローラー名を取得してテンプレートへ差し込む
        $dbg = debug_backtrace();
        $className = $dbg[1]['class'];
        $classNameArray = explode('\\', $className);
        preg_match('/^(.*)Controller$/', $classNameArray[count($classNameArray)-1], $match);
        $controllerName = mb_strtolower($match[1]);
        $this->_viewData['controller'] = $controllerName;
        
        // アクション名を取得してテンプレートへ差し込む
        $functionName = $dbg[1]['function'];
        preg_match('/^(.*)Action$/', $functionName, $match);
        $actionName = mb_strtolower($match[1]);
        $this->_viewData['action'] = $actionName;
        
        // パラメータをテンプレートへ差し込む
        $this->_viewData['params'] = $this->_params;
        
        // 画面表示
        $this->_twig->render($this->_response, $template, $this->_viewData);
    }
}
