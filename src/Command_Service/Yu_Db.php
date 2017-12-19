<?php
/**
 * Created by Lky_Vendor.
 * User: Yu
 * Date: 2017/12/19 0019
 * Time: 下午 2:52
 */

namespace lky_vendor\laravel_command\Command_Service;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;
use File;

class Yu_Db extends Command
{
    protected $signature = 'yu:model';
    protected $description = 'make model for your project';
    private $models, $parent_model;

    public function handle()
    {
        $this->models = config('lky_command.db.model_path');
        $this->parent_model = config('lky_command.db.parent_model');
        $db = config('database.connections.mysql.database');
        $sql = "select table_name from information_schema.tables where table_schema='$db' and table_type='base table';";
        $data = DB::select($sql);
        $model_path = app_path($this->models);
        if (!File::isDirectory($model_path)) {
            File::makeDirectory($model_path, $mode = 0777);
        }
        $not_make = config('lky_command.db.not_make_field');
        $i = 0;
        foreach ($data as $d) {
            $file_name = $d->table_name;
            $file_path = app_path($this->models.'/'.$file_name.'.php');
            if (!File::exists($file_path)) {
                $sql_for_tab = "select * from information_schema.columns where table_schema = '$db' and table_name = '$file_name' ;";
                $file_name = str_replace(config('database.connections.mysql.prefix'),"",$file_name);
                $data_for_name = DB::select($sql_for_tab);
                $field_name = "";
                foreach ($data_for_name as $field_name_name) {
                    if (is_array($not_make)&&count($not_make)>0&&in_array($field_name_name->COLUMN_NAME, $not_make)) {
                        break;
                    }else{
                        $field_name .= "'" . "$field_name_name->COLUMN_NAME" . "',";
                    }
                }
                $field_name = substr($field_name, 0, strlen($field_name) - 1);
                $content = $this->model_temp($file_name, $field_name);
                File::put($file_path, $content);
                $i++;
                $this->line($file_path ."     successful make model , now total:".$i);
            }
        }
        $this->line("make db model is successful! time:".Carbon::now()->toDateTimeString());
    }


    private function model_temp($name, $filed)
    {
        $timestamps = config('lky_command.db.show_timestamps')?"":'public $timestamps = false;';
        $carbon = Carbon::now();
        $date = $carbon->toDateString();
        $time = $carbon->toTimeString();
        $content = "<?php
/**
 * Created by lky_command.
 * User: Yu
 * Date: $date
 * Time: $time
 */
namespace App\\$this->models;

class $name extends $this->parent_model
{
    protected \$table = '$name';
    $timestamps
    protected \$fillable = [$filed];

}";
        return $content;
    }

}
