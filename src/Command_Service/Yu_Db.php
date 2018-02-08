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

class Yu_Db extends Yu
{
    protected $signature = 'yu:model';
    protected $description = 'make model for your project';
    private $models, $parent_model;

    public function handle()
    {
        $this->models = str_replace("\\",'/',$this->yu_cfg('db.model_path'));
        $this->parent_model = $this->yu_cfg('db.parent_model');
        $db = config('database.connections.mysql.database');
        $sql = "select table_name from information_schema.tables where table_schema='$db' and table_type='base table';";
        $data = DB::select($sql);
        $fx = str_replace("\\","/",app_path($this->models));
        $model_path = str_replace("/App","",$fx);
        if (!File::isDirectory($model_path)) {
            File::makeDirectory($model_path, $mode = 0777);
        }
        $not_make = $this->yu_cfg('db.not_make_field');
        $i = 0;
        foreach ($data as $d) {
            $file_name = $d->table_name;
            $file_name_for_file = str_replace(config('database.connections.mysql.prefix'),"",$file_name);
            $file_path = str_replace("/App","",app_path($this->models.'/'.$file_name_for_file.'.php'));
            if (!File::exists($file_path)) {
                $sql_for_tab = "select * from information_schema.columns where table_schema = '$db' and table_name = '$file_name' ;";
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
                $content = $this->model_temp($file_name_for_file, $field_name);
                File::put($file_path, $content);
                $i++;
                $this->line($file_path ."     successful make model , now total:".$i);
            }
        }
        $this->line("make db model is successful! time:".Carbon::now()->toDateTimeString());
    }


    private function model_temp($name, $filed)
    {
        $timestamps = $this->yu_cfg('db.show_timestamps')?"":'public $timestamps = false;';
        $carbon = Carbon::now();
        $date = $carbon->toDateString();
        $time = $carbon->toTimeString();
        $namespace = $this->yu_cfg('db.model_path');
        $content = "<?php
/**
 * Created by lky_command.
 * User: Yu
 * Date: $date
 * Time: $time
 */
namespace $namespace;

class $name extends $this->parent_model
{
    protected \$table = '$name';
    $timestamps
    protected \$fillable = [$filed];

}";
        return $content;
    }

}
