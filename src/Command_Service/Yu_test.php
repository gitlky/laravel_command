<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 18-2-6
 * Time: 上午9:59
 */

namespace lky_vendor\laravel_command\Command_Service;


class Yu_test extends Yu
{
    protected $signature = 'yu:test';
    protected $description = 'test something';

    public function handle()
    {
        echo 'test';
    }

}
