<html>
<head>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>S'Pay 결제 결과 페이지</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .left {
            padding-left: 5px;
            width: 200px;
        }

        .right {
            padding-left: 5px;
        }

        .wrapper {
            width: 700px;
            border: 1px solid #e1e1e1;
        }

        .tab {
            background-color: #f1f1f1;
            padding: 10px 20px;
            border: 1px solid #e1e1e1;
            font-weight: bold;
            font-size: 1.1em;
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
</head>
<body>
<h2>승인 요청 결과</h2>
<div class="wrapper">
    <div class="tab">응답 파라미터</div>
    <table>
        <tr>
            <td class="left">mchtId[상점아이디]</td>
            <td class="right">{{ request('mchtId') }}</td>
        </tr>
        <tr>
            <td class="left">outStatCd[거래상태]</td>
            <td class="right">{{ request('respOutStatCd') }}</td>
        </tr>
        <tr>
            <td class="left">outRsltCd[거절코드]</td>
            <td class="right">{{ request('respOutRsltCd') }}</td>
        </tr>
        <tr>
            <td class="left">outRsltMsg[메세지]</td>
            <td class="right">{{ request('respOutRsltMsg') }}</td>
        </tr>
        <tr>
            <td class="left">method[결제수단]</td>
            <td class="right">{{ request('respMethod') }}</td>
        </tr>
        <tr>
            <td class="left">mchtTrdNo[상점주문번호]</td>
            <td class="right">{{ request('respMchtTrdNo') }}</td>
        </tr>
        <tr>
            <td class="left">mchtCustId[상점고객아이디]</td>
            <td class="right">{{ request('respMchtCustId') }}</td>
        </tr>
        <tr>
            <td class="left">trdNo[세틀뱅크거래번호]</td>
            <td class="right">{{ request('respMchtTrdNo') }}</td>
        </tr>
        <tr>
            <td class="left">trdAmt[거래금액]</td>
            <td class="right">{{ request('plainTrdAmt') }}</td>
        </tr>

        <tr>
            <td class="left">authDt[승인일시]</td>
            <td class="right">{{ request('respAuthDt') }}</td>
        </tr>
        <tr>
            <td class="left">authNo[승인번호]</td>
            <td class="right">{{ request('respAuthNo') }}</td>
        </tr>

        <tr>
            <td class="left">fnNm[카드사명]</td>
            <td class="right">{{ request('respFnNm') }}</td>
        </tr>

        <tr>
            <td class="left">vtlAcntNo[가상계좌번호]</td>
            <td class="right">{{ request('respVtlAcntNo') }}</td>
        </tr>
        <tr>
            <td class="left">expireDt[입금기한]</td>
            <td class="right">{{ request('respExpireDt') }}</td>
        </tr>
        <tr>
            <td class="left">billKey[자동결제키]</td>
            <td class="right">{{ request('respBillKey') }}</td>
        </tr>
        <tr>
            <td class="left">csrcAmt[현금영수증 발급 금액(네이버페이)]</td>
            <td class="right">{{ request('csrcAmt') }}</td>
        </tr>
    </table>
</div>
</body>
</html>