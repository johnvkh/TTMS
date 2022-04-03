<?php
session_start();
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header('Content-Type: application/json;charset=utf-8');

include_once '../../config/DatabaseConfig.php';
include_once '../../model/BasicInfoModel/LoginModel.php';
include_once '../../includes/ActionCode.php';
include_once '../../includes/ErrorCode.php';
include_once '../../utils/ValidateString.php';
include_once '../../utils/ValidateDate.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$loginModel = new LoginModel();
$timestamp = date("Y-m-d h:i:s");

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
        case ActionCode::LOGIN:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode !== actionCode::LOGIN) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid action code'
                    ));
                    return http_response_code(200);
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid action node'
                    ));
                    return http_response_code(200);
                }

                if (!isset($data->staffCode) || empty($data->staffCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'please enter your username.'
                    ));
                    return http_response_code(200);
                }

                if (!isset($data->password) || empty($data->password)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'please enter your password.'
                    ));
                    return http_response_code(200);
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $password = isset($data->password) ? trim($data->password) : null;
                $result_login = $loginModel->login($conn, $staffCode);
                $account_array['data']=[];
                $num = $result_login->rowCount();
                if ($num > 0) {
                    $row = $result_login->fetch(PDO::FETCH_ASSOC);
                    if ($row['STATUS'] == '2') {
                        echo json_encode(array(
                            'responceCode' => ErrorCode::ACCOUNT_LOCK,
                            'message' => "account is lock",
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        ));
                        return http_response_code(200);
                    }
                    if ($row['STATUS'] == '3') {
                        echo json_encode(array(
                            'responceCode' => ErrorCode::ACCOUNT_CANCEL,
                            'message' => "account is cancel",
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        ));
                        return http_response_code(200);
                    }
                    if ($password == $row['PASSWORD']) {
                        $_SESSION['loggedIn'] = true;
                        $loginModel->deleteLoginlog($conn, $staffCode);
                        $account = array(
                            "staffId" => $row['STAFF_ID'],
                            'staffCode' => $row['STAFF_CODE'],
                            'fullName' => $row['FULL_NAME'],
                            'email' => $row['EMAIL'],
                            'msisdn' => $row['MSISDN'],
                            "roleId" => $row['ROLE_ID'],
                            'status' => $row['STATUS'],
                            'gender' => $row['GENDER'],
                            'birthDay' => $row['BIRTH_DAY'],
                            'address' => $row['ADDRESS'],
                            'createDate' => $row['CREATED_DATE'],
                            'createBy' => $row['CREATED_BY'],
                            'departmentId' => $row['DEPARTMENT_ID'],
                            'branchId' => $row['BRANCH_ID'],
                            'modifiedDate' => $row['MODIFIED_DATE'],
                            'modified' => $row['MODIFIED_BY']
                        );
                        array_push($account_array['data'], $account);
                        echo json_encode(array(
                            'info' => $account_array['data'],
                            'responceCode' => '0000',
                            'message' => 'login succesfull',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        ));
                    } else {
                        echo json_encode(array(
                            'responceCode' => ErrorCode::LOGIN_FAIL,
                            'message' => 'password incorrect',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        ));
                        $countLog = $loginModel->count_auth_log($conn, $staffCode);
                        if ($countLog > 2) {
                            $loginModel->lockAccount($conn, $staffCode);
                            return http_response_code(200);
                        }
                        $loginModel->insert_auth_log($conn, $staffCode, $actionCode, $actionNodeId, ErrorCode::LOGIN_FAIL, 'password incorrect', $countLog + 1);
                    }
                } else {
                    echo json_encode(array(
                        'responceCode' => ErrorCode::LOGIN_FAIL,
                        'message' => 'staff code or password incorrect, please check and try again.',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                }
                return http_response_code(200);
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        default:
            echo json_encode(array(
                'responseCode' => ErrorCode::UNKNOWN_ERROR,
                'message' => 'Something went wrong. Please check your data and try again.',
                'timestamp' => $timestamp
            ));
            http_response_code(200);
            break;
    }
} else {
    http_response_code(200);
    echo json_encode(array(
        'responseCode' => ErrorCode::UNKNOWN_ERROR,
        'message' => 'Something went wrong. Please check your data and try again.',
        'timestamp' => $timestamp
    ));
    exit;
}
