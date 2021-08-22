<html>
<title>webusb.html</title>
<head>

</head>
<body>
    <div id="app">
     </div>


<button id="arduinoButton">Talk to CO2 senser</button>
<div id="target"></div>




<iframe allowpaymentrequest allow="usb; fullscreen"></iframe>


<script>


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
    const port = await navigator.serial.requestPort();
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

//     writer = port.writable.getWriter();
// console.log(writer);
//     const encoder = new TextEncoder();
// console.log("encoder");
//     await writer.write(encoder.encode("STA" + '\n'));
// console.log("STA writer");
//     writer.releaseLock();
// console.log("releaseLock");

const encoder = new TextEncoderStream();
const writableStreamClosed = encoder.readable.pipeTo(port.writable);
const writer = encoder.writable.getWriter();
writer.write("STA" + '\r\n');
writer.write('\r\n');
writer.close();
await writableStreamClosed;
// await port.close();

console.log("reader");


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
</script>

</body>
