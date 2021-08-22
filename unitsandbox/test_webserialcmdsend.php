<html>
<title>webusb.html</title>
<head>

</head>
<body>
    <div id="app">
     </div>


<button id="arduinoButton">Talk to CO2 senser</button>

<button id="btnCMD">SEND CMD</button>


<!--

<input id="listCmd" type="text" list="cmdlist">
<datalist id="cmdlist">
	<option value="選択肢１"></option>
	<option value="選択肢２"></option>
	<option value="選択肢３"></option>
</datalist>
 -->
<select id="listCmd" size="10">
    <option value="STA">STA</option>
    <option value="ID?">ID?</option>
    <option value="STP">STP</option>
    <option value="FRC?">FRC?</option>
    <option value="WIFI?">WIFI?</option>
    <option value="AMBI?">AMBI?</option>
    <option value="WFSW?">WFSW?</option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>
    <option value=""></option>


</select>
<!--
※1 コマンドやリターンの後には、CRLFが付きます。
※2 nは数値、xは文字列

コマンド	L/P	リターン
	L:Lite版
	P:Pro版
【Lite/Pro共通】

ID?	… IDを問合せします
	L	OK ID=SCD30(CO2)
	P	OK ID=CO2_OLED_WIFI

VER?	… ファームバージョンを問合せします
	L	OK VER=1.1
	P	OK VER=1.0

STA	… データ送信を開始します
	L/P	OK STA

	データフォーマット		→	CO2=n,HUM=n.n,TMP=n.n
				（HUMは湿度、TMPは温度）

STP	… データ送信を停止します
	L/P	OK STP

FRC?	… SCD30のフォースキャリブレーション値を取得します
	L/P	FRC=nnnn	… SCD30のFRC値

FRC=nnnn	… SCD30のフォースキャリブレーション値を設定します（nnnn：400～2000）
	L/P	OK FRC	… 設定出来ました
		NG FRC >>	… 設定出来ませんでした
		NG FRC	… 設定値範囲外

【Proのみ】

WFINV=n	… WiFi接続の間隔（WFSW=1の時に有効。n：60～3600秒）
	P	OK WFINV

WIFI?	… WiFi設定値を取得します
	P	WIFI.SSID=xxxxx		… WiFiのSSID
		WIFI.PSWD=xxxxx		… WiFiのパスワード

SSID=x	… SSIDを設定します（最大32バイト）
	P	OK SSID

PWSD=x	… パスワードを設定します（最大64バイト）
	P	OK PWSD

AMINV=n	… Ambient又はcgiへ送信する間隔（WFSW=2の時に有効。n：10～3600秒）
	P	OK AMINV

AMBI?	… Ambientの設定値を取得します
	P	AMBI.CHID=nnnnn		… Ambientのチャンネル番号
		AMBI.WTKEY=xxxxx		… AmbientのWrite Key

CHID=n	… Ambientのチャンネル番号（cgiも同じ番号を使用）
	P	OK CHID

WTKEY=x	… AmbientのWrite Keyを設定します（最大20バイト）
	P	OK  WTKEY

WFSW?	… WiFi動作モードを取得する
	P	WFSW=n	n=0　WiFi接続無し
			n=1　一定間隔で接続・切断を繰り返す
			n=2　常時WiFi接続する

WFSW=n	… WiFi動作モードを設定する（n：上記参照）
	P	OK  WFSW

CGPORT?	… cgiで使用するポート番号を取得する
	P	CGPORT=ｎ		… TCP/IPのポート番号

CGPORT=ｎ	… cgiで使用するポート番号を設定する（n：0～65535）
	P	OK CGIPORT

CGAP?	… cgiのサーバー設定値を取得する
	P	CGAP.SERVER=xxxxx		… cgiサーバー名
		CGAP.APLI=xxxxx		… cgiアプリ名

SERVER=x	… cgiのサーバー名を設定する（最大24バイト）
	P	OK SERVER

APLI=x	… cgiのアプリ名を設定する（最大24バイト）
	P	OK APLI

SAVE	… 設定値をEEPROMへ保存する
	P	OK SAVE

WFRST	… ESP32のWiFi接続を再起動する
	P	OK WFRST

	※WiFiの動作モードにより再起動状態は変わります

RECON	… ESP32のWiFi接続情報を書き換えて再起動（再接続）する
	P	OK RECON

	※電源ON時にESP32が自動で接続を試みるため、接続情報を書き換えて再接続する機能
 -->

<div id="target"></div>
<iframe allowpaymentrequest allow="usb; fullscreen"></iframe>




<script>
async function cmd_send() {
  try {
    document.getElementById('target').innerHTML = "ss";
    const num = document.getElementById('listCmd').selectedIndex;
    const cmd = document.getElementById('listCmd').value;
    document.getElementById('target').innerHTML = cmd;
    console.log("cmd_set");

    const encoder = new TextEncoderStream();
    const writableStreamClosed = encoder.readable.pipeTo(_port.writable);
    const writer = encoder.writable.getWriter();
    writer.write(cmd + '\r\n');
    writer.write('\r\n');
    writer.close();
    await writableStreamClosed;

    console.log("cmd_send");
  } catch (error) {
    console.log("!!!! "+error);
  }
}

document.getElementById('btnCMD').addEventListener('click',cmd_send);


document.getElementById('arduinoButton').addEventListener('click', function () {
  if (navigator.serial) {
    talkToArduino();
  } else {
    alert('Web Serial API not supported.');
  }
});


var _port;

async function talkToArduino() {
  try {
    const port = await navigator.serial.requestPort();
console.log("requestPort");
    console.log(port.getInfo());
console.log("getInfo");
    await port.open({ baudRate: 9600 });
console.log("open");
    const decoder = new TextDecoderStream();
    port.readable.pipeTo(decoder.writable);
console.log("decoder");
    const inputStream = decoder.readable;
    const reader = inputStream.getReader();
console.log("reader");
//     const encoder = new TextEncoderStream();
//     const writableStreamClosed = encoder.readable.pipeTo(port.writable);
//     const writer = encoder.writable.getWriter();
//     writer.write("STA" + '\r\n');
//     writer.write('\r\n');
//     writer.close();
//     await writableStreamClosed;
// console.log("reader");

    _port = port;

    while (true) {
      const { value, done } = await reader.read();
      if (value) {
        console.log(value + '\n');

        // log.textContent += value + '\n';
      }
      if (done) {
        console.log('[readLoop] DONE', done);
        reader.releaseLock();
        break;
      }
    }
  } catch (error) {
    console.log("!!!! "+error);
  }
}


var nvUA = navigator.userAgent;
console.log(nvUA);
</script>

</body>
