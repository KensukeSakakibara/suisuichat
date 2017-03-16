<?php
/**
 * AbstractModel
 *
 * @author Kensuke Sakakibara
 * @since 2016.11.02
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 * 
 * Modelと同名でないTableへのアクセスは禁止しています。
 * 別のTableへアクセスする際は、必ず該当のModelを経由してアクセスしてください。
 */
namespace SuisuiChat\App\Model;

abstract class AbstractModel
{
    protected $_container;
    protected $_table;
    
    /**
     * モデル共通コンストラクタ
     *
     * @param object $container
     */
    public function __construct($container, $table)
    {
        $this->_container = $container;
        $this->_table = $table;
    }
    
    /**
     * 該当テーブルの全データを取得する
     * 
     * @return array データ全件
     */
    public function getAll()
    {
        return $this->_table->getAll();
    }
    
    /**
     * IDをキーにして対象のデータを取得する
     * 
     * @param integer $id ID番号（pkey）
     * @return array 取得したデータ1件
     */
    public function getRowById($id)
    {
        return $this->_table->getRowById($id);
    }
}
