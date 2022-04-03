<?php

class SetWayAllowanceTransportRateModel
{


    function __construct()
    {
    }

    public function getAllSetWayAllowanceTransportRate($conn)
    {
        try {
            $sql = 'SELECT * FROM `set_way_allowance_transport_rate` ORDER BY create_date DESC';
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }



    public function getSetWayAllowanceTransportRateByWayCode($conn, $wayCode)
    {
        try {
            $sql = 'SELECT * FROM `set_way_allowance_transport_rate` WHERE way_code = :way_code';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':way_code', $wayCode);
            if ($stmt->execute()) {
                return $stmt;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewSetWayAllowanceTransportRate(
        $conn,
        $wayCode,
        $start,
        $end,
        $distance,
        $shippingServicePerTon,
        $shippingCostPerTrip,
        $createBy
    ) {

        try {
            $sql = "INSERT INTO `set_way_allowance_transport_rate` (`id`, `way_code`, `start`, `end`, `distance`, `shippingService_per_ton`, `shipping_costs_per_trip`, `create_by`, `create_date`) 
            VALUES (NULL, :way_code, :start, :end, :distance,:shippingService_per_ton,:shipping_costs_per_trip,:create_by,  sysdate());";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':way_code', $wayCode);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->bindParam(':distance', $distance);
            $stmt->bindParam(':shippingService_per_ton', $shippingServicePerTon);
            $stmt->bindParam(':shipping_costs_per_trip', $shippingCostPerTrip);
            $stmt->bindParam(':create_by', $createBy);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateSetWayAllowanceTransportRate(
        $conn,
        $wayCode,
        $start,
        $end,
        $distance,
        $shippingServicePerTon,
        $shippingCostPerTrip,
        $updateBy
    ) {
        try {
            $sql = "
                UPDATE set_way_allowance_transport_rate SET 
                `start` = :start, 
                `end` = :end, 
                `distance` = :distance, 
                `shippingService_per_ton` = :shippingService_per_ton,
                 `shipping_costs_per_trip` = :shipping_costs_per_trip, 
                 `update_by` = :update_by,
                  `update_date` = sysdate()
                  where way_code = :way_code
            ";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->bindParam(':distance', $distance);
            $stmt->bindParam(':shippingService_per_ton', $shippingServicePerTon);
            $stmt->bindParam(':shipping_costs_per_trip', $shippingCostPerTrip);
            $stmt->bindParam(':update_by', $updateBy);
            $stmt->bindParam(':way_code', $wayCode);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteDeliveryLocation($conn, $truckTypeCode)
    {
        try {
            return false;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
