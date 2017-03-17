<?php
/**
 * TMessagesModel
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
use \SuisuiChat\App\Table\TMessagesTable;

class TMessagesModel extends AbstractModel
{
    /**
     * コントローラ共通コンストラクタ
     * 
     * @param object $container
     */
    public function __construct($container)
    {
        $table = new TMessagesTable($container);
        parent::__construct($container, $table);
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
        $messages = $this->_table->getDataLimitOffset($limit, $offset);
        
        if (!empty($messages)) {
            foreach ($messages as $key => $val) {
                $val['time'] = date('Y/m/d H:i:s', strtotime($val['create_date']));
                $messages[$key] = $val;
            }
        }
        
        return $messages;
    }
    
    /**
     * メッセージを保存する
     * 
     * @param array $messageData 保存するデータ
     * @return integer 保存データのID
     */
    public function saveMessage($name, $email, $message)
    {
        $insertData = array(
            'user_id'    => 0,
            'thread_id'  => 0,
            'name'       => $name,
            'email'      => $email,
            'message'    => $message,
            'ip_address' => '',
        );
        
        return $this->_table->insert($insertData);
    }
}
