<?php
// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$CustomerModel = new CustomerModel();


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
        case ActionCode::GET_ALL_CUSTOMER:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_CUSTOMER) {
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

                $getAllCustomer = $CustomerModel->getAllCustomerModel($conn);
                $num_row = $getAllCustomer->rowCount();
                $getAllCustomer_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllCustomer->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "customerCode" => $customer_code,
                            "fullname" => $fullname,
                            "address" => $address,
                            "tel" => $tel,
                            "fax" => $fax,
                            "mobilePhone" => $mobile_phone,
                            "email" => $email,
                            "contactNameBy" => $contact_name_by,
                            "start_contact_date" => $start_contact_date,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllCustomer_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllCustomer_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_CUSTOMER,
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
        case ActionCode::GET_CUSTOMER:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_CUSTOMER) {
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

                if (trim(!isset($data->customerCode)) || trim($data->customerCode) == null || trim($data->customerCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $customerCode = isset($data->customerCode) ? trim($data->customerCode) : null;

                $getCustomer = $CustomerModel->getCustomerModel($conn, $customerCode);
                $num_row = $getCustomer->rowCount();
                $getCustomer_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getCustomer->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "customerCode" => $customer_code,
                            "fullname" => $fullname,
                            "address" => $address,
                            "tel" => $tel,
                            "fax" => $fax,
                            "mobilePhone" => $mobile_phone,
                            "email" => $email,
                            "contactNameBy" => $contact_name_by,
                            "start_contact_date" => $start_contact_date,
                            "note" => $note,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "udpateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getCustomer_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getCustomer_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_CUSTOMER,
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
        case ActionCode::CREATE_CUSTOMER:

            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_CUSTOMER) {
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

                if (trim(!isset($data->customerCode)) || trim($data->customerCode) == null || trim($data->customerCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid customerCode'
                    ));
                    return;
                }

                if (trim(!isset($data->start_contact_date)) || trim($data->start_contact_date) == null || trim($data->start_contact_date) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid start_contact_date'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->start_contact_date)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start_contact_date'
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
                $customerCode = isset($data->customerCode) ? trim($data->customerCode) : null;
                $fullname = isset($data->fullname) ? trim($data->fullname) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $fax = isset($data->fax) ? trim($data->fax) : null;
                $mobilePhone = isset($data->mobilePhone) ? trim($data->mobilePhone) : null;
                $email = isset($data->email) ? trim($data->email) : null;
                $contactNameBy = isset($data->contactNameBy) ? trim($data->contactNameBy) : null;
                $start_contact_date = isset($data->start_contact_date) ? trim($data->start_contact_date) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getCustomer = $CustomerModel->getCustomerModel($conn, $customerCode);

                if ($getCustomer->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The Customer Code is already in the system, Please use another code'
                    ));
                    http_response_code(417); //Expectation Failed
                    return;
                }


                $result = $CustomerModel->createNewCustomerModel(
                    $conn,
                    $customerCode,
                    $fullname,
                    $tel,
                    $address,
                    $fax,
                    $mobilePhone,
                    $email,
                    $contactNameBy,
                    $start_contact_date,
                    $note,
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
                        'responseCode' => ErrorCode::CREATE_CUSTOMER_FAIL,
                        'message' => 'fail',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );

                    echo json_encode($res);
                    http_response_code(417);
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
        case ActionCode::UPDATE_CUSTOMER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_CUSTOMER) {
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

                if (trim(!isset($data->customerCode)) || trim($data->customerCode) == null || trim($data->customerCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid product code'
                    ));
                    return;
                }

                if (trim(!isset($data->start_contact_date)) || trim($data->start_contact_date) == null || trim($data->start_contact_date) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid start_contact_date'
                    ));
                    return;
                } else {
                    if (!$validateDate->date_format($data->start_contact_date)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid start_contact_date'
                        ));
                        return;
                    }
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

                $customerCode = isset($data->customerCode) ? trim($data->customerCode) : null;
                $fullname = isset($data->fullname) ? trim($data->fullname) : null;
                $tel = isset($data->tel) ? trim($data->tel) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $fax = isset($data->fax) ? trim($data->fax) : null;
                $mobilePhone = isset($data->mobilePhone) ? trim($data->mobilePhone) : null;
                $email = isset($data->email) ? trim($data->email) : null;
                $contactNameBy = isset($data->contactNameBy) ? trim($data->contactNameBy) : null;
                $start_contact_date = isset($data->start_contact_date) ? trim($data->start_contact_date) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getCustomer = $CustomerModel->getCustomerModel($conn, $customerCode);
                if ($getCustomer->rowCount() > 0) {
                    $updateResult = $CustomerModel->updateCustomerModel(
                        $conn,
                        $customerCode,
                        $fullname,
                        $tel,
                        $address,
                        $fax,
                        $mobilePhone,
                        $email,
                        $contactNameBy,
                        $start_contact_date,
                        $note,
                        $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_CUSTOMER_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_CUSTOMER_FAIL,
                    'message' => 'The Customer Code is not found in the system, Please use another code'
                ));
                http_response_code(417); //Expectation Failed
                return;
            } catch (Exception $ex) {
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_CUSTOMER:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_CUSTOMER) {
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


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                echo json_encode(array(
                    'responseCode' => ErrorCode::DELETE_CUSTOMER_FAIL,
                    'message' => 'This route is not yet define .'
                ));
                http_response_code(417); //Expectation Failed
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
