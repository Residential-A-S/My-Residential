<?php
require_once 'loader.php';

use core\Localization;
use core\PageRequest;
use core\Request;
use core\XMLHttpRequest;

const LOCALIZATION = new Localization();
const REQUEST = new Request();
const ROOT         = __DIR__;

session_start();

$request = null;
if (REQUEST->request_uri === '/xhr') {
    $request = new XMLHttpRequest();
    REQUEST->setResponseBody($request->getResponse(), false);
} else {
    $request = new PageRequest();
    REQUEST->setResponseBody($request->getHTML(), true);
}
if (!$request->success) {
    REQUEST->setFailed();
}
REQUEST->respond();
