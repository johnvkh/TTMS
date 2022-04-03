<?php

require_once '../../includes/Autoload.php';
$validateString = new ValidateString();
$db = new DatabaseConfig();
$conn = $db->connection();
$TruckRegistrationBackPartModel = new TruckRegistrationBackPartModel();

$timestamp = date("Y-m-d h:i:s");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'));
    switch ($data->actionCode) {
        case ActionCode::GET_ALL_TRUCK_REGISTRATION_BACK_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_TRUCK_REGISTRATION_BACK_PART) {
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

                $getAllTruckRegistrationBackPart = $TruckRegistrationBackPartModel->getAllTruckRegistrationBackPart($conn);
                $num_row = $getAllTruckRegistrationBackPart->rowCount();
                $getAllTruckRegistrationBackPart_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllTruckRegistrationBackPart->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckColorId" => $truck_color_id,
                            "truckColorName" => $truck_color_name,
                            "truckModelCode" => $truck_model_code,
                            "truckTypeId" => $truck_type_id,
                            "truckTypeName" => $truck_type_name,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "registrationDate" => $registration_date,
                            "registrationExpiredDate" => $registration_date_expired,
                            "technicRenewalDate" => $technical_renewal_date,
                            "technicExpiredDate" => $technical_expired_date,
                            "driverLicense" => $driver_license,
                            "annualFeeReceipt" => $annual_fee_receipt,
                            "tungsitRenewalDate" => $tungsit_renewal_date,
                            "tungsitExpiredDate" => $tungsit_renewal_expired_date,
                            "insuranceRenewalDate" => $insurance_renewal_date,
                            "insuranceExpiredDate" => $insurance_expired_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllTruckRegistrationBackPart_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getAllTruckRegistrationBackPart_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_REGISTRATION_BACK_PART_TYPE_FAIL,
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
        case ActionCode::GET_TRUCK_REGISTRATION_BACK_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_TRUCK_REGISTRATION_BACK_PART) {
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

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $getTruckRegistrationBackPart = $TruckRegistrationBackPartModel->getTruckRegistrationBackPart($conn, $licensePlate);
                $get_tire_truck_by_license_plate = $TruckRegistrationBackPartModel->getTireTruck($conn, $licensePlate);
                $tire_truck_arr = array();
                while ($row_tire_truck = $get_tire_truck_by_license_plate->fetch(PDO::FETCH_ASSOC)) {
                    extract($row_tire_truck);
                    $data = array(
                        "whichPart" => $which_part,
                        "wheelPosition" => $wheel_position,
                        "tireCode" => $tire_code,
                        "changedTireLatestDate" => $changed_tire_latest_date,
                        "tireBrandId" => $tire_brand_id,
                        "tireBrandName" => null,
                        "tireSize" => $tire_size
                    );
                    array_push($tire_truck_arr, $data);
                }

                $num_row = $getTruckRegistrationBackPart->rowCount();
                $getTruckRegistrationBackPart_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getTruckRegistrationBackPart->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "whichPart" => $which_part,
                            "licensePlate" => $license_plate,
                            "truckBrandId" => $truck_brand_id,
                            "truckBrandName" => $truck_brand_name,
                            "truckColorId" => $truck_color_id,
                            "truckColorName" => $truck_color_name,
                            "truckModelCode" => $truck_model_code,
                            "truckTypeId" => $truck_type_id,
                            "truckTypeName" => $truck_type_name,
                            "provinceId" => $province_id,
                            "provinceName" => $province_name,
                            "registrationDate" => $registration_date,
                            "registrationExpiredDate" => $registration_date_expired,
                            "technicRenewalDate" => $technical_renewal_date,
                            "technicExpiredDate" => $technical_expired_date,
                            "driverLicense" => $driver_license,
                            "annualFeeReceipt" => $annual_fee_receipt,
                            "tungsitRenewalDate" => $tungsit_renewal_date,
                            "tungsitExpiredDate" => $tungsit_renewal_expired_date,
                            "insuranceRenewalDate" => $insurance_renewal_date,
                            "insuranceExpiredDate" => $insurance_expired_date,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getTruckRegistrationBackPart_arr['data'], $data);
                    }
                    $res = array(
                        'rowCount' => $num_row,
                        'data' => $getTruckRegistrationBackPart_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_TRUCK_REGISTRATION_BACK_PART_TYPE_FAIL,
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
        case ActionCode::CREATE_TRUCK_REGISTRATION_BACK_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_TRUCK_REGISTRATION_BACK_PART) {
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

                if (!isset($data->whichPart) || !is_numeric($data->whichPart)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || empty($data->truckBrandId) || !is_numeric($data->truckBrandId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckColorId) || empty($data->truckColorId) || !is_numeric($data->truckColorId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckColorId'
                    ));
                    return;
                }

                //truckModelCode can null
