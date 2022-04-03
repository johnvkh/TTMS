<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$MileNumberChnageEngineOil = new MileNumberChnageEngineOilModel();

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
        case ActionCode::GET_ALL_MILE_NUMBER_CHANGE_ENGINE_OIL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_MILE_NUMBER_CHANGE_ENGINE_OIL) {
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

                $getAllMileNumberChnageEngineOil = $MileNumberChnageEngineOil->getAllMileNumberChnageEngineOil($conn);
                $num_row = $getAllMileNumberChnageEngineOil->rowCount();
                $getAllMileNumberChnageEngineOil_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllMileNumberChnageEngineOil->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "licensePlate" => $license_plate,
                            "truckTypeCode" => $truck_type_code,
                            "truckTypeName" => $truck_type_name,
                            "date" => $date,
                            "changeEngineOilNextTime" => (int) $km_number_change_engine_oil_next_time,
                            "changeGearOilNextTime" => (int) $km_number_change_gear_oil_next_time,
                            "changeAutjabeeNextTime" => (int) $km_number_change_autjabee_wheel_next_time,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllMileNumberChnageEngineOil_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllMileNumberChnageEngineOil_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
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
        case ActionCode::GET_MILE_NUMBER_CHANGE_ENGINE_OIL:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_MILE_NUMBER_CHANGE_ENGINE_OIL) {
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

                $getMileNumberChnageEngineOil = $MileNumberChnageEngineOil->getMileNumberChnageEngineOil($conn, $licensePlate);
                $num_row = $getMileNumberChnageEngineOil->rowCount();
                $getMileNumberChnageEngineOil_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getMileNumberChnageEngineOil->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "licensePlate" => $license_plate,
                            "truckTypeCode" => $truck_type_code,
                            "truckTypeName" => $truck_type_name,
                            "date" => $date,
                            "changeEngineOilNextTime" => (int) $km_number_change_engine_oil_next_time,
                            "changeGearOilNextTime" => (int) $km_number_change_gear_oil_next_time,
                            "changeAutjabeeNextTime" => (int) $km_number_change_autjabee_wheel_next_time,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getMileNumberChnageEngineOil_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getMileNumberChnageEngineOil_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
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
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_MILE_NUMBER_CHANGE_ENGINE_OIL:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_MILE_NUMBER_CHANGE_ENGINE_OIL) {
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

                if (!isset($data->changeEngineOilNextTime) || !is_int($data->changeEngineOilNextTime) || $data->changeEngineOilNextTime < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid changeEngineOilNextTime'
                    ));
                    return;
                }

                if (!isset($data->changeGearOilNextTime) || !is_int($data->changeGearOilNextTime) || $data->changeEngineOilNextTime < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid changeGearOilNextTime'
                    ));
                    return;
                }

                if (!isset($data->changeAutjabeeNextTime) || !is_int($data->changeAutjabeeNextTime) || $data->changeEngineOilNextTime < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid changeAutjabeeNextTime'
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
                    if (!ValidateDate::checkDateFormat($data->date)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format date'
                        ));
                        return;
                    }
                }

                if (trim(!isset($data->createBy)) || trim($data->createBy) == null || trim($data->createBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $changeEngineOilNextTime = isset($data->changeEngineOilNextTime) ? trim($data->changeEngineOilNextTime) : null;
                $changeGearOilNextTime = isset($data->changeGearOilNextTime) ? trim($data->changeGearOilNextTime) : null;
                $changeAutjabeeNextTime = isset($data->changeAutjabeeNextTime) ? trim($data->changeAutjabeeNextTime) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getMileNumberChnageEngineOil = $MileNumberChnageEngineOil->getMileNumberChnageEngineOil($conn, $licensePlate);

                if ($getMileNumberChnageEngineOil->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
                        'message' => 'The Perform Bill Code is already in the system, Please use other Perform Bill Code'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $MileNumberChnageEngineOil->createNewMileNumberChnageEngineOil($conn, $licensePlate, $date, $changeEngineOilNextTime, $changeGearOilNextTime, $changeAutjabeeNextTime, $createBy);
                if ($result) {
                    $res = array(
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
                        'responseCode' => ErrorCode::CREATE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
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
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_MILE_NUMBER_CHANGE_ENGINE_OIL:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_MILE_NUMBER_CHANGE_ENGINE_OIL) {
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

                // if (!isset($data->date) || trim($data->date) == null || trim($data->date) == "") {
                //     echo json_encode(array(
                //         'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //         'message' => 'Invalid date'
                //     ));
                //     return;
                // } else {
                //     if (!ValidateDate::checkDateFormat($data->date)) {
                //         echo json_encode(array(
                //             'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //             'message' => 'Invalid format date'
                //         ));
                //         return;
                //     }
                // }

                if (trim(!isset($data->updateBy)) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }



                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $changeEngineOilNextTime = isset($data->changeEngineOilNextTime) ? trim($data->changeEngineOilNextTime) : null;
                $changeGearOilNextTime = isset($data->changeGearOilNextTime) ? trim($data->changeGearOilNextTime) : null;
                $changeAutjabeeNextTime = isset($data->changeAutjabeeNextTime) ? trim($data->changeAutjabeeNextTime) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getMileNumberChnageEngineOil = $MileNumberChnageEngineOil->getMileNumberChnageEngineOil($conn, $licensePlate);
                if ($getMileNumberChnageEngineOil->rowCount() > 0) {
                    $result = $MileNumberChnageEngineOil->updateMileNumberChnageEngineOil($conn, $licensePlate, $date, $changeEngineOilNextTime, $changeGearOilNextTime, $changeAutjabeeNextTime, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                    } else {
                        $res = array(
                            'responseCode' => ErrorCode::SUCCESS,
                            'message' => 'success',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                    }
                    echo json_encode($res);
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
                    'message' => 'The licensePlate is not found in the system, Please use other licensePlate'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_MILE_NUMBER_CHANGE_ENGINE_OIL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_MILE_NUMBER_CHANGE_ENGINE_OIL) {
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


                $actionCode = trim($data->actionCode);
                $actionNodeId = $data->actionNodeId;

                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_MILE_NUMBER_CHANGE_ENGINE_OIL_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                http_response_code(200);
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
