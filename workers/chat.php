<?php
/**
 * Created by PhpStorm.
 * User: sotoros
 * Date: 13.03.2019
 * Time: 21:41
 */

require dirname(__DIR__) . '/vendor/autoload.php';

use Workerman\Worker;
use PHPSocketIO\SocketIO;

$io = new SocketIO(1228);

$io->on('connection', function($socket) use ($io) {
   $socket->on('chat_message', function($msg) use ($io) {
       $io->emit('chat_message', $msg);
   });
});

Worker::runAll();