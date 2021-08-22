
let _timer_stream_id = 0;
let _serialPort;
let _reader;
let _buffline = "";
const TIMER_STREAM = 500; // 読み込みのためのタイマーインターバル
const FILTER = [
        {usbVendorId: 0x04D8, usbProductId: 0x000A}
      ];
const BAUDRATE = 9600;
// const CHART_CNT = 500; // チャートに表示する件数
const CHART_CNT = 50000; // チャートに表示する件数 2s*
const STREAM_DATA = ["CO2=", "HUM=", "TMP="];

async function serialPortOpen() {
    try {
      _serialPort = await navigator.serial.requestPort({ filters: FILTER });
      console.log(_serialPort.getInfo());
      await _serialPort.open({ baudRate: BAUDRATE });
      // console.log("_serialPort.open");
      toggleStartStop();
      chgDisp("#chartbox");
      chgDispNone("#checklistbox");
      const decoder = new TextDecoderStream();
      // console.log(decoder);
      _serialPort.readable.pipeTo(decoder.writable);
      const inputStream = decoder.readable;
      _reader = inputStream.getReader();
      // console.log(_reader);

      // for CO2 senser pro
      const encoder = new TextEncoderStream();
      const writableStreamClosed = encoder.readable.pipeTo(_serialPort.writable);
      const writer = encoder.writable.getWriter();
      writer.write("STA" + '\r\n');
      writer.write('\r\n');
      writer.close();
      await writableStreamClosed;

      _timer_strem_id = setInterval(timerStreamLine, TIMER_STREAM);

      while (true) {
        const { value, done } = await _reader.read();
        if (value) {
          readLines(value);
        }
        if (done) {
          console.log('[readLoop] DONE', done);
          _reader.releaseLock();
          break;
        }
      }

    } catch (error) {
      $("#debugnote").html(String(error));
      console.log("catch error: " + error + '\n');
      return false;
    }
    return true;
}

async function serialPortClose() {
  try {
    if (!_serialPort) {
      console.log("serialPortClose _serialPort false" + '\n');
      return;
    }
    toggleStartStop();
    clearInterval(_timer_strem_id);
    if (_reader) {
      await _reader.cancel();
      await _reader.releaseLock();
      _reader = null;
    }
    if (_serialPort) {
      await _serialPort.writable.abort();
      await _serialPort.close();
      _serialPort = null;
    }
  } catch (error) {
    console.log("catch error: " + error + '\n');
    $("#debugnote").html(String(error));
    return false;
  }
}


function readLines(value) {
  // console.log("value     :" + value + '\n');
  _buffline += value;
  // console.log("_buffline :" + _buffline + '\n');
  var bufflines = _buffline.split("\n");
  var crlfcnt = bufflines.length;
  if (crlfcnt < 2) {
    return;
  }
  for (var i = crlfcnt-1; i >= 0 ; i--) {
    var buffcols = bufflines[i].split(",");
    var len = buffcols.length;
    if ( len == STREAM_DATA.length) {
      if (setChartData(buffcols)) {
        _buffline = "";
        break;
      }
    }
  }
}

function setChartData(buffcols) {
  try {
    // console.log("buffcols :" + buffcols + '\n');
    var len = buffcols.length;
    var aryData = new Array();
    for (var i = 0; i < len; i++) {
      var keycd = getKeyCD(buffcols[i]);
      var val = getValOfLine(buffcols[i],keycd);
      // if (keycd == STREAM_DATA[0] || keycd == STREAM_DATA[1] || keycd == STREAM_DATA[2]) {
      if (STREAM_DATA.includes(keycd)) {
        if (!isNaN(val)) {
          aryData[keycd] = val.replace(/\r?\n?/g,''); // tmpのあとに \rだけがついてくる
          // aryData[keycd] = val.replace(/\r?\n/g,'');
        }
      }
    }

    if ( aryData[STREAM_DATA[0]] == undefined
      || aryData[STREAM_DATA[1]] == undefined
      || aryData[STREAM_DATA[2]] == undefined){
      // console.log(aryData);
      return false;
    }

    if ( isNaN(aryData[STREAM_DATA[0]])
      || isNaN(aryData[STREAM_DATA[1]])
      || isNaN(aryData[STREAM_DATA[2]])
      || aryData[STREAM_DATA[1]].length < 3
      || aryData[STREAM_DATA[2]].length < 3) {
      console.log("isNaN " + aryData[STREAM_DATA[2]].length + '---- \n');
      return false;
    }

    // CHARTJS._ary_labels.push(new Date().toISOString());
    // CHARTJS._ary_labels.push(new Date().toLocaleString());
    CHARTJS._ary_labels.push(new Date().toLocaleTimeString());
    CHARTJS._ary_timedata.push(new Date().toLocaleString());
    CHARTJS._ary_co2data.push(aryData[STREAM_DATA[0]]);
    CHARTJS._ary_humdata.push(aryData[STREAM_DATA[1]]);
    CHARTJS._ary_tmpdata.push(aryData[STREAM_DATA[2]]);
    if (CHARTJS._ary_labels.length > CHART_CNT) {
      CHARTJS._ary_labels.shift();
      CHARTJS._ary_timedata.shift();
      CHARTJS._ary_co2data.shift();
      CHARTJS._ary_humdata.shift();
      CHARTJS._ary_tmpdata.shift();
    }

    console.log(
        "co2:" + aryData[STREAM_DATA[0]]
      + " hum:" + aryData[STREAM_DATA[1]]
      + " tmp:" + aryData[STREAM_DATA[2]]);
      // console.log(aryData);
    return true;
  } catch (error) {
    console.log("catch error" + error + '\n');
  }
}

function getKeyCD(buf) {
  var retkeycd = buf.substring(0,4);
  return retkeycd;
}

function getValOfLine(buf,keycd) {
  var ret = "";
  if (buf.indexOf(keycd) >= 0) {
    ret = buf.substring(buf.indexOf(keycd)+keycd.length);
  }
  return ret;
}

function timerStreamLine() {
  try {
    if (CHARTJS._ary_labels.length <= 1) {
      return;
    }
    CHARTJS._myLine.update({
        duration: 400,
        easing: 'easeOutBounce'
    })
    document.getElementById('debugnote').innerHTML = "現在の二酸化炭素濃度(ppm)<h2>"
      + CHARTJS._ary_co2data[CHARTJS._ary_co2data.length - 1] + "</h2>";

  } catch (error) {
    console.log("catch error" + error + '\n');
    $("#debugnote").html(String(error));
  }
}
