@include('layouts.header')
</head>
<!--
Use to automatically hide the Side Nav Bar
<body class="toggle-sidebar">-->
<body class="">
@yield('content')
 
@include('layouts.footer')

<?php

if (Request::is('billing')){
?>
@include('layouts.billing_script')
<?php
}else if (Request::is('product')){
?>
@include('layouts.product_script')
<?php
}
else if (Request::is('client')){
?>
@include('layouts.client_script')
<?php
}
else if (Request::is('report')){
?>
@include('layouts.report_script')
<?php
}

?>
</body>

</html>