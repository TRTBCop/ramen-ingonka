<html>
<head><title>S'Pay 결제 결과 페이지</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        body {
            font-family: 굴림;
            font-size: 10pt;
            color: #000000;
            text-decoration: none;
        }

        font {
            font-family: 굴림;
            font-size: 10pt;
            color: #000000;
            text-decoration: none;
        }

        td {
            font-family: 굴림;
            font-size: 10pt;
            color: #000000;
            text-decoration: none;
            padding: 3px;
            border: 1px solid #e1e1e1;
        }

        .left {
            padding-left: 5px;
            width: 100px;
        }

        .right {
            padding-left: 5px;
        }

        .wrapper {
            max-width: 700px;
            border: 1px solid #e1e1e1;
        }

        .tab {
            background-color: #f1f1f1;
            padding: 10px 20px;
            border: 1px solid #e1e1e1;
            font-weight: bold;
            font-size: 1.1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .button {
            padding: 5px 20px;
            border-radius: 20px;
            border: 1px solid #ccc;
            width: 70%;
            margin: 5px 0px;
            transition: 0.3s;
            cursor: pointer;
        }

        .button:hover {
            background-color: #aaaaaa;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        //결제 결과 세팅
        var _PAY_RESULT = {
            mchtId: '{{ $resParams['mchtId'] }}',
            outStatCd: '{{ $resParams['outStatCd'] }}',
            outRsltCd: '{{ $resParams['outRsltCd'] }}',
            outRsltMsg: '{{ $resParams['outRsltMsg'] }}',
            method: '{{ $resParams['method'] }}',
            mchtTrdNo: '{{ $resParams['mchtTrdNo'] }}',
            mchtCustId: '{{ $resParams['mchtCustId'] }}',
            trdNo: '{{ $resParams['trdNo'] }}',
            trdAmt: '{{ $resParams['trdAmt'] }}',
            mchtParam: '{{ $resParams['mchtParam'] }}',
            authDt: '{{ $resParams['authDt'] }}',
            authNo: '{{ $resParams['authNo'] }}',
            reqIssueDt: '{{ $resParams['reqIssueDt'] }}',
            intMon: '{{ $resParams['intMon'] }}',
            fnNm: '{{ $resParams['fnNm'] }}',
            fnCd: '{{ $resParams['fnCd'] }}',
            pointTrdNo: '{{ $resParams['pointTrdNo'] }}',
            pointTrdAmt: '{{ $resParams['pointTrdAmt'] }}',
            cardTrdAmt: '{{ $resParams['cardTrdAmt'] }}',
            vtlAcntNo: '{{ $resParams['vtlAcntNo'] }}',
            expireDt: '{{ $resParams['expireDt'] }}',
            cphoneNo: '{{ $resParams['cphoneNo'] }}',
            billKey: '{{ $resParams['billKey'] }}',
            csrcAmt: '{{ $resParams['csrcAmt'] }}',
        };

        //main으로 결과 전달
        function sendResult() {
            if (top.opener) {
                //팝업창
                top.opener.rstparamSet(_PAY_RESULT);
                top.opener.goResult();
                self.close();
            } else {//iframe
                parent.postMessage(JSON.stringify({action: "HECTO_IFRAME_CLOSE", params: _PAY_RESULT}), "*");
            }
        }

        setTimeout(sendResult, 1000);
    </script>
</head>
</html>