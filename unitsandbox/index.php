<?php
// header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
// header('Feature-Policy: usb none;');
header('Feature-Policy: usb *;');

// https://github.com/google/web-serial-polyfill/blob/master/demo.html
// https://codelabs.developers.google.com/codelabs/web-serial#3

// chrome://flags/
// #enable-experimental-web-platform-features
// Disabled を Enabled に

// https://glitch.com/edit/#!/remix/web-serial-codelab-start"
?>

<html>
<title>webusb.html</title>
<head>
<!-- Feature-Policy: fullscreen "*"; usb "none"; payment "self" https://payment.example.com
Strict-Transport-Security: max-age=31536000; includeSubDomains
Strict-Transport-Security: max-age=63072000; includeSubDomains; preload

Strict-Transport-SecurityもFeature-Policy
 -->
</head>
<body>
    <div id="app">
<!--         <button id="connect" @click="connectButtonAction" v-cloak>{{connectButtonText}}</button>
        <span id="status" v-cloak>{{statusText}}</span>

        <color-picker v-model="color" @input="onColorInput"></color-picker>
        <h1 v-text="msg"></h1>
        <pre v-html="color"></pre>
 -->
<!--         <script src="https://jp.vuejs.org/js/vue.min.js"></script>
        <script src="https://unpkg.com/@radial-color-picker/vue-color-picker"></script>
        <script src="serial.js"></script>
        <script src="app.js"></script> -->
     </div>


<button id="arduinoButton">Talk to CO2 senser</button>
<div id="target"></div>




<iframe allowpaymentrequest allow="usb; fullscreen"></iframe>

USB\ROOT_HUB30\5&19116F1&0&0
USB\ROOT_HUB30\5&358E0DD4&0&0
USB\ROOT_HUB30\5&4087D53&0&0

USB\VID_056E&PID_1058\6&3AF0F9CE&0&8


WEB USB

macOSの場合system_profiler SPUSBDataType
windows  USB Device Viewerで確認してみたが
C:\Program Files (x86)\Windows Kits\10\Debuggers\x64\usbview.exe
ConnectionStatus:      NoDeviceConnected
ポケットCO2モニターはLEDが光るので給電はしているもよう

ポケットCO2モニター側でUSB接続のローレベルのプロトコルが必要？



idVendor:                        0x04D8 = Microchip Technology Inc.
idProduct:                       0x000A

English (United States)  "Microchip Technology Inc."
iProduct:                          0x02
English (United States)  "CDC RS-232 Emulation Demo"

iSerialNumber:                     0x00
iManufacturer:                     0x01
English (United States)  "Microchip Technology Inc."


chrome://device-log

[2021/02/24 23:24:37.181937] USB device added: path=\\?\usb#vid_04d8&pid_000a#6&3af0f9ce&0&7#{a5dcbf10-6530-11d2-901f-00c04fb951ed} vendor=1240 "Microchip Technology Inc.", product=10 "CDC RS-232 Emulation Demo", serial="", driver="usbser", guid=ca8a0b53-afe0-47a1-b29a-ab47003528fc

USBUser[2021/02/24 23:24:37.168429] USB device added: path=\\?\usb#vid_0582&pid_0073#7&24c464d&0&1#{a5dcbf10-6530-11d2-901f-00c04fb951ed} vendor=1410 "Roland", product=115 "EDIROL UA-25", serial="", driver="usbccgp", guid=6ee8b7f0-0365-42b7-9a22-b3378bea527a

USBUser[2021/02/24 23:24:37.162971] USB device added: path=\\?\usb#vid_056e&pid_1058#6&3af0f9ce&0&8#{a5dcbf10-6530-11d2-901f-00c04fb951ed} vendor=1390 "ELECOM", product=4184 "ELECOM TK-FDM088TBK", serial="", driver="usbccgp", guid=45d551c0-a6c5-4629-8b48-73c5a6287a0e

USBUser[2021/02/24 23:24:37.151662] USB device added: path=\\?\usb#vid_04f3&pid_0210#6&3af0f9ce&0&6#{a5dcbf10-6530-11d2-901f-00c04fb951ed} vendor=1267 "", product=528 "PS/2+USB Mouse", serial="", driver="HidUsb", guid=d1f0f216-515c-476f-9166-402fb31cbed4

