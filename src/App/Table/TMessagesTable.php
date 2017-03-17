<?php
/**
 * TMessagesTable
 *
 * @author Kensuke Sakakibara
 * @since 2017.03.17
 * @copyright Copyright (c) 2017, Kensuke Sakakibara.
 * 
 * このプロジェクトではテーブルのJOINを禁止しています。
 * JOINの代わりにIN等を利用してください。
 * ORMのマニュアルはこちら https://laravel.com/docs/5.1/database
 */
namespace SuisuiChat\App\Table;

class TMessagesTable extends AbstractTable
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        parent::__construct($container, 't_messages');
    }
    
    /**
     * 件数を指定してデータを取得する
     * 
     * @param integer $limit 取得件数
     * @param integer $offset データ取得位置
     * @return array 取得したデータ
     */
    public function getDataLimitOffset($limit, $offset)
    {
        $where = array('delete_flg' => 0);
        
        $dbh = $this->_getDbAdapter();
        $messages = $dbh->table($this->_tableName)
            ->where($where)
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        return $messages;
    }
}