//                if (!isset($data->truckModelCode) || empty($data->truckModelCode) || !is_numeric($data->truckModelCode)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckModelCode'
//                    ));
//                    return;
//                }

                if (!isset($data->truckTypeId) || empty($data->truckTypeId) || !is_numeric($data->truckTypeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeId'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || empty($data->provinceId) || !is_numeric($data->provinceId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

                if (isset($data->registrationDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationDate'
                        ));
                        return;
                    }
                }

                if (isset($data->registrationDateExpired)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDateExpired)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationExpiredDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicExpiryDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitExpiryDate'
                        ));
                        return;
                    }
                }
                //driver_license
                //annual_fee_receipt can null
                if (isset($data->insuranceRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->insuranceExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceExpiryDate'
                        ));
                        return;
                    }
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
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckColorId = isset($data->truckColorId) ? trim($data->truckColorId) : null;
                $truckModelCode = isset($data->truckModelCode) ? trim($data->truckModelCode) : null;
                $truckTypeId = isset($data->truckTypeId) ? trim($data->truckTypeId) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $registrationDate = isset($data->registrationDate) ? trim($data->registrationDate) : null;
                $registrationExpiredDate = isset($data->registrationExpiredDate) ? trim($data->registrationExpiredDate) : null;
                $registrationDateExpired = isset($data->registrationDateExpired) ? trim($data->registrationDateExpired) : null;
                $technicRenewalDate = isset($data->technicRenewalDate) ? trim($data->technicRenewalDate) : null;
                $technicExpiryDate = isset($data->technicExpiryDate) ? trim($data->technicExpiryDate) : null;
                $driverLicense = isset($data->driverLicense) ? trim($data->driverLicense) : null;
                $annualFeeReceipt = isset($data->annualFeeReceipt) ? trim($data->annualFeeReceipt) : null;
                $tungsitRenewalDate = isset($data->tungsitRenewalDate) ? trim($data->tungsitRenewalDate) : null;
                $tungsitRenewalExpiredDate = isset($data->tungsitRenewalExpiredDate) ? trim($data->tungsitRenewalExpiredDate) : null;
                $insuranceRenewalDate = isset($data->insuranceRenewalDate) ? trim($data->insuranceRenewalDate) : null;
                $insuranceExpiredDate = isset($data->insuranceExpiredDate) ? trim($data->insuranceExpiredDate) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $check_license_plate_on_back_part = $TruckRegistrationBackPartModel->checkLicensePlateOnBackPart($conn, $licensePlate);
                $check_license_plate_on_tire_truck = $TruckRegistrationBackPartModel->checkLicensePlateOnTireTruck($conn, $licensePlate);
                if ($check_license_plate_on_back_part || $check_license_plate_on_tire_truck) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_TRUCK_TYPE_FAIL,
                        'message' => 'The license plate is already in the system, Please use another license plate'
                    ));
                    return;
                }

                $conn->beginTransaction();
                $result_create = $TruckRegistrationBackPartModel->createNewRegistrationBackPart(
                        $conn,
                        $licensePlate,
                        $truckBrandId,
                        $truckColorId,
                        $truckModelCode,
                        $truckTypeId,
                        $provinceId,
                        $registrationDate,
                        $registrationDateExpired,
                        $technicRenewalDate,
                        $technicExpiryDate,
                        $driverLicense,
                        $annualFeeReceipt,
                        $tungsitRenewalDate,
                        $tungsitRenewalExpiredDate,
                        $insuranceRenewalDate,
                        $insuranceExpiredDate,
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
                    $conn->commit();
                    return;
                }

                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::CREATE_TRUCK_REGISTRATION_BACK_PART_TYPE_FAIL,
                    'message' => 'success',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollback();
                return;
            } catch (Exception $ex) {
                $conn->rollback();
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_TRUCK_REGISTRATION_BACK_PART:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_TRUCK_REGISTRATION_BACK_PART) {
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

                if (!isset($data->whichPart) || !is_numeric($data->whichPart)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }

                if (!isset($data->licensePlate) || empty($data->licensePlate)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (!isset($data->truckBrandId) || empty($data->truckBrandId) || !is_numeric($data->truckBrandId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckBrandId'
                    ));
                    return;
                }

                if (!isset($data->truckColorId) || empty($data->truckColorId) || !is_numeric($data->truckColorId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckColorId'
                    ));
                    return;
                }

                //truckModelCode can null
