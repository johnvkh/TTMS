<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$driver = new DriverModel();

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
        case ActionCode::GET_ALL_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_DRIVER) {
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

                $getAllDriver = $driver->getAllDriver($conn);
                $num_row = $getAllDriver->rowCount();
                $getAllDriver_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllDriver->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "driverCode" => $driver_code,
                            "namePrefix" => $name_prefix,
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "idCard" => $id_card,
                            "taxPayerCode" => $tax_payer_code,
                            "driverLicenseNumber" => $driver_license_number,
                            "issueDateDriverLicense" => $issue_date_driver_license,
                            "expireDateDriverLicense" => $expire_date_driver_license,
                            "districtIssuedDriverLicense" => $district_issued_driver_license,
                            "provinceIssuedDriverLicense" => $province_issued_driver_license,
                            "address" => $address,
                            "homeTel" => $home_tel,
                            "mobileTel" => $mobile_tel,
                            "contactNameRef" => $contact_name_ref,
                            "relationshipRef" => $relationship_ref,
                            "mobileTelRef" => $mobile_tel_ref,
                            "dateAdmission" => $date_admission,
                            "status" => $status,
                            "reasionLeavingWork" => $reasion_leaving_work,
                            "leavingWorkDate" => $leaving_work_date,
                            "incomePerDay" => (int) $income_per_day,
                            "incomePerMonth" => (int) $income_per_month,
                            "phoneBill" => $phone_bill,
                            "imagePath" => $image_path,
                            "create_by" => $create_by,
                            "create_date" => $create_date,
                            "update_by" => $update_by,
                            "update_date" => $update_date
                        );
                        array_push($getAllDriver_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllDriver_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_DRIVER,
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
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_DRIVER:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_DRIVER) {
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

                if (trim(!isset($data->driverCode)) || trim($data->driverCode) == null || trim($data->driverCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Driver Code'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;

                $getDriver = $driver->getDriver($conn, $driverCode);
                $num_row = $getDriver->rowCount();
                $getDriver_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getDriver->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "driverCode" => $driver_code,
                            "namePrefix" => $name_prefix,
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "idCard" => $id_card,
                            "taxPayerCode" => $tax_payer_code,
                            "driverLicenseNumber" => $driver_license_number,
                            "issueDateDriverLicense" => $issue_date_driver_license,
                            "expireDateDriverLicense" => $expire_date_driver_license,
                            "districtIssuedDriverLicense" => $district_issued_driver_license,
                            "provinceIssuedDriverLicense" => $province_issued_driver_license,
                            "address" => $address,
                            "homeTel" => $home_tel,
                            "mobileTel" => $mobile_tel,
                            "contactNameRef" => $contact_name_ref,
                            "relationshipRef" => $relationship_ref,
                            "mobileTelRef" => $mobile_tel_ref,
                            "dateAdmission" => $date_admission,
                            "status" => $status,
                            "reasionLeavingWork" => $reasion_leaving_work,
                            "leavingWorkDate" => $leaving_work_date,
                            "incomePerDay" => (int) $income_per_day,
                            "incomePerMonth" => (int) $income_per_month,
                            "phoneBill" => $phone_bill,
                            "imagePath" => $image_path,
                            "create_by" => $create_by,
                            "create_date" => $create_date,
                            "update_by" => $update_by,
                            "update_date" => $update_date
                        );
                        array_push($getDriver_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getDriver_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_DRIVER,
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
        case ActionCode::GET_ALL_REPORT_DRIVER_LICENSE_EXPIRED:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPORT_DRIVER_LICENSE_EXPIRED) {
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

                $getDriver = $driver->reportDriverLicenseExpired($conn);
                $num_row = $getDriver->rowCount();
                $getDriver_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getDriver->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "driverCode" => $driver_code,
                            "namePrefix" => $name_prefix,
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "idCard" => $id_card,
                            "taxPayerCode" => $tax_payer_code,
                            "driverLicenseNumber" => $driver_license_number,
                            "issueDateDriverLicense" => $issue_date_driver_license,
                            "expireDateDriverLicense" => $expire_date_driver_license,
                            "districtIssuedDriverLicense" => $district_issued_driver_license,
                            "provinceIssuedDriverLicense" => $province_issued_driver_license,
                            "address" => $address,
                            "homeTel" => $home_tel,
                            "mobileTel" => $mobile_tel,
                            "contactNameRef" => $contact_name_ref,
                            "relationshipRef" => $relationship_ref,
                            "mobileTelRef" => $mobile_tel_ref,
                            "dateAdmission" => $date_admission,
                            "status" => $status,
                            "reasionLeavingWork" => $reasion_leaving_work,
                            "leavingWorkDate" => $leaving_work_date,
                            "incomePerDay" => (int) $income_per_day,
                            "incomePerMonth" => (int) $income_per_month,
                            "phoneBill" => $phone_bill,
                            "imagePath" => $image_path,
                            "create_by" => $create_by,
                            "create_date" => $create_date,
                            "update_by" => $update_by,
                            "update_date" => $update_date
                        );
                        array_push($getDriver_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getDriver_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_DRIVER,
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
        case ActionCode::CREATE_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_DRIVER) {
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


                if (!isset($data->driverCode) || $data->driverCode == null || $data->driverCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driverCode'
                    ));
                    return;
                }

                if (!isset($data->namePrefix) || $data->namePrefix == null || $data->namePrefix == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Name Prefix'
                    ));
                    return;
                }

                if (isset($data->issueDateDriverLicense)) {
                    if (trim($data->issueDateDriverLicense) == null || trim($data->issueDateDriverLicense) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid issueDateDriverLicense'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->issueDateDriverLicense)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid issueDateDriverLicense'
                            ));
                            return;
                        }
                    }
                }

                if (isset($data->expireDateDriverLicense)) {
                    if (trim($data->expireDateDriverLicense) == null || trim($data->expireDateDriverLicense) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid expireDateDriverLicense'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->expireDateDriverLicense)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid expireDateDriverLicense'
                            ));
                            return;
                        }
                    }
                }

                if (isset($data->dateAdmission)) {
                    if (trim($data->dateAdmission) == null || trim($data->dateAdmission) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid dateAdmission'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->dateAdmission)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid dateAdmission'
                            ));
                            return;
                        }
                    }
                }


                if (!isset($data->status) || $data->status == null || $data->status == "" || $data->status < 0 || $data->status > 1) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid status'
                    ));
                    return;
                }

                if (isset($data->leavingWorkDate)) {
                    if (trim($data->leavingWorkDate) != null || trim($data->leavingWorkDate) != "") {
                        if (!ValidateDate::checkDateFormat($data->leavingWorkDate)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid leavingWorkDate'
                            ));
                            return;
                        }
                    }
                }

                if (!isset($data->createBy) || $data->createBy == null || $data->createBy == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }




                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;
                $namePrefix = isset($data->namePrefix) ? trim($data->namePrefix) : null;
                $firstname = isset($data->firstname) ? trim($data->firstname) : null;
                $lastname = isset($data->lastname) ? trim($data->lastname) : null;
                $idCard = isset($data->idCard) ? trim($data->idCard) : null;
                $taxPayerCode = isset($data->taxPayerCode) ? trim($data->taxPayerCode) : null;
                $driverLicenseNumber = isset($data->driverLicenseNumber) ? trim($data->driverLicenseNumber) : null;
                $issueDateDriverLicense = isset($data->issueDateDriverLicense) ? trim($data->issueDateDriverLicense) : null;
                $expireDateDriverLicense = isset($data->expireDateDriverLicense) ? trim($data->expireDateDriverLicense) : null;
                $districtIssuedDriverLicense = isset($data->districtIssuedDriverLicense) ? trim($data->districtIssuedDriverLicense) : null;
                $provinceIssuedDriverLicense = isset($data->provinceIssuedDriverLicense) ? trim($data->provinceIssuedDriverLicense) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $homeTel = isset($data->homeTel) ? trim($data->homeTel) : null;
                $mobileTel = isset($data->mobileTel) ? trim($data->mobileTel) : null;
                $contactNameRef = isset($data->contactNameRef) ? trim($data->contactNameRef) : null;
                $relationshipRef = isset($data->relationshipRef) ? trim($data->relationshipRef) : null;
                $mobileTelRef = isset($data->mobileTelRef) ? trim($data->mobileTelRef) : null;
                $dateAdmission = isset($data->dateAdmission) ? trim($data->dateAdmission) : null;
                $status = isset($data->status) ? trim($data->status) : null;
                $reasionLeavingWork = isset($data->reasionLeavingWork) ? trim($data->reasionLeavingWork) : null;
                $leavingWorkDate = isset($data->leavingWorkDate) ? trim($data->leavingWorkDate) : null;
                $incomePerDay = isset($data->incomePerDay) ? trim($data->incomePerDay) : null;
                $incomePerMonth = isset($data->incomePerMonth) ? trim($data->incomePerMonth) : null;
                $phoneBill = isset($data->phoneBill) ? trim($data->phoneBill) : null;
                $imagePath = isset($data->imagePath) ? trim($data->imagePath) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getDriver = $driver->getDriver($conn, $driverCode);

                if ($getDriver->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The Driver Code is already in the system, Please use another code'
                    ));
                    http_response_code(200);
                    return;
                }
                $result = $driver->createNewDriver(
                        $conn, $driverCode, $namePrefix, $firstname, $lastname, $idCard, $taxPayerCode, $driverLicenseNumber, $issueDateDriverLicense, $expireDateDriverLicense, $districtIssuedDriverLicense, $provinceIssuedDriverLicense, $address, $homeTel, $mobileTel, $contactNameRef, $relationshipRef, $mobileTelRef, $dateAdmission, $status, $reasionLeavingWork, $leavingWorkDate, $incomePerDay, $incomePerMonth, $phoneBill, $imagePath, $createBy = 'admin'
                );

                if (!$result) {
                    $res = array(
                        'responseCode' => ErrorCode::CREATE_DRIVER_FAIL,
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
        case ActionCode::UPDATE_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_DRIVER) {
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

                if (!isset($data->namePrefix) || $data->namePrefix == null || $data->namePrefix == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Name Prefix'
                    ));
                    return;
                }

                if (isset($data->issueDateDriverLicense)) {
                    if (trim($data->issueDateDriverLicense) == null || trim($data->issueDateDriverLicense) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid issueDateDriverLicense'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->issueDateDriverLicense)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid issueDateDriverLicense'
                            ));
                            return;
                        }
                    }
                }

                if (isset($data->expireDateDriverLicense)) {
                    if (trim($data->expireDateDriverLicense) == null || trim($data->expireDateDriverLicense) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid expireDateDriverLicense'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->expireDateDriverLicense)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid expireDateDriverLicense'
                            ));
                            return;
                        }
                    }
                }

                if (isset($data->dateAdmission)) {
                    if (trim($data->dateAdmission) == null || trim($data->dateAdmission) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid dateAdmission'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->dateAdmission)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid dateAdmission'
                            ));
                            return;
                        }
                    }
                }


                if (!isset($data->status) || $data->status == null || $data->status == "" || $data->status < 0 || $data->status > 1) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid status'
                    ));
                    return;
                }

                if (isset($data->leavingWorkDate)) {
                    if (trim($data->leavingWorkDate) == null || trim($data->leavingWorkDate) == "") {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid leavingWorkDate'
                        ));
                        return;
                    } else {
                        if (!$validateDate->date_format($data->leavingWorkDate)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid leavingWorkDate'
                            ));
                            return;
                        }
                    }
                }

                if (!isset($data->updateBy) || $data->updateBy == null || $data->updateBy == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;
                $namePrefix = isset($data->namePrefix) ? trim($data->namePrefix) : null;
                $firstname = isset($data->firstname) ? trim($data->firstname) : null;
                $lastname = isset($data->lastname) ? trim($data->lastname) : null;
                $idCard = isset($data->idCard) ? trim($data->idCard) : null;
                $taxPayerCode = isset($data->taxPayerCode) ? trim($data->taxPayerCode) : null;
                $driverLicenseNumber = isset($data->driverLicenseNumber) ? trim($data->driverLicenseNumber) : null;
                $issueDateDriverLicense = isset($data->issueDateDriverLicense) ? trim($data->issueDateDriverLicense) : null;
                $expireDateDriverLicense = isset($data->expireDateDriverLicense) ? trim($data->expireDateDriverLicense) : null;
                $districtIssuedDriverLicense = isset($data->districtIssuedDriverLicense) ? trim($data->districtIssuedDriverLicense) : null;
                $provinceIssuedDriverLicense = isset($data->provinceIssuedDriverLicense) ? trim($data->provinceIssuedDriverLicense) : null;
                $address = isset($data->address) ? trim($data->address) : null;
                $homeTel = isset($data->homeTel) ? trim($data->homeTel) : null;
                $mobileTel = isset($data->mobileTel) ? trim($data->mobileTel) : null;
                $contactNameRef = isset($data->contactNameRef) ? trim($data->contactNameRef) : null;
                $relationshipRef = isset($data->relationshipRef) ? trim($data->relationshipRef) : null;
                $mobileTelRef = isset($data->mobileTelRef) ? trim($data->mobileTelRef) : null;
                $dateAdmission = isset($data->dateAdmission) ? trim($data->dateAdmission) : null;
                $status = isset($data->status) ? trim($data->status) : null;
                $reasionLeavingWork = isset($data->reasionLeavingWork) ? trim($data->reasionLeavingWork) : null;
                $leavingWorkDate = isset($data->leavingWorkDate) ? trim($data->leavingWorkDate) : null;
                $incomePerDay = isset($data->incomePerDay) ? trim($data->incomePerDay) : null;
                $incomePerMonth = isset($data->incomePerMonth) ? trim($data->incomePerMonth) : null;
                $phoneBill = isset($data->phoneBill) ? trim($data->phoneBill) : null;
                $imagePath = isset($data->imagePath) ? trim($data->imagePath) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getDriver = $driver->getDriver($conn, $driverCode);

                if ($getDriver->rowCount() > 0) {
                    $updateResult = $driver->updateDriver(
                            $conn, $driverCode, $namePrefix, $firstname, $lastname, $idCard, $taxPayerCode, $driverLicenseNumber, $issueDateDriverLicense, $expireDateDriverLicense, $districtIssuedDriverLicense, $provinceIssuedDriverLicense, $address, $homeTel, $mobileTel, $contactNameRef, $relationshipRef, $mobileTelRef, $dateAdmission, $status, $reasionLeavingWork, $leavingWorkDate, $incomePerDay, $incomePerMonth, $phoneBill, $imagePath, $updateBy
                    );
                    if (!$updateResult) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_DRIVER_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_DRIVER_FAIL,
                    'message' => 'The Driver Code is not found in the system, Please use another code'
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
        case ActionCode::DELETE_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_DRIVER) {
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
