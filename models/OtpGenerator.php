<?php

class OtpGenerator
{
    public static function add($key, $serial, $manufactureDate)
    {
        $db = Db::getConnection();

        $sql = 'INSERT INTO `generators` (`key`, `serial`, `manufacture_date`) 
        VALUES (:key, :serial, :manufacture_date)';

        $result = $db->prepare($sql);
        $result->bindParam(':key', $key, PDO::PARAM_STR);
        $result->bindParam(':serial', $serial, PDO::PARAM_STR);
        $result->bindParam(':manufacture_date', $manufactureDate, PDO::PARAM_STR);
        return $result->execute();
    }

    public static function findGenerator($serialNumber, $manufactureDate)
    {
        $db = Db::getConnection();

        $sql = 'SELECT `id` FROM `generators` WHERE 
        `manufacture_date` = :manufactureDate and `serial`= :serialNumber';

        $result = $db->prepare($sql);
        $result->bindParam(':serialNumber', $serialNumber, PDO::PARAM_STR);
        $result->bindParam(':manufactureDate', $manufactureDate, PDO::PARAM_STR);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchColumn();
    }

    public static function getUserKey($otpId)
    {
        $db = Db::getConnection();
        $sql = 'SELECT `key` FROM `generators` WHERE `id` = :otpId';

        $result = $db->prepare($sql);
        $result->bindParam(':otpId', $otpId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchColumn();
    }
}