<?php

$content = "";

if(FALSE !== ftell(STDIN))
{
    while (FALSE !== ($line = fgets(STDIN)))
    {
        $content .= $line;
    }
}

require_once 'vendor/autoload.php';

$paste_cmd = new Commando\Command();

$paste_cmd->setHelp("Examples:

docker run -it staff0rd/pastebin -k <devKey> \"paste this text to pastebin!\"
    Paste the given text to pastebin

cat myfile.log | docker run -i staff0rd/pastebin -k <devKey>
    Paste the contents of myfile.log to pastebin

docker run -it staff0rd/pastebin -k <devKey> -u <userName> --password <password>
    Retrieve a userKey to use associate pastes with your user
");

$paste_cmd->option('k')
    ->require()
    ->aka("devkey")
    ->describedAs('Your api developer key');

$paste_cmd->option('j')
    ->aka("userkey")
    ->describedAs("Your user key.  Without this all pastes will be by guest");

$paste_cmd->option('p')
    ->aka('public')
    ->default(true)
    ->describedAs('Make this paste public.  Default is unlisted.')
    ->boolean();

$paste_cmd->option('t')
    ->aka('title')
    ->describedAs('Name or title of your paste');

$paste_cmd->option()
    ->aka('content')
    ->default($content)
    ->describedAs('Content to to paste');

$paste_cmd->option('u')
    ->aka('username')
    ->describedAs('Your pastebin user name.  Only used to a get a userkey.');

$paste_cmd->option('password')
    ->describedAs('Your pastebin password.  Only used to a get a userkey.');

if (empty($paste_cmd["content"])) {
    if (!empty($paste_cmd["username"]) && !empty($paste_cmd["password"])) {
        echo "Getting user key...\n";
        $response = get_user_key($paste_cmd["devkey"], $paste_cmd["username"], $paste_cmd["password"]);
        echo "User-key: " . $response . "\n";
        exit(0);
    } else {
        $paste_cmd->error(new Exception("Must specify content to paste"));
        exit(1);
    }
}

$contents = $paste_cmd["content"];

function get_user_key($api_dev_key, $api_user_name, $api_user_password) {
    $api_user_name 		= urlencode($api_user_name);
    $api_user_password 	= urlencode($api_user_password);
    $url			= 'https://pastebin.com/api/api_login.php';
    $ch			= curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_dev_key='.$api_dev_key.'&api_user_name='.$api_user_name.'&api_user_password='.$api_user_password.'');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 0);

    $response 		= curl_exec($ch);
    return $response;
}

$api_dev_key 			= $paste_cmd['devkey']; // your api_developer_key
$api_paste_private 		= $paste_cmd['public'] ? '0' : '1'; // 0=public 1=unlisted 2=private
$api_paste_name			= $paste_cmd['title']; // name or title of your paste
$api_paste_expire_date 		= 'N';
$api_paste_format 		= 'text';
$api_user_key 			= $paste_cmd["userkey"];
$api_paste_name			= urlencode($api_paste_name);
$api_paste_code			= urlencode($contents);

$url 				= 'https://pastebin.com/api/api_post.php';
$ch 				= curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_VERBOSE, 1); 
curl_setopt($ch, CURLOPT_NOBODY, 0);
$response  			= curl_exec($ch);
echo $response . "\n";
