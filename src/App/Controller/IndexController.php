<?php
/**
 * IndexController
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

use \SuisuiChat\App\Model\TUsersModel;

class IndexController extends AbstractController
{
    /**
     * コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        parent::__construct($container);
    }
    
    /**
     * デフォルトページ
     */
    public function indexAction()
    {
        $tUsersModel = new TUsersModel($this->_container);
        $users = $tUsersModel->getDataByEmail();

        $dbh = $this->getDbAdapter();
        try {
            // トランザクション開始
            $dbh->beginTransaction();
            
            $id = $tUsersModel->updateUser();
            //$id = $tUsersModel->addUser();
            //$id = $tUsersModel->deleteUser();
            
            // トランザクションコミット
            $dbh->commit();
            
        } catch (\Exception $e) {
            $dbh->rollback();
            error_log($e->getMessage());
        }
        
        $this->_viewData = array('users' => $users);
        
        // 画面画面表示
        $this->_render('app/index/index.twig');
    }
}
