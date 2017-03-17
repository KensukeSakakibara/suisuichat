<?php
namespace SuisuiChat\Server;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use \SuisuiChat\App\Model\TMessagesModel;

class Chat implements MessageComponentInterface
{
    private $_container;
    protected $clients;

    public function __construct($container)
    {
        $this->_container = $container;
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        //echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        //$numRecv = count($this->clients) - 1;
        //echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
        //    , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        
        $jsonData = (array)json_decode($msg);
        $name    = array_key_exists('name', $jsonData) ? $jsonData['name'] : "";
        $email   = array_key_exists('email', $jsonData) ? $jsonData['email'] : "";
        $message = array_key_exists('message', $jsonData) ? $jsonData['message'] : "";
        
        // 現在時刻を追加する
        $now = date('Y/m/d H:i:s');
        $jsonData['time'] = $now;
        
        // データを保存する
        $dbh = $this->_container->get('db_master')->connection();
        try {
            // トランザクション開始
            $dbh->beginTransaction();
            
            $tMessagesModel = new TMessagesModel($this->_container);
            $id = $tMessagesModel->saveMessage($name, $email, $message);
            
            // トランザクションコミット
            $dbh->commit();
            
        } catch (\Exception $e) {
            $dbh->rollback();
            echo $e->getMessage();
        }
        
        // クライアントへデータを送る
        foreach ($this->clients as $client) {
            $client->send(json_encode($jsonData));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        //echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
