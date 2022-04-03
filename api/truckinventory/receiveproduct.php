<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$ReceiveProduct = new ReceiveProductModel();

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
        case ActionCode::GET_ALL_TIS_RECEIVE_PRODUCT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TIS_RECEIVE_PRODUCT) {
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

                if (isset($data->fromDoumentNo) && !empty($data->fromDoumentNo)) {
                    if (!is_numeric($data->fromDoumentNo)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromDoumentNo'
                        ));
                        return;
                    }
                }

                if (isset($data->toDocumentNo) && !empty($data->toDocumentNo)) {
                    if (!is_numeric($data->fromDoumentNo)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid toDocumentNo'
                        ));
                        return;
                    }
                }

                if (isset($data->fromDate) && !empty($data->fromDate)) {
                    if (!ValidateDate::checkDateFormat($data->fromDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid fromDate'
                        ));
                        return;
                    }
                }

                if (isset($data->toDate) && !empty($data->toDate)) {
                    if (!ValidateDate::checkDateFormat($data->toDate)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid toDate'
                        ));
                        return;
                    }
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $fromDoumentNo = isset($data->fromDoumentNo) ? trim($data->fromDoumentNo) : null;
                $toDocumentNo = isset($data->toDocumentNo) ? trim($data->toDocumentNo) : null;
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;


                $get_all_receive_product = $ReceiveProduct->getAllReceiveProduct($conn, $fromDoumentNo, $toDocumentNo, $fromDate, $toDate);
                $num_row = $get_all_receive_product->rowCount();
                $get_all_receive_product_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_all_receive_product->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "no" => $no,
                            "documentNo" => $document_no,
                            "documentDate" => $document_date,
                            "note" => $note,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "unit" => $unit_id,
                            "unitQty" => $qty_per_unit,
                            "unitCost" => $unit_cost,
                            "price" => $price,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_all_receive_product_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_all_receive_product_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_TIS_RECEIVE_PRODUCT,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_TIS_RECEIVE_PRODUCT:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIS_RECEIVE_PRODUCT) {
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

                if (!isset($data->documentNo) || empty($data->documentNo)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid document no'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $documentNo = isset($data->documentNo) ? trim($data->documentNo) : null;

                $get_receive_product = $ReceiveProduct->getReceiveProduct($conn, $documentNo);
                $num_row = $get_receive_product->rowCount();
                $get_receive_product_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_receive_product->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "no" => $no,
                            "documentNo" => $document_no,
                            "documentDate" => $document_date,
                            "note" => $note,
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "unit" => $unit,
                            "unitQty" => $qty_per_unit,
                            "unitCost" => $unit_cost,
                            "price" => $price,
                            "totalPrice" => $total_price,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_receive_product_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_receive_product_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_TIS_PRODUCT_TYPE,
                    'message' => 'Not Found Data',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;

        case ActionCode::CREATE_TIS_RECEIVE_PRODUCT:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TIS_RECEIVE_PRODUCT) {
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

                if (!isset($data->no) || empty($data->no)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid no'
                    ));
                    return;
                }
                if (!isset($data->documentNo) || empty($data->documentNo)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid documentNo'
                    ));
                    return;
                }
                if (!isset($data->documentDate) || empty($data->documentDate) || !ValidateDate::checkDateFormat($data->documentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid documentDate'
                    ));
                    return;
                }
                if (!isset($data->note) || empty($data->note)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid note'
                    ));
                    return;
                }

                if (!isset($data->productDetail) || !is_array($data->productDetail) || empty($data->productDetail)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid productDetail'
                    ));
                    return;
                }

                if (!empty($data->productDetail)) {
                    $pd = $data->productDetail;
                    for ($i = 0; $i < count($pd); $i++) {
                        if (!property_exists($pd[$i], "productCode") || empty($pd[$i]->productCode)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid productCode'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "productName") || empty($pd[$i]->productName)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid productName'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitId") || empty($pd[$i]->unitId) || !is_numeric($pd[$i]->unitId)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitId'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitQty") || empty($pd[$i]->unitQty) || !is_numeric($pd[$i]->unitQty)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitQty'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitCost") || empty($pd[$i]->unitCost) || !is_numeric($pd[$i]->unitCost)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitCost'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "price") || empty($pd[$i]->price) || !is_numeric($pd[$i]->price)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid price'
                            ));
                            return;
                        }
                    }
                }

                if (!isset($data->totalPrice) || empty($data->totalPrice)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalPrice'
                    ));
                    return;
                }

                if (!isset($data->createBy) || empty($data->createBy)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid create by'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $no = isset($data->no) ? trim($data->no) : null;
                $documentNo = isset($data->documentNo) ? trim($data->documentNo) : null;
                $documentDate = isset($data->documentDate) ? trim($data->documentDate) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $productDetail = isset($data->productDetail) ? $data->productDetail : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_data_exists = $ReceiveProduct->getReceiveProduct($conn, $documentNo);
                if ($check_data_exists->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TIS_PRODUCT_FAIL,
                        'message' => 'document no is exist.'
                    ));
                    return;
                }


                $conn->beginTransaction();
                $result_create = $ReceiveProduct->createReceiveProduct(
                    $conn,
                    $no,
                    $documentNo,
                    $documentDate,
                    $note,
                    $productDetail,
                    $totalPrice,
                    $createBy
                );
                if ($result_create) {
                    $conn->commit();
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
                $conn->rollBack();
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::CREATE_TIS_RECEIVE_PRODUCT_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_TIS_RECEIVE_PRODUCT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TIS_RECEIVE_PRODUCT) {
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

                if (!isset($data->no) || empty($data->no)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid no'
                    ));
                    return;
                }
                if (!isset($data->documentNo) || empty($data->documentNo)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid documentNo'
                    ));
                    return;
                }
                if (!isset($data->documentDate) || empty($data->documentDate) || !ValidateDate::checkDateFormat($data->documentDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid documentDate'
                    ));
                    return;
                }
                if (!isset($data->note) || empty($data->note)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid note'
                    ));
                    return;
                }

                if (!isset($data->productDetail) || !is_array($data->productDetail) || empty($data->productDetail)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid productDetail'
                    ));
                    return;
                }

                if (!empty($data->productDetail)) {
                    $pd = $data->productDetail;
                    for ($i = 0; $i < count($pd); $i++) {
                        if (!property_exists($pd[$i], "productCode") || empty($pd[$i]->productCode)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid productCode'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "productName") || empty($pd[$i]->productName)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid productName'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitId") || empty($pd[$i]->unitId) || !is_numeric($pd[$i]->unitId)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitId'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitQty") || empty($pd[$i]->unitQty) || !is_numeric($pd[$i]->unitQty)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitQty'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "unitCost") || empty($pd[$i]->unitCost) || !is_numeric($pd[$i]->unitCost)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid unitCost'
                            ));
                            return;
                        }

                        if (!property_exists($pd[$i], "price") || empty($pd[$i]->price) || !is_numeric($pd[$i]->price)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid price'
                            ));
                            return;
                        }
                    }
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid update by'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $no = isset($data->no) ? trim($data->no) : null;
                $documentNo = isset($data->documentNo) ? trim($data->documentNo) : null;
                $documentDate = isset($data->documentDate) ? trim($data->documentDate) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $productDetail = isset($data->productDetail) ? $data->productDetail : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_data_exists = $ReceiveProduct->getReceiveProduct($conn, $documentNo);
                if (!$check_data_exists->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIS_RECEIVE_PRODUCT,
                        'message' => 'not found'
                    ));
                    return;
                }

                $conn->beginTransaction();
                $result_update = $ReceiveProduct->udpateReceiveProduct(
                    $conn,
                    $no,
                    $documentNo,
                    $documentDate,
                    $note,
                    $productDetail,
                    $totalPrice,
                    $updateBy
                );

                if ($result_update) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    $conn->commit();
                    return;
                }

                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_TIS_RECEIVE_PRODUCT_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollBack();
                return;
            } catch (Exception $ex) {
                $conn->rollBack();
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TIS_RECEIVE_PRODUCT:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIS_RECEIVE_PRODUCT) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid actionNodeId'
                    ));
                    return;
                }

                if (!isset($data->documentNo) || empty($data->documentNo)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid document no'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $documentNo = isset($data->documentNo) ? trim($data->documentNo) : null;

                $check_data_exists = $ReceiveProduct->getReceiveProduct($conn, $documentNo);
                if (!$check_data_exists->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIS_RECEIVE_PRODUCT,
                        'message' => 'not found'
                    ));
                    return;
                }

                $conn->beginTransaction();
                $delete_result = $ReceiveProduct->deleteReceiveProduct($conn, $documentNo);
                if ($delete_result) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    $conn->commit();
                    return;
                }

                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_TIS_RECEIVE_PRODUCT_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollBack();
                return;
            } catch (Exception $ex) {
                $conn->rollBack();
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::DELETE_TIS_RECEIVE_PRODUCT_DETAIL:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIS_RECEIVE_PRODUCT_DETAIL) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if ($data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid actionNodeId'
                    ));
                    return;
                }

                if (!isset($data->documentNo) || empty($data->documentNo)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid document no'
                    ));
                    return;
                }

                if (!isset($data->productCode) || empty($data->productCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid document code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $documentNo = isset($data->documentNo) ? trim($data->documentNo) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;

                $check_data_exists = $ReceiveProduct->checkProductDetail($conn, $documentNo, $productCode);
                if (!$check_data_exists) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIS_RECEIVE_PRODUCT_DETAIL,
                        'message' => 'not found'
                    ));
                    return;
                }

                $delete_result = $ReceiveProduct->deleteReceiveProductDetail($conn, $documentNo, $productCode);
                if ($delete_result) {
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
                    'responseCode' => ErrorCode::DELETE_TIS_RECEIVE_PRODUCT_DETAIL_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
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
