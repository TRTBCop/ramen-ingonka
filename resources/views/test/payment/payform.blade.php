@php
        @endphp
        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>S'Pay 결제 페이지</title>
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
            padding: 1px;
            border: 1px solid #e1e1e1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .left {
            padding-left: 10px;
            width: 20%;
        }

        .right {
            width: 80%;
        }

        .right span {
            font-size: 9pt;
            color: red;
            padding: 0px 2px;
        }

        input[type='text'] {
            width: 350px;
            margin: 5px 5px;
        }

        form {
            margin: 0;
        }

        .wrapper {
            width: 470px;
            border: 1px solid #e1e1e1;
            margin-top: 20px;
        }

        .tab {
            background-color: #f1f1f1;
            padding: 10px 20px;
            border: 1px solid #e1e1e1;
            font-weight: bold;
            font-size: 1.1em;
        }

        .content {
            padding: 3px 3px;
            border: 1px solid #e1e1e1;
        }

        .payBtn {
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #ccc;
            height: 50px;
            width: 32.5%;
            margin: 3px 0px;
            transition: 0.3s;
            cursor: pointer;
        }

        .payBtn:hover {
            background-color: #ddd;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript"
            src="{{ $paymentService->config('pg.payment_server')  }}/resources/js/v1/SettlePG_v1.2.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
        }
      });

      /** 날짜 및 주문정보 재설정 */
      function init (type) {
        var curr_date = new Date();
        var year = curr_date.getFullYear().toString();
        var month = ("0" + (curr_date.getMonth() + 1)).slice(-2).toString();
        var day = ("0" + (curr_date.getDate())).slice(-2).toString();
        var hours = ("0" + curr_date.getHours()).slice(-2).toString();
        var mins = ("0" + curr_date.getMinutes()).slice(-2).toString();
        var secs = ("0" + curr_date.getSeconds()).slice(-2).toString();
        var random4 = ("000" + Math.random() * 10000).slice(-4).toString();

        $("#STPG_payForm [name=\"custIp\"]").val("{{ $paymentService->getRealClientIp() }}"); //고객 IP 세팅
        $("#STPG_payForm [name=\"trdDt\"]").val(year + month + day);  //요청일자 세팅
        $("#STPG_payForm [name=\"trdTm\"]").val(hours + mins + secs); //요청시간 세팅
        $("#STPG_payForm [name=\"mchtTrdNo\"]").val();//주문번호 세팅

        $("#STPG_payForm2 [name=\"trdDt\"]").val(year + month + day); //요청일자 세팅
        $("#STPG_payForm2 [name=\"trdTm\"]").val(hours + mins + secs);//요청시간 세팅
        $("#STPG_payForm2 [name=\"mchtTrdNo\"]").val("AUTOPAY" + year + month + day + hours + mins + secs + random4);//주문번호 세팅

        //휴대폰 자동결제인 경우
        if (type === "mobileAuto") {
          $("#STPG_payForm [name=\"method\"]").val("mobile");
          $("#STPG_payForm [name=\"autoPayType\"]").val("M"); //M으로 설정
        } else if (type === "payco") {//페이코 간편결제 결제수단 및 구분 코드 설정
          $("#STPG_payForm [name=\"method\"]").val("corp");
          $("#STPG_payForm [name=\"corpPayCode\"]").val("PAC"); // PAC:페이코
        } else if (type === "kakao") {//카카오페이 간편결제 결제수단 및 구분 코드 설정
          $("#STPG_payForm [name=\"method\"]").val("corp");
          $("#STPG_payForm [name=\"corpPayCode\"]").val("KKP"); // KKP:카카오페이
        } else if (type === "nvpay") {//네이버페이 간편결제 결제수단 및 구분 코드 설정
          $("#STPG_payForm [name=\"method\"]").val("corp");
          $("#STPG_payForm [name=\"corpPayCode\"]").val("NVP"); // NVP:네이버페이
        } else {
          $("#STPG_payForm [name=\"method\"]").val(type);
        }
      }

      /** 결제 버튼 동작 */
      function pay (type) {

        if (type == "vbank") {
          $("#STPG_payForm [name=\"mchtId\"]").val("nx_mid_il");
        } else if (type == "card") {
          $("#STPG_payForm [name=\"mchtId\"]").val("nxca_jt_il");
        }
        //날짜 및 결제수단 등 재설정
        init(type);

        const studentNoti = '{{ route('api.payments.noti', '10000') }}';
        const academyNoti = '{{ route('api.payments.academy-noti', '10000') }}';
        const modelId = $("#STPG_payForm [name=\"modelId\"]").val();
        if ($("#STPG_payForm [name='type']:checked").val() === "S") {
          $("#STPG_payForm [name='notiUrl']").val(studentNoti.replace("10000", modelId));

          $("#STPG_payForm [name='plainTrdAmt']").val($("#STPG_payForm [name=\"product\"]").val());
        } else {
          $("#STPG_payForm [name='notiUrl']").val(academyNoti.replace("10000", modelId));
        }

        //용도 : SHA256 해쉬 처리 및 민감정보 AES256암호화
        $.ajax({
          type: "POST",
          url: "{{ route('test.payment.encrypt-params')  }}",
          dataType: "json",
          data: $("#STPG_payForm").serialize(),
          success: function(rsp) {
            //암호화 된 파라미터 세팅
            for (name in rsp.data.enc_params) {
              $("#STPG_payForm [name=" + name + "]").val(rsp.data.enc_params[name]);
            }
            $("#STPG_payForm [name=\"mchtTrdNo\"]").val(rsp.data.od_id);
            $("#mchtTrdNo").text(rsp.data.od_id);

            //가맹점 -> 세틀뱅크로 결제 요청
            SETTLE_PG.pay({
              env: "{{ $paymentService->config('pg.payment_server')  }}",   //결제서버 URL
              mchtId: $("#STPG_payForm [name=\"mchtId\"]").val(),
              method: $("#STPG_payForm [name=\"method\"]").val(),
              trdDt: $("#STPG_payForm [name=\"trdDt\"]").val(),
              trdTm: $("#STPG_payForm [name=\"trdTm\"]").val(),
              mchtTrdNo: $("#STPG_payForm [name=\"mchtTrdNo\"]").val(),
              mchtName: $("#STPG_payForm [name=\"mchtName\"]").val(),
              mchtEName: $("#STPG_payForm [name=\"mchtEName\"]").val(),
              pmtPrdtNm: $("#STPG_payForm [name=\"pmtPrdtNm\"]").val(),
              trdAmt: $("#STPG_payForm [name=\"trdAmt\"]").val(),
              mchtCustNm: $("#STPG_payForm [name=\"mchtCustNm\"]").val(),
              custAcntSumry: $("#STPG_payForm [name=\"custAcntSumry\"]").val(),
              expireDt: $("#STPG_payForm [name=\"expireDt\"]").val(),
              notiUrl: $("#STPG_payForm [name=\"notiUrl\"]").val(),
              nextUrl: $("#STPG_payForm [name=\"nextUrl\"]").val(),
              cancUrl: $("#STPG_payForm [name=\"cancUrl\"]").val(),
              mchtParam: $("#STPG_payForm [name=\"mchtParam\"]").val(),
              cphoneNo: $("#STPG_payForm [name=\"cphoneNo\"]").val(),
              email: $("#STPG_payForm [name=\"email\"]").val(),
              telecomCd: $("#STPG_payForm [name=\"telecomCd\"]").val(),
              prdtTerm: $("#STPG_payForm [name=\"prdtTerm\"]").val(),
              mchtCustId: $("#STPG_payForm [name=\"mchtCustId\"]").val(),
              taxTypeCd: $("#STPG_payForm [name=\"taxTypeCd\"]").val(),
              taxAmt: $("#STPG_payForm [name=\"taxAmt\"]").val(),
              vatAmt: $("#STPG_payForm [name=\"vatAmt\"]").val(),
              taxFreeAmt: $("#STPG_payForm [name=\"taxFreeAmt\"]").val(),
              svcAmt: $("#STPG_payForm [name=\"svcAmt\"]").val(),
              cardType: $("#STPG_payForm [name=\"cardType\"]").val(),
              chainUserId: $("#STPG_payForm [name=\"chainUserId\"]").val(),
              cardGb: $("#STPG_payForm [name=\"cardGb\"]").val(),
              clipCustNm: $("#STPG_payForm [name=\"clipCustNm\"]").val(),
              clipCustCi: $("#STPG_payForm [name=\"clipCustCi\"]").val(),
              clipCustPhoneNo: $("#STPG_payForm [name=\"clipCustPhoneNo\"]").val(),
              certNotiUrl: $("#STPG_payForm [name=\"certNotiUrl\"]").val(),
              skipCd: $("#STPG_payForm [name=\"skipCd\"]").val(),
              multiPay: $("#STPG_payForm [name=\"multiPay\"]").val(),
              autoPayType: $("#STPG_payForm [name=\"autoPayType\"]").val(),
              linkMethod: $("#STPG_payForm [name=\"linkMethod\"]").val(),
              appScheme: $("#STPG_payForm [name=\"appScheme\"]").val(),
              custIp: $("#STPG_payForm [name=\"custIp\"]").val(),
              corpPayCode: $("#STPG_payForm [name=\"corpPayCode\"]").val(),
              corpPayType: $("#STPG_payForm [name=\"corpPayType\"]").val(),
              cashRcptUIYn: $("#STPG_payForm [name=\"cashRcptUIYn\"]").val(),
              pktHash: rsp.data.hash_cipher,   //SHA256 처리된 해쉬 값 세팅

              ui: {
                type: "popup",   //popup, iframe, self, blank
                width: "430",   //popup창의 너비
                height: "660"   //popup창의 높이
              }
            }, function(rsp) {
              //iframe인 경우 전달된 결제 완료 후 응답 파라미터 처리
              console.log(rsp);
            });
          },
          error: function() {
            alert("에러 발생");
          }
        });
      }

      /** 휴대폰 자동연장 결제(2회차) 버튼 동작 */
      function autoPay () {
        init("mobileAuto");
        $("#STPG_payForm2").attr("action", "pay_autoPayResult.php");
        $("#STPG_payForm2").attr("method", "post");
        $("#STPG_payForm2").attr("target", "_self");
        $("#STPG_payForm2").submit();
      }

      /** 가상계좌/010가상계좌 입금테스트 */
      function vbankTest () {
        $("#STPG_vbankTest").attr("action", "https://tbgw.settlebank.co.kr/spay/APIVBankTest.do");
        $("#STPG_vbankTest").attr("method", "get");
        $("#STPG_vbankTest").attr("target", "_self");
        $("#STPG_vbankTest").submit();
      }

      /** pay_receiveResult.php로부터 응답 값을 받아와서 main폼에 세팅 */
      function rstparamSet (rslt) {
        $("#STPG_payForm [name=\"respMchtId\"]").val(rslt.mchtId);
        $("#STPG_payForm [name=\"respOutStatCd\"]").val(rslt.outStatCd);
        $("#STPG_payForm [name=\"respOutRsltCd\"]").val(rslt.outRsltCd);
        $("#STPG_payForm [name=\"respOutRsltMsg\"]").val(rslt.outRsltMsg);
        $("#STPG_payForm [name=\"respMethod\"]").val(rslt.method);
        $("#STPG_payForm [name=\"respMchtTrdNo\"]").val(rslt.mchtTrdNo);
        $("#STPG_payForm [name=\"respMchtCustId\"]").val(rslt.mchtCustId);
        $("#STPG_payForm [name=\"respTrdNo\"]").val(rslt.trdNo);
        $("#STPG_payForm [name=\"respTrdAmt\"]").val(rslt.trdAmt);
        $("#STPG_payForm [name=\"respMchtParam\"]").val(rslt.mchtParam);
        $("#STPG_payForm [name=\"respAuthDt\"]").val(rslt.authDt);
        $("#STPG_payForm [name=\"respAuthNo\"]").val(rslt.authNo);
        $("#STPG_payForm [name=\"respReqIssueDt\"]").val(rslt.reqIssueDt);
        $("#STPG_payForm [name=\"respIntMon\"]").val(rslt.intMon);
        $("#STPG_payForm [name=\"respFnNm\"]").val(rslt.fnNm);
        $("#STPG_payForm [name=\"respFnCd\"]").val(rslt.fnCd);
        $("#STPG_payForm [name=\"respPointTrdNo\"]").val(rslt.pointTrdNo);
        $("#STPG_payForm [name=\"respPointTrdAmt\"]").val(rslt.pointTrdAmt);
        $("#STPG_payForm [name=\"respCardTrdAmt\"]").val(rslt.cardTrdAmt);
        $("#STPG_payForm [name=\"respVtlAcntNo\"]").val(rslt.vtlAcntNo);
        $("#STPG_payForm [name=\"respExpireDt\"]").val(rslt.expireDt);
        $("#STPG_payForm [name=\"respCphoneNo\"]").val(rslt.cphoneNo);
        $("#STPG_payForm [name=\"respBillKey\"]").val(rslt.billKey);
        $("#STPG_payForm [name=\"respCsrcAmt\"]").val(rslt.csrcAmt);
      }

      /** main폼에 세팅된 응답 값 출력 */
      function goResult () {
        $("#STPG_payForm").attr("action", '{{ url('/test/payment/show-result') }}');
        $("#STPG_payForm").attr("method", "post");
        $("#STPG_payForm").attr("target", "");
        $("#STPG_payForm").submit();
      }

      $(document).ready(function() {
        $("input[name='type']").change(function() {
          if ($("input[name='type']:checked").val() == "S") {
            $("#product_box").show();
            $("#amount_box").hide();
          } else {
            $("#product_box").hide();
            $("#amount_box").show();
          }
        });
      });
    </script>