<script>
// const notSupported = document.getElementById('notSupported');
// notSupported.classList.toggle('hidden', 'serial' in navigator);


document.getElementById('arduinoButton').addEventListener('click', function () {
  // if (navigator.usb) {
  if (navigator.serial) {
    talkToArduino();
  } else {
    alert('Web Serial API not supported.');
    // alert('WebUSB not supported.');
  }
});


// https://wicg.github.io/serial/#open-method
// https://kuippa.com/co2/

async function talkToArduino() {
  try {

// { filters: [filter] }

    // const port = await navigator.serial.requestPort();
    const port = await navigator.serial.requestPort({ filters: [{ usbVendorId: 0x04D8 }] });
console.log("requestPort");
    console.log(port.getInfo());
    // port.getInfo();
    // port.open(options);
console.log("getInfo");

    // await port.open({ baudrate: 9600 });
    await port.open({ baudRate: 9600 });

console.log("open");
//     reader = port.readable.getReader();
// console.log(reader);
//     writer = port.writable.getWriter();
// console.log(writer);

    const decoder = new TextDecoderStream();
    port.readable.pipeTo(decoder.writable);
console.log("decoder");

    const inputStream = decoder.readable;
    const reader = inputStream.getReader();
console.log("reader");
    // readLoop();


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

    // if (port) {
    //     // await disconnect();
    //     // toggleUIConnected(false);
    //     return;
    // }

    // disconnect()
    // if (outputStream) {
    //     await outputStream.getWriter().close();
    //     await outputDone;
    //     outputStream = null;
    //     outputDone = null;
    // }
    // await port.close();
    // port = null;


// [2021/02/24 23:24:37.162971] USB device added: path=\\?\usb#vid_056e&pid_1058#6&3af0f9ce&0&8#{a5dcbf10-6530-11d2-901f-00c04fb951ed} vendor=1390 "ELECOM", product=4184 "ELECOM TK-FDM088TBK", serial="", driver="usbccgp", guid=45d551c0-a6c5-4629-8b48-73c5a6287a0e

// 0x04D8
// "CDC RS-232 Emulation Demo  Microchip Technology Inc.
// https://www.microchip.co.jp/aboutus/profile.html
// MPLAB X IDEはRS-232接続はサポートしていません。USBインターフェイスをお使いください。もしくは、プログラムだけであればMPLAB IPEがv2.30からRS-232接続をサポートしています。

// USBConnectionEvent
// If device.opened is true resolve promise and abort these steps.

// Perform the necessary platform-specific steps to begin a session with the device. If these fail for any reason reject promise with a NetworkError and abort these steps.

// Set device.opened to true and resolve promise.

    // // navigator.usb.requestDevice({ filters: [{ vendorId: 0x056E }] }) // キーボード
    // navigator.usb.requestDevice({ filters: [{ vendorId: 0x04D8 }] })    // Microchip Technology Inc.
    // // navigator.usb.requestDevice({ filters: [{ vendorId: 0x04F3 }] })    // マウス
    // // navigator.usb.requestDevice({ filters: [{ vendorId: 0x0582 }] })    // EDIROL UA-25

    // .then(device => {
    //   console.log(device.opened);      //
    //   console.log(device.productName);      // "Arduino Micro"
    //   console.log(device.manufacturerName); // "Arduino LLC"

    //   // device.transferIn(1, 10);
    //   device.open();
    // })
    // .catch(error => { console.error(error); });

    // let device = await navigator.usb.requestDevice({ filters: [{ vendorId: 0x04D8 }] });
    // await device.open();
    // // console.log(device);
    // await device.claimInterface(0);
    // await device.transferIn(1, 10);

    // let device;
    // navigator.usb.requestDevice({ filters: [{ vendorId: 0x04D8 }] })
    // .then(selectedDevice => {
    //   console.log("selectedDevice");
    //   device = selectedDevice;
    //   console.log(device.configurations);
    //   return device.open(); // Begin a session.
    // })
    // .then(() => {
    //     console.log("selectConfiguration");
    //     device.selectConfiguration(1);
    // }) // Select

    // .then(() => device.claimInterface(0)) // Request
    // .then(() => device.transferIn(1, 10))

    // .then(() => device.claimInterface(1)) // Request
    // .then(() => device.transferIn(2, 64))


    // .then(() => device.claimInterface(device.configuration.interfaces[0].interfaceNumber))
    // .then(() => device.controlTransferOut({
    //   requestType: 'class',
    //   recipient: 'interface',
    //   request: 0x22,
    //   value: 0x01,
    //   index: 0x02})) // Ready to receive data
    // .then(() => device.transferIn(2, 64)) // Waiting for 64 bytes of data from endpoint #5.
    // .then(result => {
    //   const decoder = new TextDecoder();
    //   console.log('Received: ' + decoder.decode(result.data));
    // })


    // let device = await navigator.usb.requestDevice({ filters: [{ vendorId: 0x04D8 }] });

    // const filters = [
    //         {vendorId: 0x04D8, productId: 0x000A}
    //       ];

    // navigator.usb.requestDevice({filters: filters})
    // .then(usbDevice => {
    //   console.log(usbDevice.productName);
    //   console.log(usbDevice.manufacturerName);
    // })
    // .catch(error => { console.error(error); });


    // await device.open(); // Begin a session.

    // console.log(device.configurations);


    // await device.selectConfiguration(1); // configurationValue
    // await device.claimInterface(2); // interfaceNumber

    // 送信
    // await device.controlTransferOut({
    //   requestType: 'class', //standard,class,vendor
    //   recipient: 'interface', // "device", "interface", "endpoint", or "other"
    //   request: 0x22,  // A vendor-specific command.
    //   value: 0x01,  // Vender-specific request parameters.
    //   index: 0x02 // The interface number of the recipient.
    // });

    // 受信
    // var promise = USBDevice.controlTransferIn(setup, length)



    // let result = device.transferIn(5, 64); // Waiting for 64 bytes of data from endpoint #5.
    // let decoder = new TextDecoder();
    // document.getElementById('target').innerHTML = 'Received: ' + decoder.decode(result.data);
  } catch (error) {
    console.log("!!!! "+error);
    // document.getElementById('target').innerHTML = error;
  }
}


