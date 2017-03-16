<?php
/**
 * MCategoriesSubModel
 *
 * @author Kensuke Sakakibara
 * @since 2017.03.16
 * @copyright Copyright (c) 2017, Kensuke Sakakibara.
 * 
 * Modelと同名でないTableへのアクセスは禁止しています。
 * 別のTableへアクセスする際は、必ず該当のModelを経由してアクセスしてください。
 */
namespace SuisuiChat\App\Model;

// TusersTable以外のTableは使用してはいけません
use \SuisuiChat\App\Table\MCategoriesSubTable;

class MCategoriesSubModel extends AbstractModel
{
    /**
     * コントローラ共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container)
    {
        $table = new MCategoriesSubTable($container);
        parent::__construct($container, $table);
    }
    
    /**
     * order_numで並べて全件を取得する
     */
    public function getAllOrderNum()
    {
        return $this->_table->getAllOrderNum();
    }
}
