<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:06
 */

namespace lky_vendor\laravel_command\Command_Service;

use Illuminate\Console\Command;
use File;

class Yu_Clear_Log extends Yu
{
    protected $signature = 'yu:clslog';
    protected $description = 'clear your laravel log';
    public function handle(){
        $log = storage_path('logs/laravel.log');
        if(File::exists($log)){
            File::put($log, '');
        }
        $is_clear_cache = $this->yu_cfg('cache.clear_cache');
        if($is_clear_cache){
            $this->call('cache:clear');
        }
        $this->line("log is clear");
    }
}
