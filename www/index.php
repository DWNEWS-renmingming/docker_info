<?php
phpinfo();die;
class I {

    public $redis;
    public $pdo;
    private $config = [
        'redis_host'     => '120.78.199.238',
        'redis_port'     => '6379',
        'redis_auth'     => '',
        'mysql_host'     => "mysql:host=120.78.199.238;port=3306;dbname=test",
        'mysql_user'     => "rmm",
        'mysql_password' => "rm001",
    ];

    public function __construct() {
        date_default_timezone_set('PRC');
        $this->redis = new \Redis();
        $this->redis->connect($this->config['redis_host'], $this->config['redis_port']);
        $this->redis->auth($this->config['redis_auth']);

        $this->pdo       = new \PDO($this->config['mysql_host'], $this->config['mysql_user'], $this->config['mysql_password']);

    }
    
    public function __call($method, $param) {   
        if(method_exists($this, $method)) {
            return call_user_func_array( [$this, $method] , $param);
        } else {
            exit('method[' . $method . '] not exists');
        }
    }
    public static $instance = [];

    public static function getInstance() {
        $class_name = get_called_class();
        if(!isset(self::$instance[$class_name])) {
            self::$instance[$class_name] = new $class_name();
        }
        return self::$instance[$class_name];
    }

    public function demo() {
        $redis_name = 'dwk_rmm';
        $redis_data = 'home_live';
        // $this->redis->set($redis_name, $redis_data);
        // $info =  $this->redis->get($redis_name);
        // $info =  self::selectDemoTable();
        $info =  self::selectCategoriesTable();
        $categories = $result =  [];
        foreach ($info as $category) {
            $categories[$category['parentCategory']][] = $category;
        }
        $result =  self::showCategoryTree($categories, 0);
        return $result;
    }

    public function showCategoryTree($categories, $n)
    {
        if (isset($categories[$n])) {
            foreach ($categories[$n] as $category) {
                echo str_repeat('-', $n) . $category['categoryName'] . PHP_EOL;
                self::showCategoryTree($categories, $category['id']);
            }
        }
        return;
    }

     /**
     *  个人信息查询
     */
    public function selectDemoTable( ) {
        $this->pdo->query("SET NAMES utf8");
        $sql  = "SELECT * FROM demo";
        $rs = $this->pdo->query($sql);
        $rs->setFetchMode(\PDO::FETCH_ASSOC);
        $dbData = $rs->fetchAll();
        $return_data = [];
        if($dbData) {
            $return_data  =  $dbData;
        }
        return $return_data;
    }

     /**
     *  分类
     */
    public function selectCategoriesTable( ) {
        $this->pdo->query("SET NAMES utf8");
        $sql  = "SELECT * FROM categories";
        $rs = $this->pdo->query($sql);
        $rs->setFetchMode(\PDO::FETCH_ASSOC);
        $dbData = $rs->fetchAll();
        $return_data = [];
        if($dbData) {
            $return_data  =  $dbData;
        }
        return $return_data;
    }
   

   

    public function __destruct() {
        $this->redis->close();
        $this->pdo = null;
    }
}
echo '<pre>';
$i =  I::getInstance()->demo();
echo '<pre>';
print_r($i);die;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
</head>
<body> 
    <center>
    <table border="1">
       <thead>
           <tr>
                <th><input type="checkbox" class="allcheck othercheck0" onclick="ckAll(0)"></th>
                <th>Id</th>
                <th>Name</th>
                <th>操作</th>
           </tr>
       </thead>
       <tbody>
            <?php foreach ($i as $key => $v): ?>
                <tr>
                <td><input type="checkbox" value="<?php echo $v['id'] ?>" class="indexche0 indexche"></td>
                    <td><?php echo $v['id']??'' ?></td>
                    <td><?php echo $v['name']??'' ?></td>
                    <td><a href="javascript:;">编辑</a>|<a href="javascript:;">删除</a></td>
                <tr>
            <?php endforeach; ?>
            <button type="button"   onclick="javascript:delAllProduct();">点击</button>
            <input type="hidden"  value="" id="deleteid">
       </tbody>
    </table>
    </center>
</body>
</html>
<script src="https://mydata.eovobo.com/bigdata/conclusion/1/js/jquery.js"></script>
<script>
    // 全选全不选
    function ckAll(d){
        $flag = $('.allcheck').is(":checked");
        var checked=document.getElementsByClassName('indexche'+d);//获取div下的input
        for(i=0;i<checked.length;i++){
            checked[i].checked=$flag;
        } 
    }
    // 批量删除
    function delAllProduct(){
        alert(111);
        var strn = ''; 
        $("input.indexche").each(function(){
            if($(this).is(":checked")){
                var data   = $(this).val();
                if(data!=''){
                    strn = strn+','+data;
                }
                $(this).parent().parent().remove();
            }
            })
        if(strn){
            $('#deleteid').val(strn.substr(1));
        }
        var newv = $('#deleteid').val();
        alert(strn)

    }
    //删除发送ajax
    function del_ajax(newv)
    {
        $.ajax({
            url:base_url+"admin/regional_info/deleteAll",
            data:{newv:newv},
            type:'POST',
            dataType:'json',
            beforeSend: function(){
                index = layer.load(); // 加载层
            },
            success:function(data){
              if(data.code == 'success')
              {
                layer.alert(data.msg, function(){
                    document.location.reload();
                });
              }else{
                layer.alert(data.msg);
              }
              // 关闭
              layer.close(index);  
            } 
        })
    }
</script>