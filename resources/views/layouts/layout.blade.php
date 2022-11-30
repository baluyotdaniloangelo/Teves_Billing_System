@include('layouts.header')
</head>

<body class="toggle-sidebar">
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


?>
</body>

</html>