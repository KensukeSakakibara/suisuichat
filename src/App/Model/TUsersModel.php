<?php
/**
 * TUsersModel
 *
 * @author Kensuke Sakakibara
 * @since 2016.11.04
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 * 
 * Modelと同名でないTableへのアクセスは禁止しています。
 * 別のTableへアクセスする際は、必ず該当のModelを経由してアクセスしてください。
 */
namespace SuisuiChat\App\Model;

// TusersTable以外のTableは使用してはいけません
use \SuisuiChat\App\Table\TUsersTable;

class TUsersModel extends AbstractModel
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        $table = new TUsersTable($container);
        parent::__construct($container, $table);
    }
    
    /**
     * ユーザを登録する
     * 
     * @return integer 登録したユーザID
     */
    public function addUser()
    {
        $userData = array(
            'status'     => 0,
            'email'      => 'example4@sakakick.com',
            'password'   => '********',
            'first_name' => 'test',
            'last_name'  => 'taro',
        );
        
        return $this->_table->insert($userData);
    }
    
    /**
     * ユーザを更新する
     */
    public function updateUser()
    {
        $userData = array(
            'status'     => 3,
            'email'      => 'example333@sakakick.com',
            'password'   => '********33',
            'first_name' => 'test3',
            'last_name'  => 'taro3',
        );
        
        return $this->_table->getMaster()->update(5, $userData);
    }
    
    /**
     * ユーザを削除する
     */
    public function deleteUser()
    {
        return $this->_table->delete(7);
    }
    
    /**
     * ユーザーを条件付きで取得する
     * 
     * @return array 取得したデータ1件
     */
    public function getDataByEmail()
    {
        $email = 'sakakick@gmail.com';
        return $this->_table->getDataByEmail($email);
    }
}
