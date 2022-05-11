<?php

require_once '../../includes/Autoload.php';
require_once '../../utils/logger.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$TireTruckModel = new TireTruckModel();
$timestamp = date("Y-m-d h:i:s");

$className = basename(__FILE__, '.php');
$url = $_SERVER["REQUEST_URI"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    requestLogger($data, $className, $url);
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
        case ActionCode::GET_ALL_TIRE_TRUCK:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TIRE_TRUCK) {
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
                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "" || !in_array($data->whichPart, array("0", "1"))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Which Part'
                    ));
                    return;
                }
                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getAllTireTruck = $TireTruckModel->getAllTireTruck($conn, $licensePlate, $whichPart);
                $num_row = $getAllTireTruck->rowCount();
                $getAllTireTruck_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTireTruck->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireTruckId" => $tire_truck_id,
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "wheelPosition" => (int) $wheel_position,
                            "wheelPositionCode" => $wheel_position_code,
                            "tireCode" => $tire_code,
                            "changedTireLatestDate" => $changed_tire_latest_date,
                            "tireBrandId" => $tire_brand_id,
                            "tireSize" => $tire_size,
                            "reasonChangeTire" => $reason_change_tire,
                            "mileShouldChangeTire" => (int) $mile_should_change_tire,
                            "status" => $status,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTireTruck_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $getAllTireTruck_arr['data'],
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
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
                        'message' => 'data not found',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_TIRE_TRUCK_BY_POSITION:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIRE_TRUCK_BY_POSITION) {
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
                // 0 head part, 1 back part
                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Which Part'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid License Plate'
                    ));
                    return;
                }

                if (!isset($data->wheelPosition) || trim($data->wheelPosition) == null || trim($data->wheelPosition) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid wheel Position'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $wheelPosition = isset($data->wheelPosition) ? trim($data->wheelPosition) : null;

                $tireTruckByWheelPosition = $TireTruckModel->getAllTireTruckByWheelPosition($conn, $whichPart, $licensePlate, $wheelPosition);
                $num_row = $tireTruckByWheelPosition->rowCount();
                $tireTruckByWheelPosition_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $tireTruckByWheelPosition->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "tireTruckId" => $tire_truck_id,
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "wheelPosition" => (int) $wheel_position,
                            "wheelPositionCode" => $wheel_position_code,
                            "tireCode" => $tire_code,
                            "changedTireLatestDate" => $changed_tire_latest_date,
                            "tireBrandId" => $tire_brand_id,
                            "tireSize" => $tire_size,
                            "reasonChangeTire" => $reason_change_tire,
                            "mileShouldChangeTire" => (int) $mile_should_change_tire,
                            "status" => $status,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($tireTruckByWheelPosition_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $tireTruckByWheelPosition_arr['data'],
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
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
                        'message' => 'data not found',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_ALL_REPORT_TRUCK_TIRE_WILL_BE_EXPIRING:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPORT_TRUCK_TIRE_WILL_BE_EXPIRING) {
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

                if (!isset($data->mileOrDate) || trim($data->mileOrDate) == null || trim($data->mileOrDate == "")) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileOrDate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $mileOrDate = isset($data->mileOrDate) ? trim($data->mileOrDate) : null;

                if ($mileOrDate == 0) {
                    $getTruckHeadPartInfo = $TireTruckModel->reportTireWillBeExpiringMile($conn);
                    $num_row = $getTruckHeadPartInfo->rowCount();
                    $getTruckHeadPartInfo_arr['data'] = [];
                    if ($num_row > 0) {
                        while ($row = $getTruckHeadPartInfo->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            if ($which_part == 0) {
                                $part = 'ທ່ອນຫົວ';
                            } else if ($which_part == 1) {
                                $part = 'ທ່ອນຫາງ';
                            }
                            $data = array(
                                "licensePlate" => $license_plate,
                                "part" => $part,
                                "truckTypeName" => $truck_type_name,
                                "usetTireDate" => $current_date_truck_change_tire,
                                "currentMileNumber" => $current_truck_mile_number_change_tire,
                                "mileNumberShouldChangeTire" => $mile_should_change_tire,
                                "overMile" => $over_mile
                            );
                            array_push($getTruckHeadPartInfo_arr['data'], $data);
                        }
                        $res = array(
                            'result' => $num_row,
                            'data' => $getTruckHeadPartInfo_arr['data'],
                            'responseCode' => ErrorCode::SUCCESS,
                            'message' => 'success',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        echo json_encode($res);
                        http_response_code(200);
                        return;
                    }
                } else if ($mileOrDate == 1) {
                    $getTruckBackPartInfo = $TireTruckModel->reportTireWillBeExpiringDate($conn);
                    $num_row = $getTruckBackPartInfo->rowCount();
                    $getTruckBackPartInfo_arr['data'] = [];
                    if ($num_row > 0) {
                        while ($row = $getTruckBackPartInfo->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            if ($which_part == 0) {
                                $part = 'ທ່ອນຫົວ';
                            } else if ($which_part == 1) {
                                $part = 'ທ່ອນຫາງ';
                            }
                            $data = array(
                                "licensePlate" => $license_plate,
                                "part" => $part,
                                "truckTypeName" => $truck_type_name,
                                "usetTireDate" => $current_date_truck_change_tire,
                                "currentDate" => $current_date_truck_change_tire,
                                "dateShouldChangeTire" => $date_should_change_tire,
                                "overDate" => $over_date,
                            );
                            array_push($getTruckBackPartInfo_arr['data'], $data);
                        }
                        $res = array(
                            'result' => $num_row,
                            'data' => $getTruckBackPartInfo_arr['data'],
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
                            'message' => 'Not Found Data',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        echo json_encode($res);
                        http_response_code(200);
                        return;
                    }
                }
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CREATE_TIRE_TRUCK:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TIRE_TRUCK) {
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

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid which Part'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid license Plate'
                    ));
                    return;
                }

                if (!isset($data->wheelPosition) || trim($data->wheelPosition) == null || trim($data->wheelPosition) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid position Wheel'
                    ));
                    return;
                }

                if (!isset($data->wheelPositionCode) || trim($data->wheelPositionCode) == null || trim($data->wheelPositionCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid wheel Position Code'
                    ));
                    return;
                }

                if (!isset($data->tireCode) || trim($data->tireCode) == null || trim($data->tireCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Code'
                    ));
                    return;
                }
                if (!isset($data->changedTireLatestDate) || trim($data->changedTireLatestDate) == null || trim($data->changedTireLatestDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid changed tire latest date'
                    ));
                    return;
                }
                if (!isset($data->tireBrandId) || trim($data->tireBrandId) == null || trim($data->tireBrandId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire brand id'
                    ));
                    return;
                }

                if (!isset($data->tireSize) || trim($data->tireSize) == null || trim($data->tireSize) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire size'
                    ));
                    return;
                }

                if (!isset($data->mileShouldChangeTire) || trim($data->mileShouldChangeTire) == null || trim($data->mileShouldChangeTire) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mile should change tire'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $wheelPosition = isset($data->wheelPosition) ? trim($data->wheelPosition) : null;
                $wheelPositionCode = isset($data->wheelPositionCode) ? trim($data->wheelPositionCode) : null;
                $tireCode = isset($data->tireCode) ? trim($data->tireCode) : null;
                $changedTireLatestDate = isset($data->changedTireLatestDate) ? trim($data->changedTireLatestDate) : null;
                $tireBrandId = isset($data->tireBrandId) ? trim($data->tireBrandId) : null;
                $tireSize = isset($data->tireSize) ? trim($data->tireSize) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;
                $mileShouldChangeTire = isset($data->mileShouldChangeTire) ? trim($data->mileShouldChangeTire) : null;

                $checkDuplicatePositionWheel = $TireTruckModel->getTireTruck($conn, $whichPart, $licensePlate, $wheelPosition);
                if ($checkDuplicatePositionWheel->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TIRE_TRUCK_FAIL,
                        'message' => 'this license plate and position wheel already have in the system, Please use other license plate and position wheel'
                    ));
                    http_response_code(200);
                    return;
                }

                $getTireTruck = $TireTruckModel->getTireTruck($conn, $whichPart, $licensePlate, null);
                if ($getTireTruck->rowCount() > 16) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TIRE_TRUCK_FAIL,
                        'message' => 'The license plate is full in the system, Please use other license plate'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $TireTruckModel->createNewTireTruck($conn, $whichPart, $licensePlate, $wheelPosition, $wheelPositionCode, $tireCode, $changedTireLatestDate, $tireBrandId, $tireSize, $mileShouldChangeTire, $createBy);
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
                        'responseCode' => ErrorCode::CREATE_TIRE_TRUCK_FAIL,
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
        case ActionCode::UPDATE_TIRE_TRUCK:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TIRE_TRUCK) {
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

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid which Part'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid license Plate'
                    ));
                    return;
                }

                if (!isset($data->wheelPosition) || trim($data->wheelPosition) == null || trim($data->wheelPosition) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid position Wheel'
                    ));
                    return;
                }

                if (!isset($data->wheelPositionCode) || trim($data->wheelPositionCode) == null || trim($data->wheelPositionCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid wheel Position Code'
                    ));
                    return;
                }

                if (!isset($data->tireCode) || trim($data->tireCode) == null || trim($data->tireCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire Code'
                    ));
                    return;
                }
                if (!isset($data->changedTireLatestDate) || trim($data->changedTireLatestDate) == null || trim($data->changedTireLatestDate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid changed tire latest date'
                    ));
                    return;
                }
                if (!isset($data->tireBrandId) || trim($data->tireBrandId) == null || trim($data->tireBrandId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire brand id'
                    ));
                    return;
                }

                if (!isset($data->tireSize) || trim($data->tireSize) == null || trim($data->tireSize) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid tire size'
                    ));
                    return;
                }

                if (!isset($data->mileShouldChangeTire) || trim($data->mileShouldChangeTire) == null || trim($data->mileShouldChangeTire) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mile should change tire'
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
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $wheelPosition = isset($data->wheelPosition) ? trim($data->wheelPosition) : null;
                $wheelPositionCode = isset($data->wheelPositionCode) ? trim($data->wheelPositionCode) : null;
                $tireCode = isset($data->tireCode) ? trim($data->tireCode) : null;
                $changedTireLatestDate = isset($data->changedTireLatestDate) ? trim($data->changedTireLatestDate) : null;
                $tireBrandId = isset($data->tireBrandId) ? trim($data->tireBrandId) : null;
                $tireSize = isset($data->tireSize) ? trim($data->tireSize) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;
                $mileShouldChangeTire = isset($data->mileShouldChangeTire) ? trim($data->mileShouldChangeTire) : null;
                $reasonForChangeTire = isset($data->reasonForChangeTire) ? trim($data->reasonForChangeTire) : null;
                $status = isset($data->status) ? trim($data->status) : null;
                $getTireTruck = $TireTruckModel->getTireTruckForUpdate($conn, $whichPart, $licensePlate);
                if ($getTireTruck->rowCount() > 0 && $getTireTruck->rowCount()) {
                    $updateResult = $TireTruckModel->updateTireTruck(
                            $conn, $whichPart, $licensePlate, $wheelPosition, $wheelPositionCode, $tireCode, $changedTireLatestDate, $tireBrandId, $tireSize, $mileShouldChangeTire, $reasonForChangeTire, $status, $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_TIRE_TRUCK_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        echo json_encode($res);
                        http_response_code(200);
                        return;
                    } else {
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
                    }
                } else {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_TIRE_TRUCK_FAIL,
                        'message' => 'not found data in the system, please check part of the truck, license plate, position wheel for sure, and try again.'
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
        case ActionCode::CHANGE_TIRE_TRUCK:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIRE_TRUCK) {
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

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Which Part'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid License Plate'
                    ));
                    return;
                }

                if (!isset($data->wheelPosition) || trim($data->wheelPosition) == null || trim($data->wheelPosition) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid wheel Position'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $licensePlate = isset($data->licensePlate) ? $data->licensePlate : null;
                $wheelPosition = isset($data->wheelPosition) ? $data->wheelPosition : null;
                $whichPart = isset($data->whichPart) ? $data->whichPart : null;

                $checklicensePlateExists = $TireTruckModel->checkPositionWheel($conn, $licensePlate, $wheelPosition, $whichPart);
                if ($checklicensePlateExists->rowCount() > 0) {
                    $result = $TireTruckModel->deleteTireTruck($conn, $licensePlate, $wheelPosition, $whichPart);
                    if ($result) {
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
                        'responseCode' => ErrorCode::DELETE_TIRE_TRUCK_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
                    'message' => 'not found license plate and position wheel in the system, please use other license plate and postion wheeel'
                ));
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::CHANGE_TIRE_TRUCK:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CHANGE_TIRE_TRUCK) {
                    $res = array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    );
                    echo json_encode($res);
                    responseLogger($res, $className, $url);
                    return;
                }

                if ($data->actionCode == null || $data->actionCode == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    $res = array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    );
                    echo json_encode($res);
                    responseLogger($res, $className, $url);
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    $res = array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Which Part'
                    );
                    echo json_encode($res);
                    responseLogger($res, $className, $url);
                    return;
                }

                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    $res = array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid License Plate'
                    );
                    echo json_encode($res);
                    responseLogger($res, $className, $url);
                    return;
                }

                if (!isset($data->wheelPosition) || trim($data->wheelPosition) == null || trim($data->wheelPosition) == "") {
                    $res = array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid wheel Position'
                    );
                    echo json_encode($res);
                    responseLogger($res, $className, $url);
                    return;
                }

                $actionCode = isset($data->actionCode) ? $data->actionCode : null;
                $actionNodeId = isset($data->actionNodeId) ? $data->actionNodeId : null;
                $licensePlate = isset($data->licensePlate) ? $data->licensePlate : null;
                $wheelPosition = isset($data->wheelPosition) ? $data->wheelPosition : null;
                $whichPart = isset($data->whichPart) ? $data->whichPart : null;

                $checklicensePlateExists = $TireTruckModel->checkPositionWheel($conn, $licensePlate, $wheelPosition, $whichPart);
                if ($checklicensePlateExists->rowCount() > 0) {
                    $result = $TireTruckModel->deleteTireTruck($conn, $licensePlate, $wheelPosition, $whichPart);
                    if ($result) {
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
                        'responseCode' => ErrorCode::DELETE_TIRE_TRUCK_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TIRE_TRUCK,
                    'message' => 'not found license plate and position wheel in the system, please use other license plate and postion wheeel'
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