</head>
<body>

<ul>
    <li> 로컬 테스트시 <a href="https://ngrok.com" target="_blank">https://ngrok.com</a> 실행후 신청해야 noti 요청 발생함</li>
    <li> 학생/학원 고유번호가 맞지 않을경우 결제되지 않음</li>
</ul>

<br>
<div class="wrapper">
    <form id="STPG_payForm" name="STPG_payForm">
        <!-- 승인 요청 파라미터(필수) -->
        <input type="hidden" name="method" value="" />                                          <!-- 결제수단 -->
        <input type="hidden" name="trdDt" value="" />                                           <!-- 요청일자(yyyyMMdd) -->
        <input type="hidden" name="trdTm" value="" />                                           <!-- 요청시간(HHmmss)-->

        <input type="hidden" name="mchtName" value="세틀뱅크" />                                 <!-- 상점한글명 -->
        <input type="hidden" name="mchtEName" value="Settlebank" />                             <!-- 상점영문명 -->
        <input type="hidden" name="pmtPrdtNm" value="테스트상품" />                              <!-- 상품명 -->
        <input type="hidden" name="notiUrl" value="{{ route('api.payments.noti', 1) }}" />
        <!-- 결과처리 URL 1번 학원 -->
        <input type="hidden" name="nextUrl" value="{{ route('test.payment.result') }}" />   <!-- 결과화면 URL -->
        <input type="hidden" name="cancUrl" value="{{ route('test.payment.result') }}" />   <!-- 결제취소 URL -->

        <!-- 승인 요청 파라미터(옵션) -->
        <input type="hidden" name="plainMchtCustNm" value="홍길동" />            <!-- 고객명(평문) -->
        <input type="hidden" name="custAcntSumry" value="세틀뱅크" />            <!-- 통장인자내용 -->
        <input type="hidden" name="expireDt" value="" />                        <!-- 입금만료일시(yyyyMMddHHmmss) -->
        <input type="hidden" name="mchtParam" value="{!! str_replace('"', '\'', $mchtParam) !!}" />
        <!-- 상점예약필드 -->
        <input type="hidden" name="plainCphoneNo" value="" />                   <!-- 핸드폰번호(평문) -->
        <input type="hidden" name="plainEmail" value="HongGilDong@example.com" /><!-- 이메일주소(평문) -->
        <input type="hidden" name="telecomCd" value="" />                       <!-- 통신사코드 -->
        <input type="hidden" name="prdtTerm" value="20221231235959" />          <!-- 상품제공기간 -->
        <input type="hidden" name="plainMchtCustId" value="HongGilDong" />      <!-- 상점고객아이디(평문) -->
        <input type="hidden" name="taxTypeCd" value="" />                       <!-- 면세여부(Y:면세, N:과세, G:복합과세) -->
        <input type="hidden" name="plainTaxAmt" value="" />                     <!-- 과세금액(평문)(복합과세인 경우 필수) -->
        <input type="hidden" name="plainVatAmt" value="" />                     <!-- 부가세금액(평문)(복합과세인 경우 필수) -->
        <input type="hidden" name="plainTaxFreeAmt" value="" />                 <!-- 비과세금액(평문)(복합과세인 경우 필수) -->
        <input type="hidden" name="plainSvcAmt" value="" />                     <!-- 봉사료(평문) -->
        <input type="hidden" name="cardType" value="" />                        <!-- 카드결제타입 -->
        <input type="hidden" name="chainUserId" value="" />                     <!-- 현대카드Payshot ID -->
        <input type="hidden" name="cardGb" value="" />                          <!-- 특정카드사코드 -->
        <input type="hidden" name="plainClipCustNm" value="" />                 <!-- 클립포인트고객명(평문) -->
        <input type="hidden" name="plainClipCustCi" value="" />                 <!-- 클립포인트CI(평문) -->
        <input type="hidden" name="plainClipCustPhoneNo" value="" />            <!-- 클립포인트고객핸드폰번호(평문) -->
        <input type="hidden" name="certNotiUrl" value="" />                     <!-- 인증결과URL -->
        <input type="hidden" name="skipCd" value="" />                          <!-- 스킵여부 -->
        <input type="hidden" name="multiPay" value="" />                        <!-- 포인트복합결제 -->
        <input type="hidden" name="autoPayType" value="" />                     <!-- 자동결제 타입(공백:일반결제, M:월자동 1회차) -->
        <input type="hidden" name="linkMethod" value="" />                      <!-- 연동방식 -->
        <input type="hidden" name="appScheme" value="" />                       <!-- 앱스키마 -->
        <input type="hidden" name="custIp" value="" />                          <!-- 고객IP주소 -->
        <input type="hidden" name="corpPayCode" value="" />                     <!-- 간편결제 코드 -->
        <input type="hidden" name="corpPayType" value="" />                     <!-- 간편결제 타입(CARD:카드, POINT:포인트) -->
        <input type="hidden" name="cashRcptUIYn" value="" />                    <!-- 현금영수증 발급 여부 -->


        <!-- 응답 파라미터 -->
        <input type="hidden" name="respMchtId" />           <!-- 상점아이디 -->
        <input type="hidden" name="respOutStatCd" />        <!-- 거래상태 -->
        <input type="hidden" name="respOutRsltCd" />        <!-- 거절코드 -->
        <input type="hidden" name="respOutRsltMsg" />       <!-- 결과메세지 -->
        <input type="hidden" name="respMethod" />           <!-- 결제수단 -->
        <input type="hidden" name="respMchtTrdNo" />        <!-- 상점주문번호 -->
        <input type="hidden" name="respMchtCustId" />       <!-- 상점고객아이디 -->
        <input type="hidden" name="respTrdNo" />            <!-- 세틀뱅크 거래번호 -->
        <input type="hidden" name="respTrdAmt" />           <!-- 거래금액 -->
        <input type="hidden" name="respMchtParam" />        <!-- 상점예약필드 -->
        <input type="hidden" name="respAuthDt" />           <!-- 승인일시 -->
        <input type="hidden" name="respAuthNo" />           <!-- 승인번호 -->
        <input type="hidden" name="respReqIssueDt" />       <!-- 채번요청일시 -->
        <input type="hidden" name="respIntMon" />           <!-- 할부개월수 -->
        <input type="hidden" name="respFnNm" />             <!-- 카드사명 -->
        <input type="hidden" name="respFnCd" />             <!-- 카드사코드 -->
        <input type="hidden" name="respPointTrdNo" />       <!-- 포인트거래번호 -->
        <input type="hidden" name="respPointTrdAmt" />      <!-- 포인트거래금액 -->
        <input type="hidden" name="respCardTrdAmt" />       <!-- 신용카드결제금액 -->
        <input type="hidden" name="respVtlAcntNo" />        <!-- 가상계좌번호 -->
        <input type="hidden" name="respExpireDt" />         <!-- 입금기한 -->
        <input type="hidden" name="respCphoneNo" />         <!-- 휴대폰번호 -->
        <input type="hidden" name="respBillKey" />          <!-- 자동결제키 -->
        <input type="hidden" name="respCsrcAmt" />          <!-- 현금영수증 발급 금액(네이버페이) -->


        <!-- 암호화 처리 후 세팅될 파라미터-->
        <input type="hidden" name="trdAmt" />               <!-- 거래금액(암호문) -->
        <input type="hidden" name="mchtCustNm" />           <!-- 상점고객이름(암호문) -->
        <input type="hidden" name="cphoneNo" />             <!-- 휴대폰번호(암호문) -->
        <input type="hidden" name="email" />                <!-- 이메일주소(암호문) -->
        <input type="hidden" name="mchtCustId" />           <!-- 상점고객아이디(암호문) -->
        <input type="hidden" name="taxAmt" />               <!-- 과세금액(암호문) -->
        <input type="hidden" name="vatAmt" />               <!-- 부가세금액(암호문) -->
        <input type="hidden" name="taxFreeAmt" />           <!-- 면세금액(암호문) -->
        <input type="hidden" name="svcAmt" />               <!-- 봉사료(암호문) -->
        <input type="hidden" name="clipCustNm" />           <!-- 클립포인트고객명(암호문) -->
        <input type="hidden" name="clipCustCi" />           <!-- 클립포인트고객CI(암호문) -->
        <input type="hidden" name="clipCustPhoneNo" />      <!-- 클립포인트고객휴대폰번호(암호문) -->
        <input type="hidden" name="mchtTrdNo" value="" />


        <div class="tab">UI 결제창 호출</div>
        <table>
            <tr>
                <td class="left">상품 타입</td>
                <td class="right">
                    <label>
                        <input type="radio" name="type" value="S" checked> B2C 이용권결제
                    </label>
                    <br>
                    <label><input type="radio" name="type" value="AD"> 학원-직접결제 </label>
                    <br>
                    <label><input type="radio" name="type" value="AA"> 학원-자동결제 </label>
                    <br>
                    타입에 따라 주문번호 달라짐
                </td>
            </tr>
            <tr>
                <td class="left">학생/학원 고유번호</td>
                <td class="right">
                    <input type="text" name="modelId" value="1" />
                </td>
            </tr>
            <tr>
                <td class="left">상점아이디</td>
                <td class="right">
                    <input type="text" name="mchtId" value="{{ $paymentService->config('pg.mid')  }}" />
                </td>
            </tr>
            <tr id="product_box">
                <td class="left">이용권</td>
                <td class="right">
                    @foreach($paymentService->config('products') as $product)
                        <input type="radio" name="product"
                               value="{{ $product['amount']['sale'] ?: $product['amount']['origin'] }}"
                               @if ($loop->first) checked @endif> {{ $product['name'] }}
                    @endforeach
                </td>
            </tr>
            <tr id="amount_box" style="display: none">
                <td class="left">거래금액</td>
                <td class="right">
                    <input type="text" name="plainTrdAmt" value="300" />
                </td>
            </tr>
            <tr>
                <td class="left">상점주문번호</td>
                <td class="right" id="mchtTrdNo" style="padding: 10px 5px">
                    결제 수단 클릭시 생성됩니다.
                </td>
            </tr>
        </table>
        <div class="content">
            <input type="button" class="payBtn" value="신용카드" onclick="pay('card')" />
            <input type="button" class="payBtn" value="가상계좌" onclick="pay('vbank')" />
            <input type="button" class="payBtn" value="간편결제_페이코" onclick="pay('payco')" />
            <input type="button" class="payBtn" value="간편결제_카카오페이" onclick="pay('kakao')" />
            <input type="button" class="payBtn" value="간편결제_네이버페이" onclick="pay('nvpay')" />
        </div>
    </form>
</div>


<div class="wrapper">
    <form id="STPG_vbankTest" name="STPG_vbankTest">
        <div class="tab">가상계좌/010가상계좌 입금테스트 API (Non-UI)</div>

        <input type="hidden" name="method" value="VA" />
        <input type="hidden" name="bizType" value="F1" />
        <table>
            <tr>
                <td class="left">상점아이디</td>
                <td class="right"><input type="text" name="mchtId" value="nx_mid_il" /></td>
            </tr>
            <tr>
                <td class="left">가상계좌번호</td>
                <td class="right"><input type="text" name="vAcntNo" maxlength="50" value="0123456789" /></td>
            </tr>
            <tr>
                <td class="left">거래금액</td>
                <td class="right"><input type="text" name="trdAmt" maxlength="9" value="300" /></td>
            </tr>
            <tr>
                <td colspan="2" class="right" style="text-align: center;"><input class="payBtn" type="button"
                                                                                 value="입금테스트" style="width:99%;"
                                                                                 onclick="vbankTest()" /></td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
