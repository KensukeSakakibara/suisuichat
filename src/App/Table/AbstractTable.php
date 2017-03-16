<?php
/**
 * AbstractTable
 * 
 * @author Kensuke Sakakibara
 * @since 2016.11.02
 * @copyright Copyright (c) 2016, Kensuke Sakakibara.
 * 
 * このプロジェクトではテーブルのJOINを禁止しています。
 * JOINの代わりにIN等を利用してください。
 * ORMのマニュアルはこちら https://laravel.com/docs/5.1/database
 */
namespace SuisuiChat\App\Table;

abstract class AbstractTable
{
    protected $_container;
    protected $_tableName;
    private $_getMasterFlg;

    /**
     * テーブル共通コンストラクタ
     * 
     * @param object $container コンテナオブジェクト
     * @param string $tableName テーブル名
     */
    public function __construct($container, $tableName)
    {
        $this->_container = $container;
        $this->_tableName = $tableName;
        $this->_getMasterFlg = false;
    }

    /**
     * DBアダプタのオブジェクトを取得する
     * 
     * @return object テーブルオブジェクト
     */
    protected function _getDbAdapter()
    {
        if ($this->_getMasterFlg) {
            $dbh = $this->_container->get('db_master');
            $dbh = $dbh->setFetchMode(\PDO::FETCH_ASSOC);
            return $dbh;
        } else {
            // スレーブのラウンドロビン
            $rondCount = count($this->_container['settings']['db']) - 1; // masterは除くのでマイナス1
            $slaveNum = rand(1, $rondCount);
            $slaveNumStr = str_pad($slaveNum, 2, 0, STR_PAD_LEFT);
            
            // 該当テーブルを取得
            $dbh = $this->_container->get('db_slave'. $slaveNumStr);
            $dbh = $dbh->setFetchMode(\PDO::FETCH_ASSOC);
            return $dbh;
        }
    }

    /**
     * レプリケーションの遅延でスレーブから読めないときはマスターを明示的に指定して読む
     * 
     * @return object 自分自身をリターン
     */
    public function getMaster()
    {
        $this->_getMasterFlg = true;
        return $this;
    }

    /**
     * 該当テーブルの全データを取得する（基本的に使わない）
     * 
     * @return array データ全件
     */
    public function getAll()
    {
        $dbh = $this->_getDbAdapter();
        return $dbh->table($this->_tableName)->get()->toArray();
    }

    /**
     * IDをキーにして対象のデータを取得する
     * 
     * @param integer $id ID番号（pkey）
     * @return array 取得したデータ1件
     */
    public function getRowById($id)
    {
        $dbh = $this->_getDbAdapter();
        return $dbh->table($this->_tableName)->find($id);
    }

    /**
     * データを保存する
     * 
     * @param array $insertData インサートするデータの配列
     * @return integer シーケンス番号
     */
    public function insert($insertData)
    {
        // 書き込みは必ずmasterになります
        $this->_getMasterFlg = true;
        $dbh = $this->_getDbAdapter();
        
        // 現在の日時を取得する
        $nowDate = date('Y-m-d H:i:s');
        
        $userId = 0;
        if (!$userId) {
            $userId = 0;
        }
        
        $insertData['create_date']    = $nowDate;
        $insertData['create_user_id'] = $userId;
        $insertData['update_date']    = $nowDate;
        $insertData['update_user_id'] = $userId;
        $insertData['delete_user_id'] = 0;
        $insertData['delete_flg']     = 0;
        
        // 書き込みつつデータのIDを取得する
        $lastInsertId = $dbh->table($this->_tableName)->insertGetId($insertData);
        
        return $lastInsertId;
    }

    /**
     * データを更新する
     * 
     * @param array $where 更新対象
     * @param array $updateData アップデートするデータの配列
     * @return boolean
     */
    public function update($where, $updateData)
    {
        // 書き込みは必ずmasterになります
        $this->_getMasterFlg = true;
        $dbh = $this->_getDbAdapter();
        
        if (!is_array($where)) {
            $where = array('id' => $where);
        }
        
        // 現在の日時を取得する
        $nowDate = date('Y-m-d H:i:s');
        
        $userId = 0;
        if (!$userId) {
            $userId = 0;
        }
        
        $updateData['update_date'] = $nowDate;
        $updateData['update_user_id'] = $userId;
        
        // 更新を実行する
        $dbh->table($this->_tableName)->where($where)->update($updateData);
        
        return true;
    }

    /**
     * データを論理削除する
     * 
     * @param array $where 削除対象
     * @return boolean
     */
    public function delete($where)
    {
        // 書き込みは必ずmasterになります
        $this->_getMasterFlg = true;
        $dbh = $this->_getDbAdapter();
        
        if (!is_array($where)) {
            $where = array('id' => $where);
        }
        
        // 現在の日時を取得する
        $nowDate = date('Y-m-d H:i:s');
        
        $userId = 0;
        if (!$userId) {
            $userId = 0;
        }
        
        $deleteData = array(
            'delete_date' => $nowDate,
            'delete_user_id' => $userId,
            'delete_flg' => 1,
        );
        
        // 更新を実行する
        $dbh->table($this->_tableName)->where($where)->update($deleteData);
        
        return true;
    }

    /**
     * 全データを完全に物理消去する（通常使いません）
     * 
     * @return boolean
     */
    public function truncate()
    {
        // 書き込みは必ずmasterになります
        $this->_getMasterFlg = true;
        $dbh = $this->_getDbAdapter();
        
        // 消去実行
        $dbh->table($this->_tableName)->truncate();
        
        return true;
    }

    /**
     * 任意のSELECT文を実行する
     * 
     * @param string $sql 実行するSQL
     * @param array $params プレースホルダに差し込む値の配列
     * @return array 取得したデータ
     */
    protected function selectSql($sql, $params = array())
    {
        $dbh = $this->_getDbAdapter()->connection();
        $results = $dbh->select($sql, $params);
        return $results;
    }

    /**
     * 任意のSELECT文を実行して1件のデータを返す
     * 
     * @param string $sql 実行するSQL
     * @param array $params プレースホルダに差し込む値の配列
     * @return array 取得したデータ
     */
    protected function selectRowSql($sql, $params = array())
    {
        $dbh = $this->_getDbAdapter()->connection();
        $ret = $dbh->select($sql, $params);
        $results = array();
        if (is_array($ret)) {
            $results = $ret[0];
        }
        return $results;
    }

    /**
     * 任意のSQLを実行させる（基本的に使いません）
     * 
     * @param string $sql 実行するSQL
     * @param array $params プレースホルダに差し込む値の配列
     * @return array 取得したデータ
     */
    protected function executeSql($sql)
    {
        // どのようなSQLが来るかわからないので全てmasterにしておきます
        $this->_getMasterFlg = true;
        $dbh = $this->_getDbAdapter()->connection();
        $results = $dbh->statement($sql);
        return $results;
    }
}
