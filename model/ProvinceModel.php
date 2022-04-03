<?php

class ProvinceModel
{

    public function getAllProvinces($conn)
    {
        try {
            $sql = "SELECT province_id, province_name from province order by province_id asc";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getProvinces($conn,  $province_id)
    {
        try {
            $sql = "SELECT province_id, province_name from province where province_id = :province_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":province_id", $province_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function getProvinceByCountry($conn, $countryId)
    {
        $sql = "select * from province where country_id = :country_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":country_id", $countryId);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function checkProvinceNameExists($conn, $province_name)
    {
        try {
            $sql = "select province_name from province where province_name = :province_name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":province_name", $province_name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function createNewProvinces(
        $conn,
        $province_name
    ) {

        try {
            $sql = "insert into province (province_name) values (:province_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':province_name', $province_name);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function updateProvinces(
        $conn,
        $province_id,
        $province_name
    ) {
        try {
            $sql = "UPDATE province SET province_name = :province_name WHERE province_id = :province_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":province_id", $province_id);
            $stmt->bindParam(":province_name", $province_name);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }

    public function deleteProvinces($conn, $province_id)
    {
        try {
            $sql = "DELETE FROM province where province_id = :province_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":province_id", $province_id);
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (Exception $ex) {
            http_response_code(200);
            printf('exception:: ' . __FUNCTION__ . ' -> ' . $ex->getMessage());
        }
    }
}
