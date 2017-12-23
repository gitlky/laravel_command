<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:32
 */

namespace lky_vendor\laravel_command\Command_Service;


use Illuminate\Console\Command;
use File;
class Yu extends Command
{
    const cfg_title = "lky_command.";

    public function yu_cfg($name)
    {
        return config(self::cfg_title.$name);
    }


}
