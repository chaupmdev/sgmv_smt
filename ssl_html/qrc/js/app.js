window.SQR = window.SQR || {}

SQR.reader = (() => {
    /**
     * getUserMedia()に非対応の場合は非対応の表示をする
     */
    const showUnsuportedScreen = () => {
        document.querySelector('#js-unsupported').classList.add('is-show')
    }
    if (!navigator.mediaDevices) {
        showUnsuportedScreen()
        return
    }

    const video = document.querySelector('#js-video')

    /**
     * videoの出力をCanvasに描画して画像化 jsQRを使用してQR解析
     */
    const checkQRUseLibrary = () => {
        const canvas = document.querySelector('#js-canvas')
        const ctx = canvas.getContext('2d')
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height)
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)
        const code = jsQR(imageData.data, canvas.width, canvas.height)

        if (code) {
            SQR.modal.open(code.data)
        } else {
            setTimeout(checkQRUseLibrary, 200)
        }
    }

    /**
     * videoの出力をBarcodeDetectorを使用してQR解析
     */
    const checkQRUseBarcodeDetector = () => {
        const barcodeDetector = new BarcodeDetector()
        barcodeDetector
            .detect(video)
            .then((barcodes) => {
                if (barcodes.length > 0) {
                    for (let barcode of barcodes) {
                        SQR.modal.open(barcode.rawValue)
                    }
                } else {
                    setTimeout(checkQRUseBarcodeDetector, 200)
                }
            })
            .catch(() => {
                console.error('Barcode Detection failed, boo.')
            })
    }

    /**
     * BarcodeDetector APIを使えるかどうかで処理を分岐
     */
    const findQR = () => {
        window.BarcodeDetector
            ? checkQRUseBarcodeDetector()
            : checkQRUseLibrary()
    }

    /**
     * デバイスのカメラを起動
     */
    const initCamera = () => {
        navigator.mediaDevices
            .getUserMedia({
                audio: false,
                video: {
                    facingMode: {
                        exact: 'environment',
                    },
                },
            })
            .then((stream) => {
                video.srcObject = stream
                video.onloadedmetadata = () => {
                    video.play()
                    findQR()
                }
            })
            .catch(() => {
                showUnsuportedScreen()
            })
    }

    return {
        initCamera,
        findQR,
    }
})()

SQR.modal = (() => {
    const result = document.querySelector('#js-result')
    const toiawase = document.querySelector('#js-sgqr')
    const modal = document.querySelector('#js-modal')
    const modalClose = document.querySelector('#js-modal-close')

    /**
     * 取得した文字列を入れ込んでモーダルを開く
     */
    const open = (url) => {
        result.value = url
        modal.classList.add('is-show')
    }

    /**
     * モーダルを閉じてQR読み込みを再開
     */
    const close = () => {
        modal.classList.remove('is-show')
        SQR.reader.findQR()
    }

    /**
     * QRコードを読み取り再利用する
     */
    const toiban = (function(){

        const qr = result.value
        const words = qr.split(',')
        alert('問合せ番号:' + words[0] + '\n価格:' + words[1])

        //ドキュメント上の最初のフォームを取得
        const form = document.forms[0];
        //input要素を生成
        const input = document.createElement('input');

        //input要素にtype属性とvalue属性を設定
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'toiban');
        input.setAttribute('value', words[0]);
        //form要素の末尾に挿入
        form.appendChild(input);

        //input要素を生成
        const input2 = document.createElement('input2');

        //input要素にtype属性とvalue属性を設定
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'kakaku');
        input.setAttribute('value', words[1]);
        //form要素の末尾に挿入
        form.appendChild(input2);

        document.myform.submit();
    
    })

    toiawase.addEventListener('click', toiban)

    modalClose.addEventListener('click', () => close())


    return {
        open,
    }
})()

if (SQR.reader) SQR.reader.initCamera()

