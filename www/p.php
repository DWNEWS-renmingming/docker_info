<?php
// PHP类库：获取内容中的图片地址
function get_pic_url($content){
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";//正则
    preg_match_all($pattern,$content,$match);//匹配图片
    return $match[1];//返回所有图片的路径
}
// 即使使用代理服务器，也能获取用户真实ip
function getIp()
{
    $unknown = 'unknown';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }
 
    return $ip;
}
echo getIp();
die;
// 数据打印
if(!function_exists('p')){
    function p($data) {
        /**
         * 格式化打印数据
         * @param $data  array/string 需要打印的数据
         */
        header("Content-type:text/html;charset=utf-8");
        // 定义样式
        $str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data = $data ? 'true' : 'false';
        } elseif (is_null($data)) {
            $show_data = 'null';
        } else {
            $show_data = print_r($data, true);
        }
        $str .= $show_data;
        $str .= '</pre>';
        echo $str;
    }
}
$a = [
    '张三',
    '李四',
    '王五',
    '马六',
];
p($a);