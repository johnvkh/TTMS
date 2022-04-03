<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$staffModel = new StaffModel();
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
        case ActionCode::GET_STAFF:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_STAFF) {
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
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $departmentId = isset($data->departmentId) ? trim($data->departmentId) : null;
                $branchId = isset($data->branchId) ? trim($data->branchId) : null;

                $getStaff = $staffModel->getStaff($conn, $staffCode, $departmentId, $branchId);
                $num_row = $getStaff->rowCount();
                $getStaffArr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getStaff->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "staffId" => $STAFF_ID,
                            "staffCode" => $STAFF_CODE,
                            "fullName" => $FULL_NAME,
                            "email" => $EMAIL,
                            "msisdn" => $MSISDN,
                            "password" => $PASSWORD,
                            "roleId" => $ROLE_ID,
                            "status" => $STATUS,
                            "gender" => $GENDER,
                            "birthDay" => $BIRTH_DAY,
                            "address" => $ADDRESS,
                            "createDate" => $CREATED_DATE,
                            "createBy" => $CREATED_BY,
                            "modifiedDate" => $MODIFIED_DATE,
                            "departmentId" => $DEPARTMENT_ID,
                            "branchId" => $BRANCH_ID,
                            "modifiedBy" => $MODIFIED_BY
                        );
                        array_push($getStaffArr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'result' => $num_row,
                        'data' => $getStaffArr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_STAFF,
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
        case ActionCode::GET_ALL_STAFF:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_STAFF) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $getStaff = $staffModel->getStaff($conn, "", "", "");
                $num_row = $getStaff->rowCount();
                $getStaffArr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getStaff->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "staffId" => $STAFF_ID,
                            "staffCode" => $STAFF_CODE,
                            "fullName" => $FULL_NAME,
                            "email" => $EMAIL,
                            "msisdn" => $MSISDN,
                            "password" => $PASSWORD,
                            "roleId" => $ROLE_ID,
                            "status" => $STATUS,
                            "gender" => $GENDER,
                            "birthDay" => $BIRTH_DAY,
                            "address" => $ADDRESS,
                            "createDate" => $CREATED_DATE,
                            "createBy" => $CREATED_BY,
                            "modifiedDate" => $MODIFIED_DATE,
                            "departmentId" => $DEPARTMENT_ID,
                            "branchId" => $BRANCH_ID,
                            "modifiedBy" => $MODIFIED_BY
                        );
                        array_push($getStaffArr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        'result' => $num_row,
                        'data' => $getStaffArr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_STAFF,
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
        case ActionCode::INSERT_STAFF:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::INSERT_STAFF) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Code'
                    ));
                    return;
                }

                if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Action Node'
                    ));
                    return;
                }

                if (!isset($data->staffCode) || empty($data->staffCode)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid staff Code'
                    ));
                    return;
                }

                if (!isset($data->fullName) || empty($data->fullName)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid full Name'
                    ));
                    return;
                }

                if (!isset($data->email) || empty($data->email)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid email'
                    ));
                    return;
                }

                if (!isset($data->msisdn) || empty($data->msisdn)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid msisdn'
                    ));
                    return;
                }

                if (!isset($data->password) || empty($data->password)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid password'
                    ));
                    return;
                }

                if (!isset($data->roleId) || empty($data->roleId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid role Id'
                    ));
                    return;
                }

                if (!isset($data->gender) || empty($data->gender)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid gender'
                    ));
                    return;
                }

                if (isset($data->birthday)) {
                    if (!ValidateDate::checkDateFormat($data->birthday)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid birthday'
                        ));
                        return;
                    }
                }

                if (!isset($data->departmentId) || empty($data->departmentId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid department Id'
                    ));
                    return;
                }

                if (!isset($data->branchId) || empty($data->branchId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid branchId'
                    ));
                    return;
                }

                if (!isset($data->createBy) || empty($data->createBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $fullName = isset($data->fullName) ? trim($data->fullName) : null;
                $email = isset($data->email) ? trim($data->email) : null;
                $msisdn = isset($data->msisdn) ? trim($data->msisdn) : null;
                $password = isset($data->password) ? trim($data->password) : null;
                $roleId = isset($data->roleId) ? trim($data->roleId) : null;
                $gender = isset($data->gender) ? trim($data->gender) : null;
                $birthday = isset($data->birthday) ? trim($data->birthday) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $departmentId = isset($data->departmentId) ? trim($data->departmentId) : null;
                $branchId = isset($data->branchId) ? trim($data->branchId) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkDuplicateStaffCode = $staffModel->checkDuplicateStaffCodeProcess($conn, $staffCode);
                if ($checkDuplicateStaffCode) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INSERT_STAFF_FAIL,
                        'message' => 'this staff code is already has in the system, please use other code.'
                    ));
                    return;
                }
                $resultInsertStaff = $staffModel->insertStaffProcess($conn, $staffCode, $fullName, $email, $msisdn, $password, $roleId, $gender, $birthday, $address, $departmentId, $branchId, $createBy);
                if ($resultInsertStaff) {
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
                    'responseCode' => ErrorCode::INSERT_STAFF_FAIL,
                    'message' => 'fail',
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
        case ActionCode::UPDATE_STAFF:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_STAFF) {
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

                if (!isset($data->staffCode) || empty($data->staffCode)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid staff Code'
                    ));
                    return;
                }

                if (!isset($data->fullName) || empty($data->fullName)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid full Name'
                    ));
                    return;
                }

                if (!isset($data->email) || empty($data->email)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid email'
                    ));
                    return;
                }

                if (!isset($data->msisdn) || empty($data->msisdn)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid msisdn'
                    ));
                    return;
                }

                if (!isset($data->roleId) || empty($data->roleId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid role Id'
                    ));
                    return;
                }

                if (!isset($data->gender) || empty($data->gender)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid gender'
                    ));
                    return;
                }

                if (isset($data->birthday)) {
                    if (!ValidateDate::checkDateFormat($data->birthday)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid birthday'
                        ));
                        return;
                    }
                }

                if (!isset($data->departmentId) || empty($data->departmentId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid department Id'
                    ));
                    return;
                }

                if (!isset($data->branchId) || empty($data->branchId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid branchId'
                    ));
                    return;
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $fullName = isset($data->fullName) ? trim($data->fullName) : null;
                $email = isset($data->email) ? trim($data->email) : null;
                $msisdn = isset($data->msisdn) ? trim($data->msisdn) : null;
                $roleId = isset($data->roleId) ? trim($data->roleId) : null;
                $gender = isset($data->gender) ? trim($data->gender) : null;
                $birthday = isset($data->birthday) ? trim($data->birthday) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $departmentId = isset($data->departmentId) ? trim($data->departmentId) : null;
                $branchId = isset($data->branchId) ? trim($data->branchId) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $resultUpdateStaff = $staffModel->updateStaff($conn, $staffCode, $fullName, $email, $msisdn, $roleId, $gender, $birthday, $address, $departmentId, $branchId, $updateBy);
                if ($resultUpdateStaff) {
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
                    'responseCode' => ErrorCode::UPDATE_STAFF_FAIL,
                    'message' => 'fail',
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
        case ActionCode::DELETE_STAFF:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_STAFF) {
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

                if (!isset($data->staffCode) || empty($data->staffCode)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid staff Code'
                    ));
                    return;
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $result_delete = $staffModel->deleteStaff($conn, $staffCode, $updateBy);
                if ($result_delete) {
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
                    'responseCode' => ErrorCode::DELETE_STAFF_FAIL,
                    'message' => 'fail',
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
        case ActionCode::STAFF_RESET_PASS:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::STAFF_RESET_PASS) {
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

                if (!isset($data->staffCode) || empty($data->staffCode)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid staff Code'
                    ));
                    return;
                }
                
                if (!isset($data->oldPassword) || empty($data->oldPassword)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid old Password'
                    ));
                    return;
                }
                
                if (!isset($data->newPassword) || empty($data->newPassword)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid new Password'
                    ));
                    return;
                }
                
                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $staffCode = isset($data->staffCode) ? trim($data->staffCode) : null;
                $oldPassword = isset($data->oldPassword) ? trim($data->oldPassword) : null;
                $newPassword = isset($data->newPassword) ? trim($data->newPassword) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;
                
                $resultReset = $staffModel->staffResetPassword($conn, $staffCode,$oldPassword,$newPassword, $updateBy);
                if ($resultReset) {
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
                    'responseCode' => ErrorCode::DELETE_STAFF_FAIL,
                    'message' => 'fail',
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

