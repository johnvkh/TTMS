<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$MonthlyAdditionalCost = new MonthlyAdditionalCostModel();

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
        case ActionCode::GET_ALL_MONTHLY_ADDITIONAL_COST:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_MONTHLY_ADDITIONAL_COST) {
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

                $getAllMonthlyAdditionalCost = $MonthlyAdditionalCost->getAllMonthlyAdditionalCost($conn);
                $num_row = $getAllMonthlyAdditionalCost->rowCount();
                $getAllMonthlyAdditionalCost_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllMonthlyAdditionalCost->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "date" => $date,
                            "licensePlate" => $licensePlate,
                            "driver" => $driver,
                            "costRepairingIn" => $cost_repairing_in,
                            "costRepairingOut" => $const_repairing_out,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllMonthlyAdditionalCost_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllMonthlyAdditionalCost_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_MONTHLY_ADDITIONAL_COST,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_MONTHLY_ADDITIONAL_COST:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_MONTHLY_ADDITIONAL_COST) {
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

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getMonthlyAdditionalCost = $MonthlyAdditionalCost->getMonthlyAdditionalCost($conn, $licensePlate);
                $num_row = $getMonthlyAdditionalCost->rowCount();
                $getMonthlyAdditionalCost_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getMonthlyAdditionalCost->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "date" => $date,
                            "licensePlate" => $licensePlate,
                            "driver" => $driver,
                            "costRepairingIn" => $cost_repairing_in,
                            "costRepairingOut" => $const_repairing_out,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getMonthlyAdditionalCost_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getMonthlyAdditionalCost_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
                        'message' => 'Not Found Data With That Code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_MONTHLY_ADDITIONAL_COST:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_MONTHLY_ADDITIONAL_COST) {
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

                if (trim(!isset($data->date)) || trim($data->date) == null || trim($data->date) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->date)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start contact date'
                        ));
                        return;
                    }
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (trim(!isset($data->driver)) || trim($data->driver) == null || trim($data->driver) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driver'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $driver = isset($data->driver) ? trim($data->driver) : null;
                $costRepairingIn = isset($data->costRepairingIn) ? trim($data->costRepairingIn) : null;
                $costRepairingOut = isset($data->costRepairingOut) ? trim($data->costRepairingOut) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getMonthlyAdditionalCost = $MonthlyAdditionalCost->getMonthlyAdditionalCost($conn, $licensePlate);

                if ($getMonthlyAdditionalCost->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The license plate is already in the system, Please use another license plate'
                    ));
                    http_response_code(200);
                    return;
                }


                $result = $MonthlyAdditionalCost->createNewMonthlyAdditionalCost($conn, $date, $licensePlate, $driver, $costRepairingIn, $costRepairingOut, $createBy);

                if ($result) {
                    $res = array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::CREATE_MONTHLY_ADDITIONAL_COST_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );

                    echo json_encode($res);
                    http_response_code(200);
                    return;
                }
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_MONTHLY_ADDITIONAL_COST:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_MONTHLY_ADDITIONAL_COST) {
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

                if (!isset($data->date) || trim($data->date) == null || trim($data->date) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid date'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->date)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start contact date'
                        ));
                        return;
                    }
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->driver) || trim($data->driver) == null || trim($data->driver) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driver'
                    ));
                    return;
                }

                if (!isset($data->updateBy) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $driver = isset($data->driver) ? trim($data->driver) : null;
                $costRepairingIn = isset($data->costRepairingIn) ? trim($data->costRepairingIn) : null;
                $costRepairingOut = isset($data->costRepairingOut) ? trim($data->costRepairingOut) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getMonthlyAdditionalCost = $MonthlyAdditionalCost->getMonthlyAdditionalCost($conn, $licensePlate);

                if ($getMonthlyAdditionalCost->rowCount() > 0) {
                    $updateResult = $MonthlyAdditionalCost->updateMonthlyAdditionalCost(
                        $conn,
                        $date,
                        $licensePlate,
                        $driver,
                        $costRepairingIn,
                        $costRepairingOut,
                        $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_MONTHLY_ADDITIONAL_COST_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        http_response_code(200);
                        echo json_encode($res);
                        return;
                    } else {
                        $res = array(
                            'responseCode' => ErrorCode::SUCCESS,
                            'message' => 'success',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        http_response_code(200);
                        echo json_encode($res);
                        return;
                    }
                }

                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_MONTHLY_ADDITIONAL_COST_FAIL,
                    'message' => 'The License Plate is not found in the system, Please use another License Plate'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_MONTHLY_ADDITIONAL_COST:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_MONTHLY_ADDITIONAL_COST) {
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
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_MONTHLY_ADDITIONAL_COST_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
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
