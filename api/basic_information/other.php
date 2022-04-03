<?php
// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$OtherModel = new OtherModel();




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
        case ActionCode::GET_ALL_OTHER:

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
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $getAllTireTruck = $TireTruckModel->getAllTireTruck($conn);
                $num_row = $getAllTireTruck->rowCount();
                $getAllTireTruck_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTireTruck->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "positionWheel" => $position_wheel,
                            "oldTireNumber" => $old_tire_number,
                            "changeTireDate" => $change_tire_date,
                            "reasonForChange" => $reason_for_change,
                            "newTireNumber" => $new_tire_number,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTireTruck_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
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
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_OTHER,
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
        case ActionCode::GET_OTHER:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIRE_TRUCK) {
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

                if (trim(!isset($data->whichPart)) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Which Part'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid License Plate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getTireTruck = $TireTruckModel->getTireTruck($conn, $whichPart, $licensePlate);
                $num_row = $getTireTruck->rowCount();
                $getTireTruck_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTireTruck->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "positionWheel" => $position_wheel,
                            "oldTireNumber" => $old_tire_number,
                            "changeTireDate" => $change_tire_date,
                            "reasonForChange" => $reason_for_change,
                            "newTireNumber" => $new_tire_number,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTireTruck_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getTireTruck_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_OTHER,
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
        case ActionCode::CREATE_OTHER:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_OTHER) {
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
                $compensationEmployeesPerLit = isset($data->compensationEmployeesPerLit) ? trim($data->compensationEmployeesPerLit) : null;
                $socialSecurityPerMonth = isset($data->socialSecurityPerMonth) ? trim($data->socialSecurityPerMonth) : null;
                $dieselFuelPrice = isset($data->dieselFuelPrice) ? trim($data->dieselFuelPrice) : null;
                $gasCostPricePerKg = isset($data->gasCostPricePerKg) ? trim($data->gasCostPricePerKg) : null;
                $averageCarDriven = isset($data->averageCarDriven) ? trim($data->averageCarDriven) : null;
                $createBy = 'admin';

                // $getTireTruck = $TireTruckModel->getTireTruck($conn, $whichPart, $licensePlate);

                // if ($getTireTruck->rowCount() > 0) {
                //     echo json_encode(array(
                //         'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                //         'message' => 'The  Code is already in the system, Please use another code'
                //     ));
                //     http_response_code(200);
                //     return;
                // }


                $result = $OtherModel->createNewOther(
                    $conn,
                    $compensationEmployeesPerLit,
                    $socialSecurityPerMonth,
                    $dieselFuelPrice,
                    $gasCostPricePerKg,
                    $averageCarDriven,
                    $createBy
                );

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
                        'responseCode' => ErrorCode::CREATE_OTHER_FAIL,
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
        case ActionCode::UPDATE_OTHER:
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


                if (trim(!isset($data->whichPart)) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
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

                if (trim(!isset($data->changeTireDate)) || !trim($data->changeTireDate) == null || !trim($data->changeTireDate) == "") {
                    if (!$validateDate->check_date_format($data->changeTireDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid date'
                        ));
                        return;
                    }
                }



                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $positionWheel = isset($data->positionWheel) ? trim($data->positionWheel) : null;
                $oldTireNumber = isset($data->oldTireNumber) ? trim($data->oldTireNumber) : null;
                $changeTireDate = isset($data->changeTireDate) ? trim($data->changeTireDate) : null;
                $reasonForChange = isset($data->reasonForChange) ? trim($data->reasonForChange) : null;
                $newTireNumber = isset($data->newTireNumber) ? trim($data->newTireNumber) : null;
                $updateBy = 'admin';

                $getTireTruck = $TireTruckModel->getTireTruck($conn, $whichPart, $licensePlate);

                if ($getTireTruck->rowCount() > 0) {
                    $updateResult = $TireTruckModel->updateTireTruck(
                        $conn,
                        $whichPart,
                        $licensePlate,
                        $positionWheel,
                        $oldTireNumber,
                        $changeTireDate,
                        $reasonForChange,
                        $newTireNumber,
                        $updateBy
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
                }

                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TIRE_TRUCK_FAIL,
                    'message' => 'The Code is not found in the system, Please use another code'
                ));
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_OTHER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_OTHER) {
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
                    'responseCode' => ErrorCode::DELETE_OTHER_FAIL,
                    'message' => 'This route is not yet define .'
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
