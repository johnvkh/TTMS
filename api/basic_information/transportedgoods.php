<?php
// error_reporting(0);
require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$db = new DatabaseConfig();
$conn = $db->connection();
$TransportedGoodsModel = new TransportedGoodsModel();

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
        case ActionCode::GET_ALL_TRANSPORTED_GOODS:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRANSPORTED_GOODS) {
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

                $getAllTransportedGoodsModel = $TransportedGoodsModel->getAllTransportedGoodsModel($conn);
                $num_row = $getAllTransportedGoodsModel->rowCount();
                $getAllTransportedGoodsModel_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTransportedGoodsModel->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id"=>$id,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "shortName" => $short_name,
                            "productUnit" => $product_unit,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTransportedGoodsModel_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllTransportedGoodsModel_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TRANSPORTED_GOODS,
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
        case ActionCode::GET_TRANSPORTED_GOODS:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TRANSPORTED_GOODS) {
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

                if (trim(!isset($data->productCode)) || trim($data->productCode) == null || trim($data->productCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;

                $getTransportedGoods = $TransportedGoodsModel->getTransportedGoodsModel($conn, $productCode);
                $num_row = $getTransportedGoods->rowCount();
                $getTransportedGoods_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTransportedGoods->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                             "id"=>$id,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "shortName" => $short_name,
                            "productUnit" => $product_unit,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTransportedGoods_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getTransportedGoods_arr['data'],
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
        case ActionCode::CREATE_TRANSPORTED_GOODS:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRANSPORTED_GOODS) {
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

                if (trim(!isset($data->productCode)) || trim($data->productCode) == null || trim($data->productCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product code'
                    ));
                    return;
                }
                if (trim(!isset($data->productName)) || trim($data->productName) == null || trim($data->productName) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product Name'
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
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $productName = isset($data->productName) ? trim($data->productName) : null;
                $shortName = isset($data->shortName) ? trim($data->shortName) : null;
                $productUnit = isset($data->productUnit) ? trim($data->productUnit) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getTransportedGoods = $TransportedGoodsModel->getTransportedGoodsModel($conn, $productCode);

                if ($getTransportedGoods->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The Product Code is already in the system, Please use another code'
                    ));
                    http_response_code(200); //Expectation Failed
                    return;
                }

                $result = $TransportedGoodsModel->createNewTransportedGoodsModel(
                    $conn,
                    $productCode,
                    $productName,
                    $shortName,
                    $productUnit,
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
                        'responseCode' => ErrorCode::CREATE_TRANSPORTED_GOODS_FAIL,
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
        case ActionCode::UPDATE_TRANSPORTED_GOODS:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRANSPORTED_GOODS) {
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

                if (trim(!isset($data->productCode)) || trim($data->productCode) == null || trim($data->productCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product code'
                    ));
                    return;
                }
                if (trim(!isset($data->productName)) || trim($data->productName) == null || trim($data->productName) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product Name'
                    ));
                    return;
                }
                if (trim(!isset($data->updateBy)) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $productName = isset($data->productName) ? trim($data->productName) : null;
                $shortName = isset($data->shortName) ? trim($data->shortName) : null;
                $productUnit = isset($data->productUnit) ? trim($data->productUnit) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getTransportedGoods = $TransportedGoodsModel->getTransportedGoodsModel($conn, $productCode);
                if ($getTransportedGoods->rowCount() > 0) {
                    $updateResult = $TransportedGoodsModel->updateTransportedGoodsModel(
                        $conn,
                        $productCode,
                        $productName,
                        $shortName,
                        $productUnit,
                        $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_TRANSPORTED_GOODS_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_TRANSPORTED_GOODS_FAIL,
                    'message' => 'The Way Code is not found in the system, Please use another code'
                ));
                http_response_code(417); //Expectation Failed
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_DELIVERY_LOCATION:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_DELIVERY_LOCATION) {
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
                    'responseCode' => ErrorCode::DELETE_DELIVERY_LOCATION_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(417); //Expectation Failed
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
