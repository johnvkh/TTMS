<?php

class TireHistoryModel
{
    function __construct()
    {
    }

    public function getTireHistory($conn, $whichPart, $license_plate)
    {
        try {
            $sql = 'select * from tire_history where license_plate=:license_plate  and which_part=:which_part ORDER BY change_tire_date Asc';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':which_part', $whichPart);
            $stmt->bindParam(':license_plate', $license_plate);
            if ($stmt->execute()) {
                return $stmt;
            }
            return 0;
        } catch (Exception $ex) {
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
