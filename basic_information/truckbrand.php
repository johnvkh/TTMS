<?php
require_once '../../includes/Autoload.php';
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckBrandModel = new TruckBrandModel();
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
        case ActionCode::GET_ALL_TRUCK_BRAND:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_ALL_TRUCK_BRAND) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionCode) || $data->actionNodeId == null || $data->actionNodeId == "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $allTruckBrand = $TruckBrandModel->getAllTruckBrand($conn, null);
                $num_row = $allTruckBrand->rowCount();
                $allTruckBrand_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $allTruckBrand->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($allTruckBrand_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $allTruckBrand_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                } else {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_BRAND,
                        'message' => 'Not Found Data With That Code',
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
        case ActionCode::GET_TRUCK_BRAND:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != actionCode::GET_TRUCK_BRAND) {
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

                if (!isset($data->truckBrandName) || $data->truckBrandName == null || $data->truckBrandName == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandName'
                    ));
                    return;
                }
                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckBrandName = isset($data->truckBrandName) ? trim($data->truckBrandName) : null;
                $getTruckBrand = $TruckBrandModel->geTruckBrandByName($conn, $truckBrandName);

                $num_row = $getTruckBrand->rowCount();
                $truckBrand_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTruckBrand->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($truckBrand_arr['data'], $data);
                    }
                    $res = array(
                        'result' => $num_row,
                        'data' => $truckBrand_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_BRAND,
                        'message' => 'Not Found Data With That Code',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
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
        case ActionCode::CREATE_TRUCK_BRAND:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::CREATE_TRUCK_BRAND) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckBrandName) || $data->truckBrandName === null || $data->truckBrandName === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandName'
                    ));
                    return;
                }

                if (!isset($data->createBy) || $data->createBy === null || $data->createBy === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $truckBrandName = isset($data->truckBrandName) ? trim($data->truckBrandName) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getTruckBrand = $TruckBrandModel->getAllTruckBrand($conn, $truckBrandName);

                if ($getTruckBrand->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The truckBrandName Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }


                $result = $TruckBrandModel->createNewTruckBrand(
                    $conn,
                    $truckBrandName,
                    $createBy
                );

                if (!$result) {
                    $res = array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_BRAND_FAIL,
                        'message' => 'success',
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
                http_response_code(200);
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_TRUCK_BRAND:
            try {
                if (!isset($data->actionCode) || $data->actionCode === null || $data->actionCode === "" || $data->actionCode != ActionCode::UPDATE_TRUCK_BRAND) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId === null || $data->actionNodeId === "" || !ValidateString::checkNodeId($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->truckBrandName) || $data->truckBrandName === null || $data->truckBrandName === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandName'
                    ));
                    return;
                }

                if (!isset($data->updateBy) || $data->updateBy === null || $data->updateBy === "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckBrandName = isset($data->truckBrandName) ? trim($data->truckBrandName) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $checkTruckBrand = $TruckBrandModel->checkTruckBrandId($conn, $truckBrandId);

                if ($checkTruckBrand->rowCount() > 0) {
                    $updateResult = $TruckBrandModel->updateTruckBrandById(
                        $conn,
                        $truckBrandId,
                        $truckBrandName,
                        $updateBy
                    );

                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_TRUCK_BRAND_FAIL,
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

                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TRUCK_BRAND_FAIL,
                    'message' => 'The Truck Brand Id is not found in the system, Please use another code'
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
        case ActionCode::DELETE_TRUCK_BRAND:
            try {
                if (!isset($data->truckRegistrationBackPart) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_BRAND) {
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
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_TRUCK_BRAND_FAIL,
                    'message' => 'The Id is not found in the system, Please use another code'
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
