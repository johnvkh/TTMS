<?php

require_once '../../includes/Autoload.php';

$validateString = new ValidateString();
$validateDate = new ValidateDate();
$db = new DatabaseConfig();
$conn = $db->connection();
$RepairIn = new RepairInModel();

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
        case ActionCode::GET_ALL_REPAIR_IN:
            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_ALL_REPAIR_IN) {
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

                // if (isset($data->fromRepairDate) && !empty($data->toRepairDate)) {
                //     if (!ValidateDate::checkDateFormat($data->fromRepairDate)) {
                //         echo json_encode(array(
                //             'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //             'message' => 'Invalid fromRepairDate'
                //         ));
                //         return;
                //     }
                // }
                // if (isset($data->toRepairDate) && !empty($data->toRepairDate)) {
                //     if (!ValidateDate::checkDateFormat($data->toRepairDate)) {
                //         echo json_encode(array(
                //             'responseCode' => ErrorCode::INVALID_DATA_SEND,
                //             'message' => 'Invalid toRepairDate'
                //         ));
                //         return;
                //     }
                // }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;


                $getAllRepairIn = $RepairIn->getAllRepairIn($conn);
                $num_row = $getAllRepairIn->rowCount();
                $getAllRepairIn_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getAllRepairIn->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "repairInBillCode" => $repair_in_bill_code,
                            "licensePlate" => $license_plate,
                            "dateRepair" => $date_repair,
                            "date" => $date_expense,
                            "totalPrice" => $total_price,
                            "createBy" => $cerate_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getAllRepairIn_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getAllRepairIn_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
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
        case ActionCode::GET_REPAIR_IN:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_IN) {
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

                if (!isset($data->repairBillCode) || trim($data->repairBillCode) == null || trim($data->repairBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairBillCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairBillCode = isset($data->repairBillCode) ? trim($data->repairBillCode) : null;

                $getRepairIn = $RepairIn->getRepairIn($conn, $repairBillCode);
                $num_row = $getRepairIn->rowCount();
                $getRepairIn_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getRepairIn->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "id" => $id,
                            "repairInBillCode" => $repair_in_bill_code,
                            "licensePlate" => $license_plate,
                            "dateRepair" => $date_repair,
                            "date" => $date_expense,
                            "totalPrice" => $total_price,
                            "createBy" => $cerate_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getRepairIn_arr['data'], $data);
                    }
                    $res = array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getRepairIn_arr['data'],
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
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN_BILL,
                        'message' => 'Not Found Data',
                        'timestamp' => $timestamp,
                        'actionCode' => $actionCode,
                        'actionNodeId' => $actionNodeId
                    );
                    http_response_code(200);
                    echo json_encode($res);
                    return;
                }
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }
            break;
        case ActionCode::GET_REPAIR_IN_REPORT_DAILY_REPAIR:

        //     try {
        //         if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_IN_REPORT_DAILY_REPAIR) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid Action Code'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid Action Node'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid whichPart'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->fromDate) || trim($data->fromDate) == null || trim($data->fromDate) == "" || !ValidateDate::checkDateFormat($data->fromDate)) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid fromDate'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->toDate) || trim($data->toDate) == null || trim($data->toDate) == "" || !ValidateDate::checkDateFormat($data->fromDate)) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid toDate'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->fromBill) || trim($data->fromBill) == null || trim($data->fromBill) == "") {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid fromBill'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->toBill) || trim($data->toBill) == null || trim($data->toBill) == "") {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid toBill'
        //             ));
        //             return;
        //         }
        //         $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
        //         $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
        //         $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
        //         $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
        //         $toDate = isset($data->toDate) ? trim($data->toDate) : null;
        //         $fromBill = isset($data->fromBill) ? trim($data->fromBill) : null;
        //         $toBill = isset($data->toBill) ? trim($data->toBill) : null;
        //         $getRepairInByRepairBillCode = $RepairIn->reportRepairInDailyRepair($conn, $whichPart, $fromDate, $toDate, $fromBill, $toBill);
        //         $num_row = $getRepairInByRepairBillCode->rowCount();
        //         $getRepairInByRepairBillCode_arr['data'] = [];
        //         if ($num_row > 0) {
        //             while ($row = $getRepairInByRepairBillCode->fetch(PDO::FETCH_ASSOC)) {
        //                 extract($row);
        //                 $data = array(
        //                     "whichPart" => $which_part,
        //                     "whichPartName" => $which_part_name,
        //                     "repairBillCode" => $repair_bill_code,
        //                     "licensePlate" => $license_plate,
        //                     "truckTypeName" => $truck_type_name,
        //                     "driverName" => $driver_name,
        //                     "repairDate" => $repair_date,
        //                     "truckMileNumber" => $truck_mile_number,
        //                     "repairTypeId" => $repair_type_id,
        //                     "repairTypeName" => $repair_type_name,
        //                     "createBy" => $create_by,
        //                     "createDate" => $create_date,
        //                     "updateBy" => $update_by,
        //                     "updateDate" => $update_date
        //                 );
        //                 array_push($getRepairInByRepairBillCode_arr['data'], $data);
        //             }
        //             $res = array(
        //                 // 'totalResult' => (int)$total_result,
        //                 'result' => $num_row,
        //                 'data' => $getRepairInByRepairBillCode_arr['data'],
        //                 'responseCode' => ErrorCode::SUCCESS,
        //                 'message' => 'success',
        //                 'timestamp' => $timestamp,
        //                 'actionCode' => $actionCode,
        //                 'actionNodeId' => $actionNodeId
        //             );
        //             http_response_code(200);
        //             echo json_encode($res);
        //             return;
        //         } else {
        //             $res = array(
        //                 'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
        //                 'message' => 'Not Found Data',
        //                 'timestamp' => $timestamp,
        //                 'actionCode' => $actionCode,
        //                 'actionNodeId' => $actionNodeId
        //             );
        //             echo json_encode($res);
        //             http_response_code(200);
        //             return;
        //         }
        //     } catch (Exception $ex) {
        //         echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
        //         http_response_code(200);
        //     } finally {
        //         $db->disconnection();
        //     }
        //     break;
        // case ActionCode::GET_REPAIR_IN_BY_REPAIR_BILL_CODE:
        //     try {
        //         if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::GET_REPAIR_IN_BY_REPAIR_BILL_CODE) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid Action Code'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->actionNodeId) || $data->actionNodeId == null || $data->actionNodeId == "" || !$validateString->check_node_id($data->actionNodeId)) {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid Action Node'
        //             ));
        //             return;
        //         }
        //         if (!isset($data->repairBillCode) || trim($data->repairBillCode) == null || trim($data->repairBillCode) == "") {
        //             echo json_encode(array(
        //                 'responseCode' => ErrorCode::INVALID_DATA_SEND,
        //                 'message' => 'Invalid repairBillCode'
        //             ));
        //             return;
        //         }
        //         $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
        //         $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
        //         $repairBillCode = isset($data->repairBillCode) ? trim($data->repairBillCode) : null;
        //         $getRepairInByRepairBillCode = $RepairIn->getRepairInByRepairBillCode($conn, $repairBillCode);
        //         $num_row = $getRepairInByRepairBillCode->rowCount();
        //         $getRepairInByRepairBillCode_arr['data'] = [];
        //         if ($num_row > 0) {
        //             while ($row = $getRepairInByRepairBillCode->fetch(PDO::FETCH_ASSOC)) {
        //                 extract($row);
        //                 $data = array(
        //                     "whichPart" => $which_part,
        //                     "whichPartName" => $which_part_name,
        //                     "repairBillCode" => $repair_bill_code,
        //                     "licensePlate" => $license_plate,
        //                     "truckTypeName" => $truck_type_name,
        //                     "driverName" => $driver_name,
        //                     "repairDate" => $repair_date,
        //                     "truckMileNumber" => $truck_mile_number,
        //                     "repairTypeId" => $repair_type_id,
        //                     "repairTypeName" => $repair_type_name,
        //                     "createBy" => $create_by,
        //                     "createDate" => $create_date,
        //                     "updateBy" => $update_by,
        //                     "updateDate" => $update_date
        //                 );
        //                 array_push($getRepairInByRepairBillCode_arr['data'], $data);
        //             }
        //             $res = array(
        //                 // 'totalResult' => (int)$total_result,
        //                 'result' => $num_row,
        //                 'data' => $getRepairInByRepairBillCode_arr['data'],
        //                 'responseCode' => ErrorCode::SUCCESS,
        //                 'message' => 'success',
        //                 'timestamp' => $timestamp,
        //                 'actionCode' => $actionCode,
        //                 'actionNodeId' => $actionNodeId
        //             );
        //             http_response_code(200);
        //             echo json_encode($res);
        //             return;
        //         } else {
        //             $res = array(
        //                 'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
        //                 'message' => 'Not Found Data',
        //                 'timestamp' => $timestamp,
        //                 'actionCode' => $actionCode,
        //                 'actionNodeId' => $actionNodeId
        //             );
        //             echo json_encode($res);
        //             http_response_code(200);
        //             return;
        //         }
        //     } catch (Exception $ex) {
        //         echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
        //         http_response_code(200);
        //     } finally {
        //         $db->disconnection();
        //     }
        //     break;
        case ActionCode::CREATE_REPAIR_IN:
            try {
                // print_r($data->truckPartsDetails[0]);

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REPAIR_IN) {
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

                if (!isset($data->dateExpense) || trim($data->dateExpense) == null || trim($data->dateExpense) == "" || !ValidateDate::checkDateFormat($data->dateExpense)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid dateExpense'
                    ));
                    return;
                }

                if (!isset($data->repairInBillCode) || trim($data->repairInBillCode) == null || trim($data->repairInBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairInBillCode'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }


                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }


                if (!isset($data->repairOrderDate) || trim($data->repairOrderDate) == null || trim($data->repairOrderDate) == "" || !ValidateDate::checkDateFormat($data->repairOrderDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOrderDate'
                    ));
                    return;
                }

                if (!isset($data->repairedDate) || trim($data->repairedDate) == null || trim($data->repairedDate) == "" || !ValidateDate::checkDateFormat($data->repairedDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairedDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || trim($data->mileNumber) == null || trim($data->mileNumber) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }



                if (!isset($data->repairTypeId) || trim($data->repairTypeId) == null || trim($data->repairTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }

                if (isset($data->truckPartsDetails) && !is_array($data->truckPartsDetails)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckPartsDetails'
                    ));
                    return;
                } else {
                    foreach ($data->truckPartsDetails as $objKey) {
                        if (!property_exists($objKey, "truckPartsCode")) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid truckPartsCode'
                            ));
                            return;
                        }
                        if (!property_exists($objKey, "list")) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid list'
                            ));
                            return;
                        }
                        if (!property_exists($objKey, "quantityPerUnit")) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid quantityPerUnit'
                            ));
                            return;
                        }
                        if (!property_exists($objKey, "pricePerUnit")) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid pricePerUnit'
                            ));
                            return;
                        }
                        if (!property_exists($objKey, "price")) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid price'
                            ));
                            return;
                        }
                    }
                }


                if (!isset($data->createBy) || trim($data->createBy) == null || trim($data->createBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $dateExpense = isset($data->dateExpense) ? trim($data->dateExpense) : null;
                $repairInBillCode = isset($data->repairInBillCode) ? trim($data->repairInBillCode) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairOrderDate = isset($data->repairOrderDate) ? trim($data->repairOrderDate) : null;
                $repairedDate = isset($data->repairedDate) ? trim($data->repairedDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $repairedDesc = isset($data->repairedDesc) ? trim($data->repairedDesc) : null;
                $truckPartsCode = isset($data->truckPartsCode) ? trim($data->truckPartsCode) : null;

                // truckPartsDetails

                $list = isset($data->list) ? trim($data->list) : null;
                $quantityPerUnit = isset($data->quantityPerUnit) ? trim($data->quantityPerUnit) : null;
                $pricePerUnit = isset($data->pricePerUnit) ? trim($data->pricePerUnit) : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $repairFacilityId = isset($data->repairFacilityId) ? trim($data->repairFacilityId) : null;
                $note = isset($data->note) ? trim($data->note) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkBillCodeInRepairIn = $RepairIn->checkRepairBillCodeInRepairIn($conn, $repairInBillCode);
                if ($checkBillCodeInRepairIn->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_REPAIR_IN_FAIL,
                        'message' => 'The repair bill code is already in the system, Please use other repair bill code '
                    ));
                    return;
                }

                $result = $RepairIn->createNewRepairIn($conn, $dateExpense, $repairInBillCode, $whichPart, $licensePlate, $dateRepair, $dateRepairSuccess, $mileNumber, $repairTypeId, $truckPartsCode, $list, $quantityPerUnit, $pricePerUnit, $totalPrice, $repairFacilityId, $note, $createBy);
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
                        'responseCode' => ErrorCode::CREATE_REPAIR_TYPE_FAIL,
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
        case ActionCode::CREATE_REPAIR_IN_TEST:
            try {

                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::CREATE_REPAIR_IN_TEST) {
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

                if (!isset($data->dateExpense) || trim($data->dateExpense) == null || trim($data->dateExpense) == "" || !ValidateDate::checkDateFormat($data->dateExpense)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid dateExpense'
                    ));
                    return;
                }

                if (!isset($data->repairInBillCode) || trim($data->repairInBillCode) == null || trim($data->repairInBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairInBillCode'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }


                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }


                if (!isset($data->repairOrderDate) || trim($data->repairOrderDate) == null || trim($data->repairOrderDate) == "" || !ValidateDate::checkDateFormat($data->repairOrderDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOrderDate'
                    ));
                    return;
                }

                if (!isset($data->repairedDate) || trim($data->repairedDate) == null || trim($data->repairedDate) == "" || !ValidateDate::checkDateFormat($data->repairedDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairedDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || trim($data->mileNumber) == null || trim($data->mileNumber) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }



                if (!isset($data->repairTypeId) || trim($data->repairTypeId) == null || trim($data->repairTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }

                if (isset($data->truckPartsDetails) && !is_array($data->truckPartsDetails)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckPartsDetails'
                    ));
                    return;
                } else {
                    foreach ($data->truckPartsDetails as $item) {
                        if (!isset($item->truckPartsCode) || empty($item->truckPartsCode)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid truckPartsCode'
                            ));
                            return;
                        }
                        if (!isset($item->list) || empty($item->list)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid list'
                            ));
                            return;
                        }
                        if (!isset($item->quantityPerUnit) || empty($item->quantityPerUnit)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid quantityPerUnit'
                            ));
                            return;
                        }
                        if (!isset($item->pricePerUnit) || empty($item->pricePerUnit)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid pricePerUnit'
                            ));
                            return;
                        }
                        if (!isset($item->price) || empty($item->price)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid price'
                            ));
                            return;
                        }
                    }
                }

                if (!isset($data->createBy) || empty($data->createBy)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid createBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $dateExpense = isset($data->dateExpense) ? trim($data->dateExpense) : null;
                $repairInBillCode = isset($data->repairInBillCode) ? trim($data->repairInBillCode) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairOrderDate = isset($data->repairOrderDate) ? trim($data->repairOrderDate) : null;
                $repairedDate = isset($data->repairedDate) ? trim($data->repairedDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $repairedDesc = isset($data->repairedDesc) ? trim($data->repairedDesc) : null;
                $truckPartsDetails = isset($data->truckPartsDetails) ? $data->truckPartsDetails : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $repairFacilityId = isset($data->repairFacilityId) ? trim($data->repairFacilityId) : null;
                $createBy = isset($data->createBy) ? trim($data->createBy) : null;

                $checkBillCodeInRepairIn = $RepairIn->checkRepairBillCodeInRepairIn($conn, $repairInBillCode);
                if ($checkBillCodeInRepairIn->rowCount() > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::CREATE_REPAIR_IN_FAIL,
                        'message' => 'The repair bill code is already in the system, Please use other repair bill code '
                    ));
                    return;
                }

                $result = $RepairIn->createNewRepairInTwo(
                        $conn, $dateExpense, $repairInBillCode, $whichPart, $licensePlate, $repairOrderDate, $repairedDate, $mileNumber, $repairTypeId, $repairedDesc, $truckPartsDetails, $totalPrice, $repairFacilityId, $createBy
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
                        'responseCode' => ErrorCode::CREATE_REPAIR_TYPE_FAIL,
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
        case ActionCode::UPDATE_REPAIR_IN:

            try {
                if (!isset($data->actionCode) || $data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::UPDATE_REPAIR_IN) {
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
                if (!isset($data->dateExpense) || trim($data->dateExpense) == null || trim($data->dateExpense) == "" || !ValidateDate::checkDateFormat($data->dateExpense)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid dateExpense'
                    ));
                    return;
                }

                if (!isset($data->repairInBillCode) || trim($data->repairInBillCode) == null || trim($data->repairInBillCode) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairInBillCode'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || trim($data->whichPart) == null || trim($data->whichPart) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }


                if (!isset($data->licensePlate) || trim($data->licensePlate) == null || trim($data->licensePlate) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid licensePlate'
                    ));
                    return;
                }


                if (!isset($data->repairOrderDate) || trim($data->repairOrderDate) == null || trim($data->repairOrderDate) == "" || !ValidateDate::checkDateFormat($data->repairOrderDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairOrderDate'
                    ));
                    return;
                }

                if (!isset($data->repairedDate) || trim($data->repairedDate) == null || trim($data->repairedDate) == "" || !ValidateDate::checkDateFormat($data->repairedDate)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairedDate'
                    ));
                    return;
                }

                if (!isset($data->mileNumber) || trim($data->mileNumber) == null || trim($data->mileNumber) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid mileNumber'
                    ));
                    return;
                }



                if (!isset($data->repairTypeId) || trim($data->repairTypeId) == null || trim($data->repairTypeId) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairTypeId'
                    ));
                    return;
                }

                if (isset($data->truckPartsDetails) && !is_array($data->truckPartsDetails)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckPartsDetails'
                    ));
                    return;
                } else {
                    foreach ($data->truckPartsDetails as $item) {
                        if (!isset($item->truckPartsCode) || empty($item->truckPartsCode)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid truckPartsCode'
                            ));
                            return;
                        }
                        if (!isset($item->list) || empty($item->list)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid list'
                            ));
                            return;
                        }
                        if (!isset($item->quantityPerUnit) || empty($item->quantityPerUnit)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid quantityPerUnit'
                            ));
                            return;
                        }
                        if (!isset($item->pricePerUnit) || empty($item->pricePerUnit)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid pricePerUnit'
                            ));
                            return;
                        }
                        if (!isset($item->price) || empty($item->price)) {
                            echo json_encode(array(
                                'responseCode' => ErrorCode::INVALID_DATA_SEND,
                                'message' => 'Invalid price'
                            ));
                            return;
                        }
                    }
                }


                if (!isset($data->updateBy) || trim($data->updateBy) == null || trim($data->updateBy) == "") {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid updateBy'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $saveRepairInId = isset($data->saveRepairInId) ? trim($data->saveRepairInId) : null;
                $dateExpense = isset($data->dateExpense) ? trim($data->dateExpense) : null;
                $repairInBillCode = isset($data->repairInBillCode) ? trim($data->repairInBillCode) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;
                $licensePlate = isset($data->licensePlate) ? trim($data->licensePlate) : null;
                $repairOrderDate = isset($data->repairOrderDate) ? trim($data->repairOrderDate) : null;
                $repairedDate = isset($data->repairedDate) ? trim($data->repairedDate) : null;
                $mileNumber = isset($data->mileNumber) ? trim($data->mileNumber) : null;
                $repairTypeId = isset($data->repairTypeId) ? trim($data->repairTypeId) : null;
                $repairedDesc = isset($data->repairedDesc) ? trim($data->repairedDesc) : null;
                $truckPartsDetails = isset($data->truckPartsDetails) ? $data->truckPartsDetails : null;
                $totalPrice = isset($data->totalPrice) ? trim($data->totalPrice) : null;
                $repairFacilityId = isset($data->repairFacilityId) ? trim($data->repairFacilityId) : null;
                $updateBy = isset($data->updateBy) ? trim($data->updateBy) : null;

                $getRepairIn = $RepairIn->checkRepairBillCodeInRepairIn($conn, $repairInBillCode);

                if ($getRepairIn->rowCount() > 0) {
                    $result = $RepairIn->updateRepairIn(
                            $conn, $saveRepairInId, $dateExpense, $repairInBillCode, $whichPart, $licensePlate, $repairOrderDate, $repairedDate, $mileNumber, $repairTypeId, $repairedDesc, $truckPartsDetails, $totalPrice, $repairFacilityId, $updateBy
                    );
                    if (!$result) {
                        $res = array(
                            'responseCode' => ErrorCode::UPDATE_REPAIR_IN_FAIL,
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
                    'responseCode' => ErrorCode::UPDATE_REPAIR_IN_FAIL,
                    'message' => 'The repair bill code is not found in the system, Please use other repair bill code'
                ));
                return;
            } catch (Exception $ex) {
                http_response_code(200);
                echo 'Exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage();
            } finally {
                $db->disconnection();
            }

            break;
        case ActionCode::DELETE_REPAIR_IN:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REPAIR_IN) {
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
                if (!isset($data->id) || empty($data->id)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid id'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $id = isset($data->id) ? trim($data->id) : null;
                $checkRepairIn = $RepairIn->checkBeforeDelete($conn, $id);
                if ($checkRepairIn->rowCount() <= 0) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
                        'message' => 'not found id in the systems'
                    ));
                    return;
                }
                $delete_result = $RepairIn->deleteRepairIn($conn, $id);
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
                    'responseCode' => ErrorCode::DELETE_REPAIR_IN_FAIL,
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
        case ActionCode::DELETE_REPAIR_IN_TRUCK_PARTS:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::DELETE_REPAIR_IN_TRUCK_PARTS) {
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

                if (!isset($data->repairInBillId) || empty($data->repairInBillId)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid repairInBillId'
                    ));
                    return;
                }

                if (!isset($data->truckPartsCode) || empty($data->truckPartsCode)) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid truckPartsCode'
                    ));
                    return;
                }

                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $repairInBillId = isset($data->repairInBillId) ? trim($data->repairInBillId) : null;
                $truckPartsCode = isset($data->truckPartsCode) ? trim($data->truckPartsCode) : null;

                $getData = $RepairIn->getRepairInTruckPartByRepairInIdAndTruckPartsCode($conn, $truckPartsCode, $repairInBillId);
                if ($getData->rowCount() <= 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
                        'message' => 'not found data in the system.'
                    ));
                    return;
                }
                $delete = $RepairIn->deleteRepairInTruckParts($conn, $truckPartsCode, $repairInBillId);
                if ($delete) {
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
                    'responseCode' => ErrorCode::DELETE_REPAIR_IN_TRUCK_PARTS_FAIL,
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
        case ActionCode::REPORT_DAILY_TRUCK_REPAIR_IN:
            try {
                if ($data->actionCode == null || $data->actionCode == "" || $data->actionCode != ActionCode::REPORT_DAILY_TRUCK_REPAIR_IN) {
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

                if (!isset($data->fromDate) || empty($data->fromDate || !ValidateDate::checkDateFormat($data->fromDate))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid fromDate'
                    ));
                    return;
                }

                if (!isset($data->toDate) || empty($data->toDate || !ValidateDate::checkDateFormat($data->toDate))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid toDate'
                    ));
                    return;
                }

                if (!isset($data->whichPart) || !in_array($data->whichPart, array(0, 1))) {
                    echo json_encode(array(
                        'responseCode' => ErrorCode::INVALID_DATA_SEND,
                        'message' => 'Invalid whichPart'
                    ));
                    return;
                }


                $actionCode = isset($data->actionCode) ? trim($data->actionCode) : null;
                $actionNodeId = isset($data->actionNodeId) ? trim($data->actionNodeId) : null;
                $fromDate = isset($data->fromDate) ? trim($data->fromDate) : null;
                $toDate = isset($data->toDate) ? trim($data->toDate) : null;
                $whichPart = isset($data->whichPart) ? trim($data->whichPart) : null;

                $getReportDailyRepairIn = $RepairIn->reportDailyRepairIn($conn, $fromDate, $toDate, $whichPart);
                $num_row = $getReportDailyRepairIn->rowCount();
                $getReportDailyRepairIn_arr['data'] = [];
                if ($num_row > 0) {
                    while ($row = $getReportDailyRepairIn->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        $data = array(
                            "date" => $date_expense,
                            "repairInBillCode" => $repair_in_bill_code,
                            "licensePlate" => $license_plate,
                            "repairOrderDate" => $date_repair,
                            "repairedDate" => $date_repair_success,
                            "repairTypeId" => $repair_type_id,
                            "repairTypeName" => null,
                            "desc" => $note,
                            "mechanicName" => null,
                            "truckPartsCode" => $code,
                            "list" => $list,
                            "quantityPerUnit" => $qty_per_unit,
                            "pricePerUnit" => $price_per_unit,
                            // "price" => $price,
                            "createBy" => $cerate_by,
                            "createDate" => $create_date,
                            "updateBy" => $update_by,
                            "updateDate" => $update_date
                        );
                        array_push($getReportDailyRepairIn_arr['data'], $data);
                    }
                    http_response_code(200);
                    echo json_encode(array(
                        // 'totalResult' => (int)$total_result,
                        'result' => $num_row,
                        'data' => $getReportDailyRepairIn_arr['data'],
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
                    'responseCode' => ErrorCode::NOT_FOUND_REPAIR_IN,
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
