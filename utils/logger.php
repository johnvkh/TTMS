<?php

function requestLogger($req, $className, $requestURL) {
    $log = date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . $requestURL . "\n";
    $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "-----------REQUEST FROM CLIENT " . $_SERVER["REQUEST_METHOD"] . "-----------\n";
    foreach ($req as $key => $value) {
        $myKey = ucwords(str_replace('_', ' ', $key));
        if ($myKey == "LstRequest") {
            $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "  " . $myKey . " : " . json_encode($value) . "\n";
        } else {
            $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "  " . $myKey . " : " . $value . "\n";
        }
    }
    $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "-----------------------------------------\n";
    file_put_contents('../../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function responseLogger($res, $className, $requestURL) {
    $log = date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . $requestURL . "\n";
    $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "-----------RESPONSE TO CLIENT -----------\n";
    foreach ($res as $key => $value) {
        //ucwords   # strtoupper
        $myKey = ucwords(str_replace('_', ' ', $key));
        if ($myKey == "Data") {
            $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "  " . $myKey . " : " . json_encode($value) . "\n";
        } else {
            $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "  " . $myKey . " : " . $value . "\n";
        }
    }
    $log .= date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . "-----------------------------------------\n";
    file_put_contents('../../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function infoUpload($className, $content) {
    $log = date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . $content . "\n";
    file_put_contents('../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function info($className, $content) {
    $log = date("Y/m/d H:i:s") . " [ INFO ]" . "[" . $className . "]" . " - " . $content . "\n";
    file_put_contents('../../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function errorUpload($className, $content) {
    $log = date("Y/m/d H:i:s") . " [ ERROR ]" . "[" . $className . "]" . " - " . $content . "\n";
    file_put_contents('../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function error($className, $content) {
    $log = date("Y/m/d H:i:s") . " [ ERROR ]" . "[" . $className . "]" . " - " . $content . "\n";
    file_put_contents('../../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

function warn($className, $content) {
    $log = date("Y/m/d H:i:s") . " [ WARN ]" . "[" . $className . "]" . " - " . $content . "\n";
    file_put_contents('../../logs/full_' . date("Y.m.d") . '.log', $log, FILE_APPEND);
    return true;
}

?>