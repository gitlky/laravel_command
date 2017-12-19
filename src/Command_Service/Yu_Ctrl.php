<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:44
 */

namespace lky_vendor\laravel_command\Command_Service;


class Yu_Ctrl extends Yu
{
    protected $signature = 'yu:ctrl {path : input where you wanna make controller}';
    protected $description = 'make controller for your project';
    public function handle(){
        $path = $this->argument('path');
        $ctrl_path = app_path('Http/Controllers/'.$path);
        $ctrl_path = str_replace("\\","/",$ctrl_path);
        if (!File::exists($ctrl_path)){
            $this->mk_dir($ctrl_path);
        }
        #$this->line("make file successful");
    }
}
