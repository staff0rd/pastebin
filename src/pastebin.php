<?php

require_once 'vendor/autoload.php';

$paste_cmd = new Commando\Command();

$paste_cmd->option('k')
    ->require()
    ->aka("key")
    ->describedAs('Your api developer key');

$paste_cmd->option('f')
    ->aka('file')
    ->describedAs('Paste the contents of a file')
    ->file();

$paste_cmd->option('p')
    ->aka('public')
    ->default(true)
    ->describedAs('Make this paste public.  Default is unlisted.')
    ->boolean();

$paste_cmd->option('n')
    ->aka('name')
    ->describedAs('Name or title of your paste');

$paste_cmd->option()
    ->aka('content')
    ->describedAs('Content to to paste');

$paste_cmd->option('u')
    ->aka('username')
    ->describedAs('Your pastebin user name');

$paste_cmd->option('p')
    ->aka('password')
    ->describedAs('Your pastebin password');

if (!empty($paste_cmd["file"]) && !empty($paste_cmd["content"])) {
    $paste_cmd->error(new Exception("Cannot specify both file and content"));
    exit(1);
}
if (empty($paste_cmd["file"]) && empty($paste_cmd["content"])) {
    if (!empty($paste_cmd["username"]) && !empty($paste_cmd["password"])) {
        echo get_user_key($paste_cmd["key"], $paste_cmd["username"], $paste_cmd["password"]);
        exit(0);
    } else {
        $paste_cmd->error(new Exception("Must specify either file and content"));
        exit(1);
    }
}

$contents = $paste_cmd["content"];

if (!empty($paste_cmd["file"])) {
    $contents = file_get_contents($paste_cmd["file"]);
}

function get_user_key($api_dev_key, $api_user_name, $api_user_password) {
    $api_user_name 		= urlencode($api_user_name);
    $api_user_password 	= urlencode($api_user_password);
    $url			= 'https://pastebin.com/api/api_login.php';
    $ch			= curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_dev_key='.$api_dev_key.'&api_user_name='.$api_user_name.'&api_user_password='.$api_user_password.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 0);

    $response 		= curl_exec($ch);
    return $response;
}



$api_dev_key 			= $paste_cmd['key']; // your api_developer_key
$api_paste_private 		= $paste_cmd['public'] ? '0' : '1'; // 0=public 1=unlisted 2=private
$api_paste_name			= $paste_cmd['name']; // name or title of your paste
$api_paste_expire_date 		= 'N';
$api_paste_format 		= 'text';
// $api_user_key 			= ''; // if an invalid or expired api_user_key is used, an error will spawn. If no api_user_key is used, a guest paste will be created
$api_paste_name			= urlencode($api_paste_name);
$api_paste_code			= urlencode($contents);

$url 				= 'https://pastebin.com/api/api_post.php';
$ch 				= curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1); 
curl_setopt($ch, CURLOPT_NOBODY, 0);
$response  			= curl_exec($ch);
echo "The response is\n";
echo $response;
