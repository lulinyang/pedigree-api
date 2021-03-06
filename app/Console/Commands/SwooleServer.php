<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WebSocketController;
use DB;
use Illuminate\Support\Facades\Redis;

class SwooleServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'swoole websocket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        
        //创建server
        $server = new \Swoole\WebSocket\Server("0.0.0.0", 9501);
        // $server->set(array(
        //     'dispatch_mode'=> 5
        // ));
        
        //连接成功回调
        $server->on('open', function (\Swoole\WebSocket\Server $server, $request) {
            $uid = '';
            try {
                $query_string = $request->server['query_string'];
                if($query_string) {
                    $uid = explode("=", $query_string)[1];
                    Redis::sadd('uid_'.$uid, $request->fd);
                    session()->put('uid_fid', 'uid_'.$uid);
                }
            } catch (\Throwable $th) {
               
            }
            $this->info($request->fd . '链接成功'. $uid);
        });

        //收到消息回调
        $server->on('message', function (\Swoole\WebSocket\Server $server, $frame) {
            $content = $frame->data;
            $fd = $frame->fd;
            try {
                $data = json_decode($frame->data, true);
                $action = $data['action'];
                $controller = new WebSocketController();
                $controller->$action($server, $data['content'], $fd);
            } catch (\Throwable $th) {
                var_dump($th->getMessage());
                // //推送给所有链接
                // foreach ($server->connections as $fd){
                //     $server->push($fd,$content);
                // }
            }
        });

        //关闭链接回调
        $server->on('close', function ($ser, $fd) {
            $res = Redis::sismember(session('uid_fid'), $fd);
            if($res) {
                Redis::srem(session('uid_fid'), $fd);
            }
            $this->info($fd . '断开链接'.session('uid_fid'));
        });

        $server->start();
    }
}