<?php
/**
 * TUsersTable
 *
 * @author Kensuke Sakakibara
 * @since 2016.11.04
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 * 
 * このプロジェクトではテーブルのJOINを禁止しています。
 * JOINの代わりにIN等を利用してください。
 * ORMのマニュアルはこちら https://laravel.com/docs/5.1/database
 */
namespace SuisuiChat\App\Table;

class TUsersTable extends AbstractTable
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        parent::__construct($container, 't_users');
    }
    
    /**
     * メールアドレスを元にユーザを取得する（ユニーク）
     * 
     * @param string $email E-mailアドレス
     * @return array 取得したデータ1件
     */
    public function getDataByEmail($email)
    {
        $where = array('email' => $email);
        
        $dbh = $this->_getDbAdapter();
        $userData = $dbh->table($this->_tableName)->where($where)->first();
        
        return $userData;
    }
}
