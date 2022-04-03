<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$Product = new ProductModel();

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
        case ActionCode::GET_ALL_TIS_PRODUCT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TIS_PRODUCT) {
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


                $get_all_product = $Product->getAllProduct($conn);
                $num_row = $get_all_product->rowCount();
                $get_all_product_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_all_product->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "productUnitId" => $product_unit_id,
                            "date" => $date,
                            "lowestStocked" => $lowest_stocked,
                            "highestStocked" => $highest_stocked,
                            "productTypeId" => $product_type_id,
                            "accountingId" => $accounting_id,
                            "pricePerUnit" => $price_per_unit,
                            "bigUnit" => $big_unit,
                            "size" => $size,
                            "note" => $note,
                            "productImg" => $product_img,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_all_product_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_all_product_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_TIS_PRODUCT,
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
        case ActionCode::GET_TIS_PRODUCT:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TIS_PRODUCT) {
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

                if (!isset($data->productCode) || trim($data->productCode) == null || trim($data->productCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;

                $get_product = $Product->getProduct($conn, $productCode);
                $num_row = $get_product->rowCount();
                $get_product_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $get_product->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "productCode" => $product_code,
                            "productName" => $product_name,
                            "productUnitId" => $product_unit_id,
                            "date" => $date,
                            "lowestStocked" => $lowest_stocked,
                            "highestStocked" => $highest_stocked,
                            "productTypeId" => $product_type_id,
                            "accountingId" => $accounting_id,
                            "pricePerUnit" => $price_per_unit,
                            "bigUnit" => $big_unit,
                            "size" => $size,
                            "note" => $note,
                            "productImg" => $product_img,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($get_product_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $get_product_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_REPAIR_TYPE,
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

        case ActionCode::CREATE_TIS_PRODUCT:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TIS_PRODUCT) {
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

                if (!isset($data->productCode) || empty($data->productCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product code'
                    ));
                    return;
                }


                if (!isset($data->productName) || empty($data->productName)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product name'
                    ));
                    return;
                }

                if (!isset($data->productUnitId) || empty(trim($data->productUnitId))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product unit id'
                    ));
                    return;
                }

                if (!isset($data->date) || !ValidateDate::checkDateFormat($data->date)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid date'
                    ));
                    return;
                }

                if (isset($data->lowestStocked) && !is_numeric($data->lowestStocked)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid lowestStocked'
                    ));
                    return;
                }


                if (isset($data->highestStocked) && !is_numeric($data->highestStocked)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid highestStocked'
                    ));
                    return;
                }

                if (!isset($data->productTypeId) || empty(trim($data->productTypeId)) || !is_numeric($data->productTypeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product type id'
                    ));
                    return;
                }

                if (!isset($data->acountingId) || empty(trim($data->acountingId)) || !is_numeric($data->acountingId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid acountingId id'
                    ));
                    return;
                }

                if (isset($data->pricePerUnit) && !is_numeric($data->pricePerUnit)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid pricePerUnit'
                    ));
                    return;
                }


                if (isset($data->size) && !is_numeric($data->size)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid size'
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
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $productName = isset($data->productName) ? trim($data->productName) : null;
                $productUnitId = isset($data->productUnitId) ? trim($data->productUnitId) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $lowestStocked = isset($data->lowestStocked) ? trim($data->lowestStocked) : null;
                $highestStocked = isset($data->highestStocked) ? trim($data->highestStocked) : null;
                $productTypeId = isset($data->productTypeId) ? trim($data->productTypeId) : null;
                $acountingId = isset($data->acountingId) ? trim($data->acountingId) : null;
                $pricePerUnit = isset($data->pricePerUnit) ? trim($data->pricePerUnit) : null;
                $bigUnit = isset($data->bigUnit) ? trim($data->bigUnit) : null;
                $size = isset($data->size) ? trim($data->size) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $productImg = isset($data->productImg) ? trim($data->productImg) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_product_name = $Product->checkProductName($conn, $productName);

                if ($check_product_name->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_DRIVERS_WAGE_FAIL,
                        'message' => 'the product name is already in the system, Please use other product name '
                    ));
                    return;
                }

                $result_create = $Product->createProduct(
                    $conn,
                    $productCode,
                    $productName,
                    $productUnitId,
                    $date,
                    $lowestStocked,
                    $highestStocked,
                    $productTypeId,
                    $acountingId,
                    $pricePerUnit,
                    $bigUnit,
                    $size,
                    $note,
                    $productImg,
                    $createBy
                );
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
                    'responseCode' => ErrorCode::CREATE_TIS_PRODUCT_FAIL,
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
        case ActionCode::UPDATE_TIS_PRODUCT:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TIS_PRODUCT) {
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

                if (!isset($data->productCode) || empty($data->productCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product code'
                    ));
                    return;
                }


                if (!isset($data->productName) || empty($data->productName)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product name'
                    ));
                    return;
                }

                if (!isset($data->productUnitId) || empty(trim($data->productUnitId))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product unit id'
                    ));
                    return;
                }

                if (!isset($data->date) || !ValidateDate::checkDateFormat($data->date)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid date'
                    ));
                    return;
                }

                if (isset($data->lowestStocked) && !is_numeric($data->lowestStocked)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid lowestStocked'
                    ));
                    return;
                }


                if (isset($data->highestStocked) && !is_numeric($data->highestStocked)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid highestStocked'
                    ));
                    return;
                }

                if (!isset($data->productTypeId) || empty(trim($data->productTypeId)) || !is_numeric($data->productTypeId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid product type id'
                    ));
                    return;
                }

                if (!isset($data->acountingId) || empty(trim($data->acountingId)) || !is_numeric($data->acountingId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid acountingId id'
                    ));
                    return;
                }

                if (isset($data->pricePerUnit) && !is_numeric($data->pricePerUnit)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid pricePerUnit'
                    ));
                    return;
                }


                if (isset($data->size) && !is_numeric($data->size)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'invalid size'
                    ));
                    return;
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
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;
                $productName = isset($data->productName) ? trim($data->productName) : null;
                $productUnitId = isset($data->productUnitId) ? trim($data->productUnitId) : null;
                $date = isset($data->date) ? trim($data->date) : null;
                $lowestStocked = isset($data->lowestStocked) ? trim($data->lowestStocked) : null;
                $highestStocked = isset($data->highestStocked) ? trim($data->highestStocked) : null;
                $productTypeId = isset($data->productTypeId) ? trim($data->productTypeId) : null;
                $acountingId = isset($data->acountingId) ? trim($data->acountingId) : null;
                $pricePerUnit = isset($data->pricePerUnit) ? trim($data->pricePerUnit) : null;
                $bigUnit = isset($data->bigUnit) ? trim($data->bigUnit) : null;
                $size = isset($data->size) ? trim($data->size) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $productImg = isset($data->productImg) ? trim($data->productImg) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_product_exists = $Product->getProduct($conn, $productCode);
                if ($check_product_exists->rowCount() > 0) {
                    $check_product_name = $Product->checkProductName($conn, $productName);
                    if ($check_product_name->rowCount() > 0) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::CREATE_TIS_PRODUCT_FAIL,
                            'message' => 'The product name is already in the system, Please use other product name '
                        ));
                        return;
                    }

                    $result_update = $Product->updateProduct(
                        $conn,
                        $productCode,
                        $productName,
                        $productUnitId,
                        $date,
                        $lowestStocked,
                        $highestStocked,
                        $productTypeId,
                        $acountingId,
                        $pricePerUnit,
                        $bigUnit,
                        $size,
                        $note,
                        $productImg,
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
                        return;
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_TIS_PRODUCT_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    ));
                    return;
                }
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::NOT_FOUND_TIS_PRODUCT,
                    'message' => 'The product code is not found in the system, Please use other product code'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TIS_PRODUCT:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TIS_PRODUCT) {
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

                if (!isset($data->productCode) || empty($data->productCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $productCode = isset($data->productCode) ? trim($data->productCode) : null;

                $check_product_exists = $Product->getProduct($conn, $productCode);
                if (!$check_product_exists->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIS_PRODUCT,
                        'message' => 'not found product code in the system'
                    ));
                    return;
                }
                $delete_result = $Product->deleteProduct($conn, $productCode);

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
                    'responseCode' => ErrorCode::DELETE_TIS_PRODUCT_FAIL,
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
