<?php

date_default_timezone_set("Asia/Jakarta");

function url_foto_frontend($string)
{
    $url = "http://127.0.0.1:8000/" . $string;
    return $url;
}