var nvUA = navigator.userAgent;
console.log(nvUA);
// const vendor_id = 0x04D8;
// const product_id = 0x000A;

// const filters = [
//         {vendorId: 0x04D8, productId: 0x000A}
//       ];

// navigator.usb.getDevices().then(devices => {
//   devices.forEach(device => {
//     console.log(device.productName);
//     console.log(device.manufacturerName);
//   });
// });

// navigator.usb.requestDevice({filters: filters})
// .then(usbDevice => {
//   console.log(usbDevice.productName);
//   console.log(usbDevice.manufacturerName);
// })
// .catch(error => { console.error(error); });

// let device;

// navigator.usb.requestDevice({ filters: [{ vendorId: 0x2341 }] })
// .then(selectedDevice => {
//     device = selectedDevice;
//     return device.open(); // Begin a session.
//   })
// .then(() => device.selectConfiguration(1)) // Select configuration #1 for the device.
// .then(() => device.claimInterface(2)) // Request exclusive control over interface #2.
// .then(() => device.controlTransferOut({
//     requestType: 'class',
//     recipient: 'interface',
//     request: 0x22,
//     value: 0x01,
//     index: 0x02})) // Ready to receive data
// .then(() => device.transferIn(5, 64)) // Waiting for 64 bytes of data from endpoint #5.
// .then(result => {
//   const decoder = new TextDecoder();
//   console.log('Received: ' + decoder.decode(result.data));
// })
// .catch(error => { console.error(error); });


//  console で確認するよう
// const vendor_id = 0x04D8;
// const product_id = 0x000A;
// const device2 = await navigator.usb.requestDevice(
//   {
//     'filters': [
//       {'vendorId': 0x04D8}
//     ]
//   }
// )
// device2.configurations

// await device.open()
// await device.selectConfiguration(1)
// await device.claimInterface(0)

// // デバイスの接続要求
// // ポップアップが出るので接続するデバイスを選択すること
// const device = await navigator.usb.requestDevice(
//   {
//     'filters': [
//       {'vendorId': vendor_id, 'product_id': product_id}
//     ]
//   }
// )
// // Configurationsの表示
// device.configurations


// // いずれもPromiseが返るので、awaitします
// await device.open()
// await device.selectConfiguration(1)
// await device.claimInterface(0)

</script>

</body>
