<?php

//Basic認証
$user = 'sgmv2';
$pass = 'sagawa2';

if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER["PHP_AUTH_USER"]==$user && $_SERVER["PHP_AUTH_PW"]==$pass)){
    //print '<div style="position:absolute;top:0px;left:0;z-index:1020;">未公開</div>';
} else {
    header("WWW-Authenticate: Basic realm=\"basic\"");
    header("HTTP/1.0 401 Unauthorized - basic");
    echo "<script>location.href='/404.html'</script>";
    exit();
}


?>