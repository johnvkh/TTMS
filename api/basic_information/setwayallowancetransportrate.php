<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$db = new DatabaseConfig();
$conn = $db->connection();
$setWayAllowanceTransportRate = new SetWayAllowanceTransportRateModel();

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
        case ActionCode::GET_ALL_SET_WAY_ALLOWANCE_TRANSPORT_RATE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_SET_WAY_ALLOWANCE_TRANSPORT_RATE) {
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

                $getAllSetWayAllowanceTransportRate = $setWayAllowanceTransportRate->getAllSetWayAllowanceTransportRate($conn);
                $num_row = $getAllSetWayAllowanceTransportRate->rowCount();
                $getAllSetWayAllowanceTransportRate_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllSetWayAllowanceTransportRate->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "wayCode" => $way_code,
                            "start" => $start,
                            "end" => $end,
                            "distance" => $distance,
                            "shippingServicePerTon" => $shippingService_per_ton,
                            "shippingCostsPerTrip" => $shipping_costs_per_trip,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllSetWayAllowanceTransportRate_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllSetWayAllowanceTransportRate_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_SET_WAY_ALLOWANCE_TRANSPORT_RATE,
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
        case ActionCode::GET_SET_WAY_ALLOWANCE_TRANSPORT_RATE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_SET_WAY_ALLOWANCE_TRANSPORT_RATE) {
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

                if (trim(!isset($data->wayCode)) || trim($data->wayCode) == null || trim($data->wayCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Way Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $wayCode = isset($data->wayCode) ? trim($data->wayCode) : null;

                $setWayAllowanceTransportRate = $setWayAllowanceTransportRate->getSetWayAllowanceTransportRateByWayCode($conn, $wayCode);
                $num_row = $setWayAllowanceTransportRate->rowCount();
                $setWayAllowanceTransportRate_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $setWayAllowanceTransportRate->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "wayCode" => $way_code,
                            "start" => $start,
                            "end" => $end,
                            "distance" => $distance,
                            "shippingServicePerTon" => $shippingService_per_ton,
                            "shippingCostsPerTrip" => $shipping_costs_per_trip,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($setWayAllowanceTransportRate_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $setWayAllowanceTransportRate_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_SET_WAY_ALLOWANCE_TRANSPORT_RATE,
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
        case ActionCode::CREATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE) {
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

                if (trim(!isset($data->wayCode)) || trim($data->wayCode) == null || trim($data->wayCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Way Code'
                    ));
                    return;
                }
                if (trim(!isset($data->startPoint)) || trim($data->startPoint) == null || trim($data->startPoint) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid start Point'
                    ));
                    return;
                }
                if (trim(!isset($data->endPoint)) || trim($data->endPoint) == null || trim($data->endPoint) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid end Point'
                    ));
                    return;
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
                $wayCode = isset($data->wayCode) ? trim($data->wayCode) : null;
                $startPoint = isset($data->startPoint) ? trim($data->startPoint) : null;
                $endPoint = isset($data->endPoint) ? trim($data->endPoint) : null;
                $distance = isset($data->distance) ? trim($data->distance) : null;
                $shippingServicePerTon = isset($data->shippingServicePerTon) ? trim($data->shippingServicePerTon) : null;
                $shippingCostsPerTrip = isset($data->shippingCostsPerTrip) ? trim($data->shippingCostsPerTrip) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;
                $getOnesetWayAllowanceTransportRate = $setWayAllowanceTransportRate->getSetWayAllowanceTransportRateByWayCode($conn, $wayCode);
                if ($getOnesetWayAllowanceTransportRate->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The Way Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $setWayAllowanceTransportRate->createNewSetWayAllowanceTransportRate(
                        $conn, $wayCode, $startPoint, $endPoint, $distance, $shippingServicePerTon, $shippingCostsPerTrip, $createBy
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
                        'responseCode' => ErrorCode::CREATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE_FAIL,
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
        case ActionCode::UPDATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE) {
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

                if (trim(!isset($data->wayCode)) || trim($data->wayCode) == null || trim($data->wayCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Way Code'
                    ));
                    return;
                }
                if (trim(!isset($data->startPoint)) || trim($data->startPoint) == null || trim($data->startPoint) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid start Point'
                    ));
                    return;
                }
                if (trim(!isset($data->endPoint)) || trim($data->endPoint) == null || trim($data->endPoint) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid end Point'
                    ));
                    return;
                }
                if (trim(!isset($data->updateBy)) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid end Point'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $wayCode = isset($data->wayCode) ? trim($data->wayCode) : null;
                $startPoint = isset($data->startPoint) ? trim($data->startPoint) : null;
                $endPoint = isset($data->endPoint) ? trim($data->endPoint) : null;
                $distance = isset($data->distance) ? trim($data->distance) : null;
                $shippingServicePerTon = isset($data->shippingServicePerTon) ? trim($data->shippingServicePerTon) : null;
                $shippingCostsPerTrip = isset($data->shippingCostsPerTrip) ? trim($data->shippingCostsPerTrip) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;
                $getSetWayAllowanceTransportRate = $setWayAllowanceTransportRate->getSetWayAllowanceTransportRateByWayCode($conn, $wayCode);
                if ($getSetWayAllowanceTransportRate->rowCount() > 0) {
                    $updateResult = $setWayAllowanceTransportRate->updateSetWayAllowanceTransportRate(
                            $conn, $wayCode, $startPoint, $endPoint, $distance, $shippingServicePerTon, $shippingCostsPerTrip, $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE_FAIL,
                            'message' => 'fail',
                            'timestamp' => $timestamp,
                            'actionCode' => $actionCode,
                            'actionNodeId' => $actionNodeId
                        );
                        echo json_encode($res);
                        http_response_code(417);
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
                    'responseCode' => ErrorCode::UPDATE_SET_WAY_ALLOWANCE_TRANSPORT_RATE_FAIL,
                    'message' => 'The Way Code is not found in the system, Please use another code'
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
        case ActionCode::DELETE_DELIVERY_LOCATION:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_DELIVERY_LOCATION) {
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
                    'responseCode' => ErrorCode::DELETE_DELIVERY_LOCATION_FAIL,
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
