<?php
$segments = explode('/', $_SERVER['REQUEST_URI']);
array_pop($segments);
$baseURL = implode('/', $segments);
header('Location:' . $baseURL . '/html/')
?>