
let _chk_idx = 0;
let _chk_agent = [0,0];  // ブラウザチェック
let _all_path = true; // ブラウザ環境
let _all_path_2 = true; // 動作環境
let _timer_id = 0;
let _timer_cnt = 0;

const MINIMUM_AGENT_VERSION = 80; // web serial は 80以上
const USER_AGENT = "chrome";
const TIMER_TMP = 40; // x5msec 描画のためのタイマーインターバル

function chgIconsSpinner(elmid) {
  $(elmid).removeClass("bi-square").addClass("spinner-border");
}
function chgIconsSpinnerSM(elmid) {
  $(elmid).removeClass("bi-square").addClass("spinner-border spinner-border-sm");
}
function chgIconsChecked(elmid) {
  $(elmid).removeClass("spinner-border").addClass("bi-check2-square text-info");
}
function chgIconsSuccess(elmid) {
  $(elmid).removeClass("spinner-border").addClass("bi-check-circle text-success");
}
function chgIconsCaution(elmid) {
  $(elmid).removeClass("spinner-border").addClass("bi-exclamation-triangle text-danger");
}
function chgDisp(elmid) {
  $(elmid).removeClass("d-none");
}
function chgDispNone(elmid) {
  $(elmid).addClass("d-none");
}

function checkBrowser() {
  switch (_chk_idx) {
    case 0:
      chgIconsSpinner("#chk_1");
      chgIconsSpinnerSM("#chk_1_1");
      break;
    // ブラウザチェック
    case 1:
      if(isChrome(_chk_agent[0])) {
        chgIconsSuccess("#chk_1_1");
      } else {
        chgIconsCaution("#chk_1_1");
        _all_path = false;
      }
      break;
    case 2:
      chgIconsSpinnerSM("#chk_1_2");
      break;
    // ブラウザバージョン
    case 3:
      if(isChromeVer(_chk_agent[1])) {
        chgIconsSuccess("#chk_1_2");
      } else {
        chgIconsCaution("#chk_1_2");
        _all_path = false;
      }
      break;
    case 4:
      if (_all_path) {
        chgIconsChecked("#chk_1");
      } else {
        chgIconsCaution("#chk_1");
        chgDisp("#chk_1_note");
      }
      break;
  }
  _chk_idx++;
  return true;
}

function checkOpeEnv() {
  switch (_chk_idx) {
    case 5:
      chgIconsSpinner("#chk_2");
      chgIconsSpinnerSM("#chk_2_1");
      break;
    // SSL
    case 6:
      if (isHttps()){
        chgIconsSuccess("#chk_2_1");
      } else {
        chgIconsCaution("#chk_2_1");
        chgDisp("#chk_2_1_note");
        _all_path_2 = false;
      }
      break;
    case 7:
      chgIconsSpinnerSM("#chk_2_2");
      break;
    // シリアル接続
    case 8:
      if(isSerialSupport()) {
        chgIconsSuccess("#chk_2_2");
      } else {
        chgIconsCaution("#chk_2_2");
        chgDisp("#chk_2_2_note");
        _all_path_2 = false;
      }
      break;
    case 9:
      if (_all_path_2) {
        chgIconsChecked("#chk_2");
      } else {
        chgIconsCaution("#chk_2");
      }
      break;
  }
  _chk_idx++;
  return true;
}

function isHttps() {
  var href_url = document.location.href;
  if (document.location.protocol != "https:") {
    if (document.location.protocol == "file:") {
      href_url = href_url.replace("file:/","https:");
    } else {
      href_url = href_url.replace(document.location.protocol,"https:");
    }
    $("#ssl_url").attr("href", href_url);
    $("#ssl_url").html(href_url);
    return false;
  }
  return true;
}

function isSerialSupport() {
  try {
    if (navigator.serial) {
    // if (navigator.serial && navigator.usb) {
      // $("#tesBrowseVer").html("ok");
    } else {
      // $("#tesBrowseVer").html("'Web Serial API not supported.'");
      return false;
    }
  } catch (error) {
    // $("#tesBrowseVer").html("----");
    // $("#tesBrowseVer").html(error);
    return false;
  }
  return true;
}

function isChrome(ua) {
  try{
    if (String(ua).toLowerCase().indexOf(USER_AGENT)>=0) {
      return true;
    }
    return false;
  } catch (error) {

    console.log(error + ua);
    return false;
  }
}

function isChromeVer(uaver) {
  if (uaver >= MINIMUM_AGENT_VERSION) {
    return true;
  }
  return false;
}

function getUserAgentInfo() {
  var nvUA = navigator.userAgent;
  var nvApp = navigator.appVersion;
  var uas = nvUA.split(' ');
  for (var i = 0 ; i < uas.length ; i++){
    if(isChrome(uas[i])) {
      _chk_agent[0] = uas[i]; // user Agent
      _chk_agent[1] = uas[i].split('/')[1].split('.')[0]; // Agent Major version
      if (isChromeVer(_chk_agent[1])) {
        return true;
      }
      return false;
    }
  }
  return false;
}

function timerCheckUp() {
  if ((_timer_cnt % 5 )==0) {
    if (_chk_idx < 5) {
      checkBrowser();
    } else if (_chk_idx < 10) {
      checkOpeEnv();
    } else if (_chk_idx == 10) {
      if (_all_path && _all_path_2) {
        $("#btnStart").removeAttr('disabled');
      }
    }
  }
  if (_timer_cnt > 150) {
    clearInterval(_timer_id);
  }
  _timer_cnt++;
}


function toggleStartStop() {
  if ($("#btnStart").is(':disabled')) {
    chgDisp("#btnStart");
    chgDispNone("#btnStop");
    $("#txtStartNote").html("ポケット二酸化炭素センサーを接続してください");
    $("#btnStart").prop('disabled',false);
    $("#btnStop").prop('disabled',true);
  } else {
    chgDisp("#btnStop");
    chgDispNone("#btnStart");

    $("#txtStartNote").html("");
    $("#btnStart").prop('disabled',true);
    $("#btnStop").prop('disabled',false);
  }
}
