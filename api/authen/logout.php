<?php
session_start();
$timestamp = date("Y-m-d h:i:s");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header('Content-Type: application/json;charset=utf-8');

include_once '../../config/DatabaseConfig.php';
include_once '../../model/BasicInfoModel/LoginModel.php';
include_once '../../includes/ActionCode.php';
include_once '../../includes/ErrorCode.php';
include_once '../../utils/ValidateString.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    if (!isset($data->actionCode)) {
        echo json_encode(array(
            'responseCode' => ErrorCode::UNKNOWN_ERROR,
            'message' => 'Something went wrong. Please check your data and try again.',
            'timestamp' => $timestamp
        ));
        http_response_code(200);
        exit;
    }
    switch ($data->actionCode) {
        case ActionCode::LOGOUT:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::LOGOUT) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionCode == null || $data->actionCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                if (session_destroy()) {
                    echo json_encode(array(
                        'responseCode' => "00000",
                        'message' => 'logout success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return http_response_code(200);
                } else {
                    echo json_encode(array(
                        'responseCode' => "10096",
                        'message' => 'logout fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return http_response_code(200);
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            }
            break;
        default:
            echo json_encode(array(
                'responseCode' => ErrorCode::UNKNOWN_ERROR,
                'message' => 'Something went wrong. Please check your data and try again.',
                'timestamp' => $timestamp
            ));
            http_response_code(200);
            exit;
            break;
    }
}
