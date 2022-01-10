<?php $cashUStandard = app('Webkul\CashU\Payment\CashUPayment') ?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the CashU website in a few seconds.

    <form action="{{ $cashUStandard->getCashuUrl() }}" id="cashu_checkout" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">
            <input type="hidden" name="Transaction_Code"
            value="'{{ $transcationCode }}'">
    </form>

    <script type="text/javascript">
        document.getElementById("cashu_checkout").submit();
    </script>
</body>

