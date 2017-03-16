<?php
/**
 * MCategoriesTable
 *
 * @author Kensuke Sakakibara
 * @since 2017.03.16
 * @copyright Copyright (c) 2017, Kensuke Sakakibara.
 * 
 * このプロジェクトではテーブルのJOINを禁止しています。
 * JOINの代わりにIN等を利用してください。
 * ORMのマニュアルはこちら https://laravel.com/docs/5.1/database
 */
namespace SuisuiChat\App\Table;

class MCategoriesTable extends AbstractTable
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        parent::__construct($container, 'm_categories');
    }
    
    /**
     * order_numで並べて全件を取得する
     */
    public function getAllOrderNum()
    {
        $where = array('delete_flg' => 0);
        
        $dbh = $this->_getDbAdapter();
        $categories = $dbh->table($this->_tableName)
            ->where($where)
            ->orderBy('order_num', 'asc')
            ->get();
        
        return $categories;
    }
}