//                if (!isset($data->truckModelCode) || empty($data->truckModelCode) || !is_numeric($data->truckModelCode)) {
//                    http_response_code(200);
//                    echo json_encode(array(
//                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
//                        'message' => 'Invalid truckModelCode'
//                    ));
//                    return;
//                }

                if (!isset($data->truckTypeId) || empty($data->truckTypeId) || !is_numeric($data->truckTypeId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckTypeId'
                    ));
                    return;
                }

                if (!isset($data->provinceId) || empty($data->provinceId) || !is_numeric($data->provinceId)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid provinceId'
                    ));
                    return;
                }

                if (isset($data->registrationDate)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationDate'
                        ));
                        return;
                    }
                }

                if (isset($data->registrationDateExpired)) {
                    if (!ValidateDate::checkDateFormat($data->registrationDateExpired)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid registrationExpiredDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->technicExpiryDate)) {
                    if (!ValidateDate::checkDateFormat($data->technicExpiryDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid technicExpiryDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->tungsitRenewalExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->tungsitRenewalExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid tungsitExpiryDate'
                        ));
                        return;
                    }
                }
                //driver_license
                //annual_fee_receipt can null
                if (isset($data->insuranceRenewalDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceRenewalDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceRenewalDate'
                        ));
                        return;
                    }
                }

                if (isset($data->insuranceExpiredDate)) {
                    if (!ValidateDate::checkDateFormat($data->insuranceExpiredDate)) {
                        http_response_code(200);
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid insuranceExpiryDate'
                        ));
                        return;
                    }
                }

                if (!isset($data->updateBy) || empty($data->updateBy)) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid update by'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;

                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $truckBrandId = isset($data->truckBrandId) ? trim($data->truckBrandId) : null;
                $truckColorId = isset($data->truckColorId) ? trim($data->truckColorId) : null;
                $truckModelCode = isset($data->truckModelCode) ? trim($data->truckModelCode) : null;
                $truckTypeId = isset($data->truckTypeId) ? trim($data->truckTypeId) : null;
                $provinceId = isset($data->provinceId) ? trim($data->provinceId) : null;
                $registrationDate = isset($data->registrationDate) ? trim($data->registrationDate) : null;
                $registrationExpiredDate = isset($data->registrationExpiredDate) ? trim($data->registrationExpiredDate) : null;
                $registrationDateExpired = isset($data->registrationDateExpired) ? trim($data->registrationDateExpired) : null;
                $technicRenewalDate = isset($data->technicRenewalDate) ? trim($data->technicRenewalDate) : null;
                $technicExpiryDate = isset($data->technicExpiryDate) ? trim($data->technicExpiryDate) : null;
                $driverLicense = isset($data->driverLicense) ? trim($data->driverLicense) : null;
                $annualFeeReceipt = isset($data->annualFeeReceipt) ? trim($data->annualFeeReceipt) : null;
                $tungsitRenewalDate = isset($data->tungsitRenewalDate) ? trim($data->tungsitRenewalDate) : null;
                $tungsitRenewalExpiredDate = isset($data->tungsitRenewalExpiredDate) ? trim($data->tungsitRenewalExpiredDate) : null;
                $insuranceRenewalDate = isset($data->insuranceRenewalDate) ? trim($data->insuranceRenewalDate) : null;
                $insuranceExpiredDate = isset($data->insuranceExpiredDate) ? trim($data->insuranceExpiredDate) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $check_license_plate_is_exists_on_back_part = $TruckRegistrationBackPartModel->checkLicensePlateOnBackPart($conn, $licensePlate);
                if (!$check_license_plate_is_exists_on_back_part) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                        'message' => 'The license plate is not found in the system, Please use another license plate'
                    ));
                    return;
                }

                $conn->beginTransaction();
                $result_update = $TruckRegistrationBackPartModel->updateRegistrationBackPart(
                        $conn,
                        $whichPart,
                        $licensePlate,
                        $truckBrandId,
                        $truckColorId,
                        $truckModelCode,
                        $truckTypeId,
                        $provinceId,
                        $registrationDate,
                        $registrationDateExpired,
                        $technicRenewalDate,
                        $technicExpiryDate,
                        $driverLicense,
                        $annualFeeReceipt,
                        $tungsitRenewalDate,
                        $tungsitRenewalExpiredDate,
                        $insuranceRenewalDate,
                        $insuranceExpiredDate,
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
                    'responseCode' => ErrorCode::UPDATE_TRUCK_REGISTRATION_HEAD_PART_TYPE_FAIL,
                    'message' => 'fail',
                    'timestamp' => $timestamp,
                    'actionCode' => $actionCode,
                    'actionNodeId' => $actionNodeId
                ));
                $conn->rollback();
                return;
            } catch (Exception $ex) {
                $conn->rollback();
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
                http_response_code(200);
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_TRUCK_REGISTRATION_HEAD_PART:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_TRUCK_REGISTRATION_HEAD_PART) {
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

                if ($data->truckTypeCode == null || $data->truckTypeCode == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid Truck Type Code'
                    ));
                    http_response_code(200);
                    return;
                }

                $actionCode = trim($data->actionCode);
                $actionNodeId = $data->actionNodeId;
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
