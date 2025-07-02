<?php
if (!function_exists('settings')) {
    function settings()
    {
        $root = "http://localhost/php/Quiz%20Project%20Final/Quiz-Palette-updated2/";
        return [
            'root'  => $root,
            'companyname' => 'Quiz Palette.',
            'logo' => $root . "admin/assets/img/logo.svg",
            'homepage' => $root,
            'adminpage' => $root . 'admin/',
            'hostname' => config('db.host') ?? 'localhost',
            'user' => config('db.user') ?? 'root',
            'password' => config('db.password') ?? '',
            'database' => config('db.database') ?? 'quizpallete'
        ];
    }
}
if (!function_exists('testfunc')) {
    function testfunc()
    {
        return "<h3>testing common functions</h3>";
    }
}
if (!function_exists('config')) {
    function config($param)
    {
        $parts = explode(".", $param);
        $inc = include(__DIR__ . "/../config/" . $parts[0] . ".php");
        return $inc[$parts[1]];
    }
}
