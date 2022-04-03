<?php

class ReportTruckInShippingProductDailyModel
{
    function __construct()
    {
    }

    public function getAllChangeEngineOil($conn, $start_date, $end_date)
    {
        try {
            $sql = "";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
