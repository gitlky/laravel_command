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

    private $file_name, $ctrl_namespace, $model_name, $model_path, $old_path;

    protected $signature = 'yu:ctrl {path : input where you wanna make controller}';
    protected $description = 'make controller for your project';

    public function handle()
    {
        $path = $this->argument('path');
        $this->old_path = str_replace("\\", "/", $path);
        $this->model_name = $this->ask("what's your model name");
        $this->model_path = app_path(config('db.model_path') . '/' . $this->model_name . '.php');
//        if(!File::exists($model_path)){
//            $this->error("Model does not exist");
//            return;
//        }
        $ctrl_path = app_path('Http/Controllers/' . $path);
        $ctrl_path = str_replace("\\", "/", $ctrl_path);

        if (!File::exists($ctrl_path)) {
            $this->mk_dir_write($ctrl_path);
        }

    }

    public function mk_dir_write($path)
    {
        $old_path = $path;
        $app_path = str_replace("\\", "/", app_path('Http/Controllers/'));
        $path = str_replace($app_path, "", $path);
        $ctrl_path = str_replace("\\", "/", $path);
        $set_up = explode("/", $ctrl_path);
        $this->file_name = $set_up[count($set_up) - 1];
        unset($set_up[count($set_up) - 1]);
        $path = "";
        foreach ($set_up as $key=>$dir) {
            if (empty($dir)) {
                continue;
            }
            $path .= $dir . '/';
            if($key<1){
                $path1 = app_path('Http/Controllers/');
                $path1 = str_replace("\\", "/", $path1);
                $path = $path1.$path;
            }
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, $mode = 0777);
            }
        }
        $this->ctrl_namespace = "App\Http\Controllers\\" .str_replace(str_replace("/", "\\",app_path('Http/Controllers/')),"", str_replace("/", "\\", substr($path, 0, strlen($path) - 1)));
        $old_path = count(explode('.php', $old_path)) > 1 ? $old_path : $old_path . '.php';
        $this->line('make file successful:' . $this->ctrl_namespace );
        if (!File::exists($old_path)) {
            File::put($old_path, $this->ctrl_temp());
        } else {
            $this->error('file is exist');
        }
        #File::delete($old_path);
        return true;
    }

    private function ctrl_temp()
    {

        $use = "use " . $this->yu_cfg('db.model_path') . '\\' . $this->model_name . ';
        ';
        $use .= "use Illuminate\Http\Request;";
        if (strlen($this->yu_cfg('ctrl.parent_controller_name_space')) > 0) {
            $use .= "
        use " . $this->yu_cfg('ctrl.parent_controller_name_space') . ";
        ";
        }
        $str = "<?php
namespace $this->ctrl_namespace;
$use
class $this->file_name extends " . $this->yu_cfg('ctrl.parent_controller') . "{
    const view_path = '" . str_replace("/", ".", $this->old_path) . "';
    
    
    //列表
    public function lists($this->model_name \$$this->model_name)
    {
        \$val = \$$this->model_name->paginate(self::page);
        \$pam = array(
            'data' => \$val
        );
        return \$this->see_view(self::view . 'List', \$pam);
    }
    
    
    //详情
    public function edit(Request \$req, $this->model_name \$$this->model_name)
    {
        \$id = \$req->id;
        \$data = [];
        if (\$id) {
            \$data = $$this->model_name->find(\$id)->toArray();
        }
        \$pam = array(
            'old_data' => \$data,
            'old_id' => \$id,
        );
        return \$this->see_view(self::view . 'Edit', \$pam);
    }
    
    //插入
   public function doedit(Request \$req, $this->model_name \$$this->model_name)
    {
        \$data = \$req->all();
        \$$this->model_name->updateOrCreate([
            'id' => \$req->id,
        ], \$data);
        return redirect()->route('".str_replace("/","_",$this->old_path)."_list');
    }
    
    //删除
       public function del(Request \$req, $this->model_name \$$this->model_name)
    {
        \$$this->model_name->find(\$req->id)->delete();
        return redirect()->route('".str_replace("/","_",$this->old_path)."_list');
    }
    
}
";
        return $str;
    }
}
