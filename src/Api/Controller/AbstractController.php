<?php
/**
 * AbstractController
 *
 * @author Kensuke Sakakibara
 * @since 2016.11.02
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 */
namespace SuisuiChat\Api\Controller;

abstract class AbstractController
{
    protected $_app;
    
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $app
     */
    public function __construct($app)
    {
        $this->_app = $app;
    }
}
