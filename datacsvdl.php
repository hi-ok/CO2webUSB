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

if (!isset($_POST['timedata'])
|| !isset($_POST['co2data'])
|| !isset($_POST['humdata'])
|| !isset($_POST['tmpdata'])
|| !isset($_POST['botblocker'])
) {
  echo "can't get data";
  exit;
}

// $csvdata = isset($_POST['csvdata']);
// 簡単なボット日本語非対応環境避け
if ($_POST['botblocker'] != "にばいと文字圏自然バリア") {
  echo $_POST['botblocker'];
  exit;
}

// ダウンロード
header('Content-Type: application/octet-stream');
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename={$file_name}");
header('Content-Transfer-Encoding: binary');

$aryTime = preg_split("/\r\n/", $_POST['timedata']);
$aryCO2 = preg_split("/\r\n/", $_POST['co2data']);
$aryHum = preg_split("/\r\n/", $_POST['humdata']);
$aryTmp = preg_split("/\r\n/", $_POST['tmpdata']);

for($i = 0; $i < count($aryTime); $i++) {
    echo
      $aryTime[$i] . ","
      . $aryCO2[$i] . ","
      . $aryHum[$i] . ","
      . $aryTmp[$i] . PHP_EOL;
}


// function setval() {
//   $tmp = "";
//   isset($_POST['timedata'];
//   if ()
//
//   return $tmp
// }

// echo($_POST['csvdata']);
