<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:32
 */

namespace lky_vendor\laravel_command\Command_Service;


use Illuminate\Console\Command;

class Yu extends Command
{
    const cfg_title = "lky_command.";

    public function yu_cfg($name)
    {
        return config(self::cfg_title.$name);
    }

    public function mk_dir_write($path,$content)
    {
        $old_path = $path;
        $path = str_replace(base_path(),"",$path);
        $ctrl_path = str_replace("\\","/",$path);
        $set_up = explode("/",$ctrl_path);
        unset($set_up[count($set_up)-1]);
        $path = "";
        foreach ($set_up as $dir){
            $this->line($dir);
            if(empty($dir)){
                continue;
            }
            $path.=$dir.'/';
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, $mode = 0777);
            }
        }
        $this->line('aa:'.count(explode('.php',$old_path)));
        $old_path = count(explode('.php',$old_path))>1?$old_path:$old_path.'.php';
        if (!File::exists($old_path)){
            File::put($old_path, $content);
        }
    }
}
