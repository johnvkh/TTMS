<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$Battery = new BatteryModel();

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
        case ActionCode::GET_ALL_BATTERY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_BATTERY) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $get_all_battery = $Battery->getAllBattery($conn);
                $num_row = $get_all_battery->rowCount();
                $get_all_battery_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_all_battery->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id"=>$id,
                            "batteryName" => $battery_name,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_all_battery_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'result' => $num_row,
                        'data' => $get_all_battery_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_BATTERY,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_BATTERY_BY_NAME:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_BATTERY_BY_NAME) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->batteryName) || empty($data->batteryName)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryName'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $batteryName = isset($data->batteryName) ? trim($data->batteryName) : null;

                $get_battery = $Battery->getBatteryByName($conn, $batteryName);
                $num_row = $get_battery->rowCount();
                $get_battery_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_battery->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id"=>$id,
                            "batteryName" => $battery_name,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_battery_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'result' => $num_row,
                        'data' => $get_battery_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_BATTERY,
                    'message' => 'data not found',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_BATTERY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_BATTERY) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->batteryName) || empty($data->batteryName)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryName'
                    ));
                    return;
                }

                if (!isset($data->createBy) || empty($data->createBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $batteryName = isset($data->batteryName) ? trim($data->batteryName) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_battery_name = $Battery->checkBatteryName($conn, $batteryName);
                if ($check_battery_name) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_BATTERY_FAIL,
                        'message' => 'this battery name is already has in the system, please use other name.'
                    ));
                    return;
                }

                $result_create = $Battery->createNewBattery($conn, $batteryName, $createBy);
                if ($result_create) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::CREATE_BATTERY_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::UPDATE_BATTERY:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_BATTERY) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->batteryId) || empty($data->batteryId) || !is_numeric($data->batteryId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryId'
                    ));
                    return;
                }

                if (!isset($data->batteryName) || empty($data->batteryName)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryName'
                    ));
                    return;
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $batteryId = isset($data->batteryId) ? trim($data->batteryId) : null;
                $batteryName = isset($data->batteryName) ? trim($data->batteryName) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_id = $Battery->checkBatteryId($conn, $batteryId);
                if (!$check_id) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_BATTERY,
                        'message' => 'not found id in the system, please use other id.'
                    ));
                    return;
                }

                $check_battery_name = $Battery->checkBatteryName($conn, $batteryName);
                if ($check_battery_name) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_BATTERY,
                        'message' => 'battery name is already has in the system, please use other name.'
                    ));
                    return;
                }

                $result_update = $Battery->updateBattery($conn, $batteryId, $batteryName, $updateBy);
                if ($result_update) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_COLOR_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::DELETE_BATTERY:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_BATTERY) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionCode == null || $data->actionCode == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }
                
                if (!isset($data->batteryId) || empty($data->batteryId) || !is_numeric($data->batteryId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid batteryId'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $batteryId = isset($data->batteryId) ? trim($data->batteryId) : null;

                $result_delete = $Battery->deleteBattery($conn, $batteryId);
                if ($result_delete) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_BATTERY_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
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
            exit;
            break;
    }
} else {
    echo json_encode(array(
        'responseCode' => ErrorCode::UNKNOWN_ERROR,
        'message' => 'Something went wrong. Please check your data and try again.',
        'timestamp' => $timestamp
    ));
    http_response_code(200);
    exit;
}
