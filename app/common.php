<?php
// 应用公共文件
function show($code,$message,$data=''){
    $result  =[
        'code'=>$code,
        'message'=>$message,
        'data'=>$data,
    ];
    return $result;
}
