<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:44
 */

namespace lky_vendor\laravel_command\Command_Service;

use File;
class Yu_Ctrl extends Yu
{
    protected $signature = 'yu:ctrl {path : input where you wanna make controller}';
    protected $description = 'make controller for your project';
    public function handle(){
        $path = $this->argument('path');
        $model_name = $this->ask("what's your name");
        $model_path = app_path(config('db.model_path').'/'.$model_name.'.php');
        if(!File::exists($model_path)){
            $this->error("Model does not exist");
            return;
        }
        $ctrl_path = app_path('Http/Controllers/'.$path);
        $ctrl_path = str_replace("\\","/",$ctrl_path);
        if (!File::exists($ctrl_path)){
            $this->mk_dir_write($ctrl_path,'');
        }
        #$this->line("make file successful");

    }

    private function ctrl_temp(){

    }
}
