<?php
$datestring = date('YmdHis');
$file_name = "co2_csv" . $datestring . ".csv";


// 一応ドメイン外からのコールはキック
$referer = $_SERVER['HTTP_REFERER'];
$url = parse_url($referer);
$host = $url['host'];


$originhost = $_SERVER['HTTP_HOST'];
if ($host != $originhost) {
  echo $host . " unmatch origin host:" . $originhost;
  exit;
}

var_dump($_SERVER);
echo $_SERVER['HTTP_REFERER'];
echo $referer;
echo "host";
echo $host;

//  ["HTTP_REFERER"]=>
//  string(46) "https://kuippa.com/co2/sandbox/test_csvdl.html"
// ["HTTP_SEC_FETCH_SITE"]=>
// string(11) "same-origin"
// "cross-site", "same-origin", "same-site", and "none

// header('Content-Type: application/octet-stream');
// header("Content-Disposition: attachment; filename={$file_name}");
// header('Content-Transfer-Encoding: binary');
//

// ファイルサイズ上限設定
// echo ini_get ("post_max_size"); // 8M

echo($_POST['csvdata']);

// array(48) {
//   ["REDIRECT_STATUS"]=>
//   string(3) "200"
//   ["PHPRC"]=>
//   string(21) "/home/hagurachaya/www"
//   ["PATH"]=>
//   string(28) "/usr/local/bin:/usr/bin:/bin"
//   ["HTTPS"]=>
//   string(2) "on"
//   ["HTTP_HOST"]=>
//   string(10) "kuippa.com"
//   ["HTTP_X_REAL_IP"]=>
//   string(14) "153.214.70.138"
//   ["HTTP_X_SAKURA_FORWARDED_FOR"]=>
//   string(14) "153.214.70.138"
//   ["HTTP_LISTEN_IPADDR"]=>
//   string(14) "49.212.198.177"
//   ["CONTENT_LENGTH"]=>
//   string(2) "39"
//   ["HTTP_CACHE_CONTROL"]=>
//   string(9) "max-age=0"
//   ["HTTP_UPGRADE_INSECURE_REQUESTS"]=>
//   string(1) "1"
//   ["HTTP_ORIGIN"]=>
//   string(18) "https://kuippa.com"
//   ["CONTENT_TYPE"]=>
//   string(33) "application/x-www-form-urlencoded"
//   ["HTTP_USER_AGENT"]=>
//   string(139) "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1"
//   ["HTTP_ACCEPT"]=>
//   string(135) "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"
//   ["HTTP_SEC_FETCH_SITE"]=>
//   string(11) "same-origin"
//   ["HTTP_SEC_FETCH_MODE"]=>
//   string(8) "navigate"
//   ["HTTP_SEC_FETCH_USER"]=>
//   string(2) "?1"
//   ["HTTP_SEC_FETCH_DEST"]=>
//   string(8) "document"
//   ["HTTP_REFERER"]=>
//   string(46) "https://kuippa.com/co2/sandbox/test_csvdl.html"
//   ["HTTP_ACCEPT_ENCODING"]=>
//   string(17) "gzip, deflate, br"
//   ["HTTP_ACCEPT_LANGUAGE"]=>
//   string(23) "ja,en-US;q=0.9,en;q=0.8"
//   ["HTTP_COOKIE"]=>
//   string(248) "__utmz=76712207.1584976377.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __gads=ID=d6fea30b9cb16b40-226f2fba2ac4001e:T=1603034342:RT=1603034342:S=ALNI_MZVlNAzWLUbZiiedLbe1a5v5IztwQ; __utma=76712207.516440540.1584976377.1615305822.1615481595.21"
//   ["SERVER_SIGNATURE"]=>
//   string(0) ""
//   ["SERVER_SOFTWARE"]=>
//   string(6) "Apache"
//   ["SERVER_NAME"]=>
//   string(10) "kuippa.com"
//   ["SERVER_ADDR"]=>
//   string(11) "100.64.0.37"
//   ["SERVER_PORT"]=>
//   string(3) "443"
//   ["REMOTE_HOST"]=>
//   string(41) "p7138-ipngn8001marunouchi.tokyo.ocn.ne.jp"
//   ["REMOTE_ADDR"]=>
//   string(14) "153.214.70.138"
//   ["DOCUMENT_ROOT"]=>
//   string(22) "/home/hagurachaya/www/"
//   ["REQUEST_SCHEME"]=>
//   string(5) "https"
//   ["CONTEXT_PREFIX"]=>
//   string(0) ""
//   ["CONTEXT_DOCUMENT_ROOT"]=>
//   string(22) "/home/hagurachaya/www/"
//   ["SERVER_ADMIN"]=>
//   string(18) "[no address given]"
//   ["SCRIPT_FILENAME"]=>
//   string(48) "/home/hagurachaya/www/co2/sandbox/test_csvdl.php"
//   ["REMOTE_PORT"]=>
//   string(5) "11446"
//   ["GATEWAY_INTERFACE"]=>
//   string(7) "CGI/1.1"
//   ["SERVER_PROTOCOL"]=>
//   string(8) "HTTP/1.1"
//   ["REQUEST_METHOD"]=>
//   string(4) "POST"
//   ["QUERY_STRING"]=>
//   string(0) ""
//   ["REQUEST_URI"]=>
//   string(27) "/co2/sandbox/test_csvdl.php"
//   ["SCRIPT_NAME"]=>
//   string(27) "/co2/sandbox/test_csvdl.php"
//   ["PHP_SELF"]=>
//   string(27) "/co2/sandbox/test_csvdl.php"
//   ["REQUEST_TIME_FLOAT"]=>
//   float(1616334389.1982)
//   ["REQUEST_TIME"]=>
//   int(1616334389)
//   ["argv"]=>
//   array(0) {
//   }
//   ["argc"]=>
//   int(0)
// }
