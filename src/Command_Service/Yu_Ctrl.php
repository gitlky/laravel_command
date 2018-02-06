<?php
/**
 * Created by IntelliJ IDEA.
 * User: yu
 * Date: 2017/12/19
 * Time: 下午11:44
 */

namespace lky_vendor\laravel_command\Command_Service;
use Carbon\Carbon;
use File;
use Illuminate\Support\Str;
class Yu_Ctrl extends Yu
{

    private $file_name, $ctrl_namespace, $model_name, $model_path, $old_path;

    protected $signature = 'yu:ctrl {path : input where you wanna make controller}';
    protected $description = 'make controller for your project';

    public function handle()
    {
        $path = $this->argument('path');
        $this->old_path = str_replace("\\", "/", $path);
        $this->blade();
        #return;
        $this->model_name = $this->ask("what's your model name");
        $this->model_path = str_replace("\\",'/',str_replace('/App','',app_path($this->yu_cfg('db.model_path')) . '/' . $this->model_name . '.php'));
        if(!File::exists($this->model_path)){
            $this->error("Model does not exist");
            return;
        }
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
        $this->ctrl_namespace = "App\\Http\\Controllers\\" .str_replace(str_replace("/", "\\",app_path('Http/Controllers/')),"", str_replace("/", "\\", substr($path, 0, strlen($path) - 1)));
        $old_path = count(explode('.php', $old_path)) > 1 ? $old_path : $old_path . '.php';
        $this->line('make file successful:' . $this->ctrl_namespace );
        if (!File::exists($old_path)) {
            File::put($old_path, $this->ctrl_temp());
        } else {
            $this->error('file is exist');
        }
        $this->line('make controller is successful');
        $name_space = explode('\\',$this->ctrl_namespace);
        $name_space = $name_space[count($name_space)-1];
        $str = " 
        
    //你的路由备注
    Route::group(['namespace' => '$name_space','prefix' => '".Str::lower($this->file_name)."'], function () {
        Route::get('list', '$this->file_name@lists')->name('".$this->file_name."_list');
        Route::get('edit', '$this->file_name@edit')->name('".$this->file_name."_edit');
        Route::post('sub_edit', '$this->file_name@sub_edit')->name('".$this->file_name."_sub_edit');
        Route::any('del', '$this->file_name@del')->name('".$this->file_name."_del');
        Route::any('batch_del', '$this->file_name@batch_del')->name('".$this->file_name."_batch_del');
    });
    
    ";

        echo $str;
        #File::delete($old_path);
        return true;
    }

    private function ctrl_temp()
    {
        $carbon = Carbon::now();
        $date = $carbon->toDateString();
        $time = $carbon->toTimeString();
        $use = "use " . $this->yu_cfg('db.model_path') . '\\' . $this->model_name . ';
        ';
        $use .= "use Illuminate\\Http\\Request;";
        if (strlen($this->yu_cfg('ctrl.parent_controller_name_space')) > 0) {
            $use .= "
        use " . $this->yu_cfg('ctrl.parent_controller_name_space') . ";
        ";
        }
        $str = "<?php
        namespace $this->ctrl_namespace;
        
        /**
 * Created by lky_command.
 * User: Yu
 * Date: $date
 * Time: $time
 */

$use
class $this->file_name extends " . $this->yu_cfg('ctrl.parent_controller') . "{
    const view_path = '" . str_replace("/", ".", $this->old_path) . ".';
    
    
    //列表
    public function lists($this->model_name \$$this->model_name)
    {
        \$val = \$$this->model_name->paginate(self::page);
        \$kws = \$req->kws;
        
        \$data = \$dangjian_carousel_img
           ->where('name', 'like', '%' . \$kws . '%')
           ->corp_data()
           ->paginate(\$this->page_size());
        \$pam = array(
            'data' => \$data,
            'kws' => \$kws
        );
        return \$this->see_view(self::view . 'List', \$pam); 
    }
    
    
    //详情
    public function edit(Request \$req, $this->model_name \$$this->model_name)
    {
        \$id = \$req->id;
        \$data = [];
        if (\$id) {
            \$data = \$$this->model_name->find(\$id)->toArray();
        }
        \$pam = array(
            'old_data' => \$data,
            'old_id' => \$id,
        );
        return \$this->see_view(self::view . '_Edit', \$pam);
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
        \$$this->model_name->destroy(\$req->id);
        return redirect()->route('".str_replace("/","_",$this->old_path)."_list');
    }
    //批量删除
     public function batch_del(Request \$req, $this->model_name \$$this->model_name)
    {
        \$ids = json_decode(\$req->id);
        if(count(\$ids)>0){
            foreach (\$ids as \$id){
               \$$this->model_name->destroy(\$id);
            }
        }
       return redirect()->route('".str_replace("/","_",$this->old_path)."_list');
    }
    
}
";
        return $str;
    }


    private function blade(){
        $view_path = resource_path('views/');
        $view_path = str_replace("\\","/",$view_path);
        $set_up = explode("/", $this->old_path);
        $file_name = $set_up[count($set_up) - 1];
        unset($set_up[count($set_up) - 1]);
        $path="";
        foreach ($set_up as $key=>$dir) {
            if (empty($dir)) {
                continue;
            }
            if($this->yu_cfg('ctrl.str2lower')){
                $dir = Str::lower($dir);
            }
            $path .= $dir . '/';
            if($key<1){
                $path = $view_path.$path;
            }
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, $mode = 0777);
            }
        }
        $path = dirname(dirname(__FILE__)).'/temp/';
        if (!File::exists($path.$file_name.'_list.blade.php')) {
            $add = $path . 'add.yu';
            $content = File::get($add);

            $search = $this->file_name."_list";
            $add = $this->file_name."_edit";
            $batch = $this->file_name."_batch_del";
            $del = $this->file_name."_del";

            $th = "";

            $content = str_replace('$$search',$search,$content);
            $content = str_replace('$$add',$add,$content);
            $content = str_replace('$$batch',$batch,$content);
            $content = str_replace('$$del',$del,$content);
            $content = str_replace('$$th',$th,$content);

            File::put($path.$file_name.'_list.blade.php',$content);
        }

        if (!File::exists($path.$file_name.'_Edit.blade.php')) {
            $edit = $path . 'edit.yu';
            $content = File::get($edit);
            $submit = $this->file_name."_sub_edit";
            $content = str_replace('$$submit',$submit,$content);
            File::put($path.$file_name.'_Edit.blade.php',$content);
        }
    }
}
