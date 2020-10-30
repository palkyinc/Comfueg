<?php
echo phpinfo();

/*$indicesServer = array( 'PHP_SELF' => $_SERVER['PHP_SELF'],
//'argv' =>$_SERVER['argv'],
//'argc' =>$_SERVER['argc'],
'GATEWAY_INTERFACE'=>$_SERVER['GATEWAY_INTERFACE'],
//'SERVER_ADDR'=>$_SERVER['SERVER_ADDR'],
'SERVER_NAME'=>$_SERVER['SERVER_NAME'],'SERVER_SOFTWARE'=>
$_SERVER['SERVER_SOFTWARE'],'SERVER_PROTOCOL'=>
$_SERVER['SERVER_PROTOCOL'],'REQUEST_METHOD'=>
$_SERVER['REQUEST_METHOD'],'REQUEST_TIME'=>
$_SERVER['REQUEST_TIME'],'REQUEST_TIME_FLOAT'=>
$_SERVER['REQUEST_TIME_FLOAT'],'QUERY_STRING'=>
$_SERVER['QUERY_STRING'],'DOCUMENT_ROOT'=>
$_SERVER['DOCUMENT_ROOT'],'HTTP_ACCEPT'=>
$_SERVER['HTTP_ACCEPT'],
//'HTTP_ACCEPT_CHARSET'=>$_SERVER['HTTP_ACCEPT_CHARSET'],
'HTTP_ACCEPT_ENCODING'=>$_SERVER['HTTP_ACCEPT_ENCODING'],'HTTP_ACCEPT_LANGUAGE'=>
$_SERVER['HTTP_ACCEPT_LANGUAGE'],'HTTP_CONNECTION'=>
$_SERVER['HTTP_CONNECTION'],'HTTP_HOST'=>
$_SERVER['HTTP_HOST'],
//'HTTP_REFERER'=>$_SERVER['HTTP_REFERER'],
'HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT'],'HTTPS'=>
$_SERVER['HTTPS'],'REMOTE_ADDR'=>
$_SERVER['REMOTE_ADDR'],'REMOTE_HOST'=>
$_SERVER['REMOTE_HOST'],'REMOTE_PORT'=>
$_SERVER['REMOTE_PORT'],'REMOTE_USER'=>
$_SERVER['REMOTE_USER'],
//'REDIRECT_REMOTE_USER'=>$_SERVER['REDIRECT_REMOTE_USER'],
'SCRIPT_FILENAME'=>$_SERVER['SCRIPT_FILENAME'],
//'SERVER_ADMIN'=>$_SERVER['SERVER_ADMIN'],
'SERVER_PORT'=>$_SERVER['SERVER_PORT'],
//'SERVER_SIGNATURE'=>$_SERVER['SERVER_SIGNATURE'],
'PATH_TRANSLATED'=>$_SERVER['PATH_TRANSLATED'],'SCRIPT_NAME'=>
$_SERVER['SCRIPT_NAME'],'REQUEST_URI'=>
$_SERVER['REQUEST_URI'],
//'PHP_AUTH_DIGEST'=>$_SERVER['PHP_AUTH_DIGEST'],
//'PHP_AUTH_USER'=>$_SERVER['PHP_AUTH_USER'],
//'PHP_AUTH_PW'=>$_SERVER['PHP_AUTH_PW'],
'AUTH_TYPE'=>$_SERVER['AUTH_TYPE'],
//'PATH_INFO'=>$_SERVER['PATH_INFO'],
'ORIG_PATH_INFO'=>$_SERVER['ORIG_PATH_INFO']);

header("Content-Type:application/json");

$data = file_get_contents('php://input');
$data = json_decode($data, false); //parse_str($indicesServer['QUERY_STRING'])
$indicesServer [1] = $data->where;
echo json_encode($indicesServer);

/*delivery_response(200,$indicesServer['REQUEST_METHOD'],$dataSalida);
function delivery_response($status,$status_message,$data)
{

   header("HTTP/1.1 $status $status_message");
   $response['status']=$status;
   $response['status_message']=$status_message;
   $response['data']=$data;

   $json_response=json_encode($response);
   echo $json_response;
}*/