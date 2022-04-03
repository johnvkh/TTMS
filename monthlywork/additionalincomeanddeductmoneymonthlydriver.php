<?php

// error_reporting(0);
require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$AdditionalIncomeAndDeductMoneyMonthlyDriver = new AdditionalIncomeAndDeductMoneyMonthlyDriverModel();

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
        case ActionCode::GET_ALL_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER) {
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

                $getAllAdditionalIncomeAndDeductMoneyMonthlyDriver = $AdditionalIncomeAndDeductMoneyMonthlyDriver->getAllAdditionalIncomeAndDeductMoneyMonthlyDriver($conn);
                $num_row = $getAllAdditionalIncomeAndDeductMoneyMonthlyDriver->rowCount();
                $getAllAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllAdditionalIncomeAndDeductMoneyMonthlyDriver->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "additionalIncomeAndDeduct" => $additional_income_and_deduct_money_monthly_driver_date,
                            "driverCode" => $driver_code,
                            "licensePlate" => $license_plate,
                            "workingDay" => $working_day,
                            "incomePerTrip" => $income_per_trip,
                            "propertyInsuranceDeduction" => $property_insurance_deduction,
                            "deductMoneyFromBorrowing" => $deduct_money_from_borrowing,
                            "deductionAccidentInsurance" => $deduction_accident_insurance,
                            "deductOtherMoney" => $deduct_other_money,
                            "totalDeduction" => $total_deduction,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
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
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER) {
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
                        'message' => 'Invalid driverCode'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;

                $getAdditionalIncomeAndDeductMoneyMonthlyDriver = $AdditionalIncomeAndDeductMoneyMonthlyDriver->getAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $driverCode, $licensePlate);
                $num_row = $getAdditionalIncomeAndDeductMoneyMonthlyDriver->rowCount();
                $getAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAdditionalIncomeAndDeductMoneyMonthlyDriver->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "additionalIncomeAndDeduct" => $additional_income_and_deduct_money_monthly_driver_date,
                            "driverCode" => $driver_code,
                            "licensePlate" => $license_plate,
                            "workingDay" => $working_day,
                            "incomePerTrip" => $income_per_trip,
                            "propertyInsuranceDeduction" => $property_insurance_deduction,
                            "deductMoneyFromBorrowing" => $deduct_money_from_borrowing,
                            "deductionAccidentInsurance" => $deduction_accident_insurance,
                            "deductOtherMoney" => $deduct_other_money,
                            "totalDeduction" => $total_deduction,
                            "createBy" => $create_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAdditionalIncomeAndDeductMoneyMonthlyDriver_arr['data'],
                        'responseCode' => ErrorCode::SUCCESS,
                        'message' => 'success',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                } else {
                    $res = array(
                        'responseCode' => ErrorCode::NOT_FOUND_TIRE_HISTORY,
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
        case ActionCode::CREATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER) {
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

                if (!isset($data->additionalIncomeAndDeduct) || trim($data->additionalIncomeAndDeduct) == null || trim($data->additionalIncomeAndDeduct) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid additionalIncomeAndDeduct'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->additionalIncomeAndDeduct)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format additionalIncomeAndDeduct'
                        ));
                        return;
                    }
                }

                if (trim(!isset($data->driverCode)) || trim($data->driverCode) == null || trim($data->driverCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driverCode'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (isset($data->workingDay) && ($data->workingDay != null || $data->workingDay != "")) {
                    if (!ValidateDate::checkDateFormat($data->workingDay)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid workingDay'
                        ));
                        return;
                    }
                }


                if (!isset($data->incomePerTrip) || !is_int($data->incomePerTrip) || $data->incomePerTrip < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid incomePerTrip'
                    ));
                    return;
                }


                if (isset($data->propertyInsuranceDeduction) && (!is_int($data->propertyInsuranceDeduction) || $data->propertyInsuranceDeduction < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid propertyInsuranceDeduction'
                    ));
                    return;
                }

                if (isset($data->deductMoneyFromBorrowing) && (!is_int($data->deductMoneyFromBorrowing) || $data->deductMoneyFromBorrowing < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductMoneyFromBorrowing'
                    ));
                    return;
                }

                if (isset($data->deductionAccidentInsurance) && (!is_int($data->deductionAccidentInsurance) || $data->deductionAccidentInsurance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductionAccidentInsurance'
                    ));
                    return;
                }

                if (isset($data->deductOtherMoney) && (!is_int($data->deductOtherMoney) || $data->deductOtherMoney < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductOtherMoney'
                    ));
                    return;
                }

                if (isset($data->totalDeduction) && (!is_int($data->totalDeduction) || $data->totalDeduction < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalDeduction'
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
                $additionalIncomeAndDeduct = isset($data->additionalIncomeAndDeduct) ? trim($data->additionalIncomeAndDeduct) : null;
                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $workingDay = isset($data->workingDay) ? trim($data->workingDay) : null;
                $incomePerTrip = isset($data->incomePerTrip) ? trim($data->incomePerTrip) : null;
                $propertyInsuranceDeduction = isset($data->propertyInsuranceDeduction) ? trim($data->propertyInsuranceDeduction) : null;
                $deductMoneyFromBorrowing = isset($data->deductMoneyFromBorrowing) ? trim($data->deductMoneyFromBorrowing) : null;
                $deductionAccidentInsurance = isset($data->deductionAccidentInsurance) ? trim($data->deductionAccidentInsurance) : null;
                $deductOtherMoney = isset($data->deductOtherMoney) ? trim($data->deductOtherMoney) : null;
                $totalDeduction = isset($data->totalDeduction) ? trim($data->totalDeduction) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $getAdditionalIncomeAndDeductMoneyMonthlyDriver = $AdditionalIncomeAndDeductMoneyMonthlyDriver->getAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $driverCode, $licensePlate);

                if ($getAdditionalIncomeAndDeductMoneyMonthlyDriver->rowCount() > 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER_FAIL,
                        'message' => 'The driverCode and licensePlate is already in the system, Please use other driverCode and licensePlate'
                    ));
                    http_response_code(200);
                    return;
                }

                $result = $AdditionalIncomeAndDeductMoneyMonthlyDriver->createNewAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $additionalIncomeAndDeduct, $driverCode, $licensePlate, $workingDay, $incomePerTrip, $propertyInsuranceDeduction, $deductMoneyFromBorrowing, $deductionAccidentInsurance, $deductOtherMoney, $totalDeduction, $createBy);

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
                        'responseCode' => ErrorCode::CREATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER_FAIL,
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
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::UPDATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER) {
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


                if (!isset($data->additionalIncomeAndDeduct) || trim($data->additionalIncomeAndDeduct) == null || trim($data->additionalIncomeAndDeduct) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid additionalIncomeAndDeduct'
                    ));
                    return;
                } else {
                    if (!ValidateDate::checkDateFormat($data->additionalIncomeAndDeduct)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid format additionalIncomeAndDeduct'
                        ));
                        return;
                    }
                }

                if (trim(!isset($data->driverCode)) || trim($data->driverCode) == null || trim($data->driverCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid driverCode'
                    ));
                    return;
                }

                if (trim(!isset($data->licensePlate)) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }

                if (isset($data->workingDay) && ($data->workingDay != null || $data->workingDay != "")) {
                    if (!ValidateDate::checkDateFormat($data->workingDay)) {
                        echo json_encode(array(
                            'responseCode' => ErrorCode::INVALID_DATA_SEND,
                            'message' => 'Invalid workingDay'
                        ));
                        return;
                    }
                }


                if (!isset($data->incomePerTrip) || !is_int($data->incomePerTrip) || $data->incomePerTrip < 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid incomePerTrip'
                    ));
                    return;
                }


                if (isset($data->propertyInsuranceDeduction) && (!is_int($data->propertyInsuranceDeduction) || $data->propertyInsuranceDeduction < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid propertyInsuranceDeduction'
                    ));
                    return;
                }

                if (isset($data->deductMoneyFromBorrowing) && (!is_int($data->deductMoneyFromBorrowing) || $data->deductMoneyFromBorrowing < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductMoneyFromBorrowing'
                    ));
                    return;
                }

                if (isset($data->deductionAccidentInsurance) && (!is_int($data->deductionAccidentInsurance) || $data->deductionAccidentInsurance < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductionAccidentInsurance'
                    ));
                    return;
                }

                if (isset($data->deductOtherMoney) && (!is_int($data->deductOtherMoney) || $data->deductOtherMoney < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid deductOtherMoney'
                    ));
                    return;
                }

                if (isset($data->totalDeduction) && (!is_int($data->totalDeduction) || $data->totalDeduction < 0)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid totalDeduction'
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
                $additionalIncomeAndDeduct = isset($data->additionalIncomeAndDeduct) ? trim($data->additionalIncomeAndDeduct) : null;
                $driverCode = isset($data->driverCode) ? trim($data->driverCode) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $workingDay = isset($data->workingDay) ? trim($data->workingDay) : null;
                $incomePerTrip = isset($data->incomePerTrip) ? trim($data->incomePerTrip) : null;
                $propertyInsuranceDeduction = isset($data->propertyInsuranceDeduction) ? trim($data->propertyInsuranceDeduction) : null;
                $deductMoneyFromBorrowing = isset($data->deductMoneyFromBorrowing) ? trim($data->deductMoneyFromBorrowing) : null;
                $deductionAccidentInsurance = isset($data->deductionAccidentInsurance) ? trim($data->deductionAccidentInsurance) : null;
                $deductOtherMoney = isset($data->deductOtherMoney) ? trim($data->deductOtherMoney) : null;
                $totalDeduction = isset($data->totalDeduction) ? trim($data->totalDeduction) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;


                $getAdditionalIncomeAndDeductMoneyMonthlyDriver = $AdditionalIncomeAndDeductMoneyMonthlyDriver->getAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $driverCode, $licensePlate);


                if ($getAdditionalIncomeAndDeductMoneyMonthlyDriver->rowCount() > 0) {
                    $result = $AdditionalIncomeAndDeductMoneyMonthlyDriver->updateAdditionalIncomeAndDeductMoneyMonthlyDriver($conn, $additionalIncomeAndDeduct, $driverCode, $licensePlate, $workingDay, $incomePerTrip, $propertyInsuranceDeduction, $deductMoneyFromBorrowing, $deductionAccidentInsurance, $deductOtherMoney, $totalDeduction, $updateBy);
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER_FAIL,
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
                http_response_code(200);
                echo json_encode(array(
                    'responseCode' => ErrorCode::UPDATE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER_FAIL,
                    'message' => 'The Perfrom Bill Code is not found in the system, Please use other Perfrom Bill Code'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_ADDITIONAL_INCOME_AND_DEDUCT_MONEY_MONTHLY_OF_DRIVER) {
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
                http_response_code(200);
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
