<?php

class ReportTruckInScheduleForOilChangeModel {

    function __construct() {
        
    }

    // This report you have to set on script, when Schedule For Oil Change

    public function getAllChangeEngineOil($conn) {
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
