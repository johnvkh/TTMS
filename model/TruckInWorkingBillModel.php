<?php

class TruckInWorkingBillModel
{


    function __construct()
    {
    }

    public function getAllTruckInWorkingBill($conn, $fromDate, $toDate)
    {
        $sql = "select 
        a.id,
        a.bill_release_truck_in_no,
        b.go_date,
        b.back_date,
        b.license_plate,
        b.driver_first,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_first) as driver_name_first,
        b.driver_second,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_second) as driver_name_sec,
        b.date_shipping,
        b.product_code,
        (select c.product_name from transported_goods c where c.product_code= b.product_code) as product_name,
        b.destination,
        (select concat(e.start, ' - ', e.end) from set_way_allowance_transport_rate e where e.way_code = b.destination) as destination_name,
        a.wight,
        a.ton_per,
        a.total_price,
        a.create_by,
        a.create_date,
        a.update_by,
        a.update_date
        from truck_in_working_bill a, release_bill_truck_in b where a.bill_release_truck_in_no = b.bill_release_truck_in_no order by a.id desc";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getTruckInWorkingBill($conn,  $truckInWorkingBillId)
    {
        $sql = "select 
        a.id,
        a.bill_release_truck_in_no,
        b.go_date,
        b.back_date,
        b.license_plate,
        b.driver_first,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_first) as driver_name_first,
        b.driver_second,
        (select concat(firstname, ' ', lastname) from driver d where d.driver_code = b.driver_second) as driver_name_sec,
        b.date_shipping,
        b.product_code,
        (select c.product_name from transported_goods c where c.product_code = b.product_code) as product_name,
        b.destination,
        (select concat(e.start, ' - ', e.end) from set_way_allowance_transport_rate e where e.way_code = b.destination) as destination_name,
        a.wight,
        a.ton_per,
        a.total_price,
        a.create_by,
        a.create_date,
        a.update_by,
        a.update_date
        from truck_in_working_bill a, release_bill_truck_in b where a.bill_release_truck_in_no = b.bill_release_truck_in_no and a.id = :truck_in_working_bill_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":truck_in_working_bill_id", $truckInWorkingBillId);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getReleaseTruckInBillByBillReleaseTruckInNo($conn, $billReleaseTruckInNo)
    {

        $sql = "SELECT 
        a.bill_release_truck_in_no,
        a.go_date,
        a.back_date,
        a.license_plate,
        a.driver_first,
        a.driver_second,
        a.date_shipping,
        a.product_code,
        a.destination
        FROM release_bill_truck_in a where a.bill_release_truck_in_no = :bill_release_truck_in_no";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_in_no", $billReleaseTruckInNo);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function cerateNewTruckInWorkingBill(
        $conn,
        $billReleaseTruckInNo,
        $weight,
        $ton_per,
        $totalPrice,
        $createBy
    ) {

        $sql = "insert into truck_in_working_bill (bill_release_truck_in_no, wight, ton_per, total_price, create_by, create_date) "
            . "values (:bill_release_truck_in_no, :wight, :ton_per, :total_price, :create_by, sysdate())";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_in_no", $billReleaseTruckInNo);
            $stmt->bindParam(":wight", $weight);
            $stmt->bindParam(":ton_per", $ton_per);
            $stmt->bindParam(":total_price", $totalPrice);
            $stmt->bindParam(":create_by", $createBy);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateTruckWorkingBill(
        $conn,
        $truckInWorkingBillId,
        $weight,
        $ton_per,
        $totalPrice,
        $updateBy
    ) {
        $sql = "update truck_in_working_bill set wight = :wight, ton_per = :ton_per, total_price = :total_price, update_by = :update_by, update_date = sysdate() where id = :id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $truckInWorkingBillId);
            $stmt->bindParam(":wight", $weight);
            $stmt->bindParam(":ton_per", $ton_per);
            $stmt->bindParam(":total_price", $totalPrice);
            $stmt->bindParam(":update_by", $updateBy);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkBillReleaseTruckInNo($conn, $billReleaseTruckInNo)
    {
        $sql = "select bill_release_truck_in_no from truck_in_working_bill where bill_release_truck_in_no = :bill_release_truck_in_no";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":bill_release_truck_in_no", $billReleaseTruckInNo);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }

    public function checkTruckInWorkingBillId($conn, $truckInWorkingBillId)
    {
        $sql = "select id from truck_in_working_bill where id = :truck_in_working_bill_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":truck_in_working_bill_id", $truckInWorkingBillId);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
        return false;
    }
}
