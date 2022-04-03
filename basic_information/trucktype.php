<?php

require_once '../../includes/Autoload.php';

$db = new DatabaseConfig();
$conn = $db->connection();
$TruckTypeModel = new TruckTypeModel();
$timestamp = date("Y-m-d h:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    switch ($data->actionCode) {
        case ActionCode::GET_ALL_TRUCK_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRUCK_TYPE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $get_truck_type = $TruckTypeModel->getAllTruckType($conn);
                $num_row = $get_truck_type->rowCount();
                $truck_type_array = [];
                $truck_type_array['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_truck_type->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $truck_type_item = array(
                            'truckTypeId' => $truck_type_id,
                            'truckTypeCode' => $truck_type_code,
                            'truckTypeName' => $truck_type_name,
                            'tireLifeKm' => (int) $tire_life_km,
                            'tireLifeDay' => $tire_life_day,
                            'numberOfWheels' => $number_of_wheels,
                            'createBy' => $create_by,
                            'createDate' => $create_date,
                            'updateBy' => $update_by,
                            'updateDate' => $update_date
                        );
                        array_push($truck_type_array['data'], $truck_type_item);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $truck_type_array['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_TYPE,
                        'message' => 'Not found truck type',
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_TRUCK_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TRUCK_TYPE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckTypeCode) || $data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckTypeCode = isset($data->truckTypeCode) ? trim($data->truckTypeCode) : null;
                $get_truck_type = $TruckTypeModel->getTruckTypeByTruckCode($conn, $truckTypeCode);
                $num_row = $get_truck_type->rowCount();
                $truck_type_array = [];
                $truck_type_array['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_truck_type->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $truck_type_item = array(
                            'truckTypeId' => $truck_type_id,
                            'truckTypeCode' => $truck_type_code,
                            'truckTypeName' => $truck_type_name,
                            'tireLifeKm' => (int) $tire_life_km,
                            'tireLifeDay' => $tire_life_day,
                            'numberOfWheels' => $number_of_wheels,
                            'createBy' => $create_by,
                            'createDate' => $create_date,
                            'updateBy' => $update_by,
                            'updateDate' => $update_date
                        );
                        array_push($truck_type_array['data'], $truck_type_item);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $truck_type_array['data'],
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
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_TYPE,
                        'message' => 'The Truck Code is not found in the system, Please use another code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
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
        case ActionCode::CREATE_TRUCK_TYPE:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRUCK_TYPE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckTypeCode) || $data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));
                    return;
                }

                if ($data->truckTypeName == null || $data->truckTypeName == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truck Type Name'
                    ));
                    return;
                }

                if ($data->tireLifeKm == null || $data->tireLifeKm <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Life Km'
                    ));
                    return;
                }

                if ($data->tireLifeDay == null || $data->tireLifeDay <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Life Day'
                    ));
                    return;
                }
                if ($data->countWheels == null || $data->countWheels <= 0 || $data->countWheels > 150) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid count Wheels'
                    ));
                    return;
                }

                if (!isset($data->createBy) || $data->createBy == null || $data->createBy == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $truckTypeCode = isset($data->truckTypeCode) ? $data->truckTypeCode : null;
                $truckTypeName = isset($data->truckTypeName) ? $data->truckTypeName : null;
                $tireLifeKm = isset($data->tireLifeKm) ? $data->tireLifeKm : null;
                $tireLifeDay = isset($data->tireLifeDay) ? $data->tireLifeDay : null;
                $countWheels = isset($data->countWheels) ? $data->countWheels : null;
                $createBy = isset($data->createBy) ? $data->createBy : null;

                $checkTruckTypeCodeExist = $TruckTypeModel->getTruckTypeByTruckCode($conn, $truckTypeCode);
                if ($checkTruckTypeCodeExist->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The Truck Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }
                $result = $TruckTypeModel->createNewTruckTypeCode($conn, $actionNodeId, $truckTypeCode, $truckTypeName, $tireLifeKm, $tireLifeDay, $countWheels, $createBy);
                if (!$result) {
                    $res = array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                }
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
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;

        case ActionCode::UPDATE_TRUCK_TYPE:
            try {
                $data = json_decode(file_get_contents('php://input'));
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRUCK_TYPE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if ($data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));
                    http_response_code(200);
                    return;
                }

                if ($data->truckTypeName == null || $data->truckTypeName == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truck Type Name'
                    ));
                    return;
                }

                if ($data->tireLifeKm == null || $data->tireLifeKm <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Life Km'
                    ));

                    return;
                }

                if ($data->tireLifeDay == null || $data->tireLifeDay <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Life Day'
                    ));

                    return;
                }
                if ($data->countWheels == null || $data->countWheels <= 0 || $data->countWheels >= 150) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid count Wheels'
                    ));

                    return;
                }

                if (!isset($data->updateBy) || $data->updateBy == null || $data->updateBy == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $truckTypeCode = isset($data->truckTypeCode) ? $data->truckTypeCode : null;
                $truckTypeName = isset($data->truckTypeName) ? $data->truckTypeName : null;
                $tireLifeKm = isset($data->tireLifeKm) ? $data->tireLifeKm : null;
                $tireLifeDay = isset($data->tireLifeDay) ? $data->tireLifeDay : null;
                $countWheels = isset($data->countWheels) ? $data->countWheels : null;
                $updateBy = isset($data->updateBy) ? $data->updateBy : null;

                $checkTruckTypeCodeExist = $TruckTypeModel->getTruckTypeByTruckCode($conn, $truckTypeCode);
                if ($checkTruckTypeCodeExist->rowCount() <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_TYPE,
                        'message' => 'The Truck Code is not found in the system, Please use another code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    http_response_code(200);
                    return;
                }
                $result = $TruckTypeModel->updateTruckTypeByTruckTypeCode($conn, $actionNodeId, $truckTypeCode, $truckTypeName, $tireLifeKm, $tireLifeDay, $countWheels, $updateBy);
                if (!$result) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_TRUCK_TYPE_FAIL,
                        'message' => 'update fail, please check your data and try again.',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    http_response_code(200);
                    return;
                }
                echo json_encode(array(
                    'responseCode' => ErrorCode::SUCCESS,
                    'message' => 'update successfully.',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
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
        case ActionCode::DELETE_TRUCK_TYPE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_TYPE) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }
                if ($data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));

                    return;
                }
                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $truckTypeCode = isset($data->truckTypeCode) ? $data->truckTypeCode : null;
                $checkTruckTypeCodeExist = $TruckTypeModel->getTruckTypeByTruckCode($conn, $truckTypeCode);
                if ($checkTruckTypeCodeExist->rowCount() <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_TYPE,
                        'message' => 'The Truck Code is not found in the system, Please use another code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    http_response_code(200);
                    return;
                }
                $delete_truck_type = $TruckTypeModel->deleteTruckType($conn, $truckTypeCode);
                if (!$delete_truck_type) {
                    $res = array(
                        'responseCode' => ErrorCode::DELETE_TRUCK_TYPE_FAIL,
                        'message' => 'Delete Fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    http_response_code(200);

                    return;
                }
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
    echo json_encode(array(
        'responseCode' => ErrorCode::UNKNOWN_ERROR,
        'message' => 'Something went wrong. Please check your data and try again.',
        'timestamp' => $timestamp
    ));
    http_response_code(200);
    exit;
}
