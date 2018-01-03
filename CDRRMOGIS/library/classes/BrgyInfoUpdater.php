<?php
include('../form/connection.php');
include('../form/connectionbrgy.php');
include ('../function/functions.php');

class BrgyInfoUpdater {
    protected $brgylist = array();
    protected $brgylistSQL = "SELECT ID, NAME FROM barangay";
    protected $db;
    protected $dbbrgy;

    public function __construct() {
        $this->db = new db();
        $this->dbbrgy = new dbbrgy();
    }

    public function dbSync() {
        $this->brgylist = $this->getBrgyList();
        $result = 0;
        
        foreach($this->brgylist as $barangay) {
            if ($this->isExistingBrgy($barangay["NAME"])) {
                $demoData = $this->getDemographicData($barangay["NAME"]);
                $houseData = $this->getHousingData($barangay["NAME"]);

                $insertResult = $this->insertBarangayData(array(
                    "BRGYID" => $barangay["ID"],
                    "MEN" => $demoData["MEN"],
                    "WOMEN" => $demoData["WOMEN"],
                    "MINORS" => $demoData["MINORS"],
                    "ADULTS" => $demoData["ADULTS"],
                    "PWD" => $demoData["PWD"],
                    "TOTALH" => $houseData["STRONG"] + $houseData["LIGHT"] + $houseData["MIXED"],
                    "STRONGH" => $houseData["STRONG"],
                    "LIGHTH" => $houseData["LIGHT"],
                    "MIXEDH" => $houseData["MIXED"]
                ));

                $result += $insertResult;
            }

        }

        return $result;
    }

    protected function getBrgyList() {
        $brgyArr = array();
        $brgyListResult = $this->db->connection->query($this->brgylistSQL);

        while($brgyListRow = $brgyListResult->fetch_assoc()) {
            array_push($brgyArr, array( "ID" => $brgyListRow["ID"], "NAME" => $brgyListRow["NAME"]));
        }

        return $brgyArr;
    }

    protected function insertBarangayData($barangay) {
        $insertSQL = 
            "INSERT INTO barangay_info (
            BARANGAY, MEN, WOMEN,
            MINORS, ADULTS, PWD,
            T_HOUSES, C_HOUSES, L_HOUSES,
            CL_HOUSES, Area, isFloodProne)
            VALUES (
            {$barangay['BRGYID']}, {$barangay['MEN']}, {$barangay['WOMEN']},
            {$barangay['MINORS']}, {$barangay['ADULTS']}, {$barangay['PWD']},
            {$barangay['TOTALH']}, {$barangay['STRONGH']}, {$barangay['LIGHTH']},
            {$barangay['MIXEDH']}, 1, 0)";
        
        
        return $this->db->connection->query($insertSQL);
    }

    protected function getDemographicData($barangay) {
        $demoSQL = 
            "SELECT
            identification.idenBrgy,
            COUNT(CASE WHEN demography.demSex='Male' THEN 1 END) AS MEN,
            COUNT(CASE WHEN demography.demSex='Female' THEN 1 END) AS WOMEN,
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, demography.demBday, CURDATE())<18 THEN 1 END) AS MINORS,
            COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, demography.demBday, CURDATE())>=18 THEN 1 END) AS ADULTS,
            COUNT(CASE WHEN disability.daTypeDisability IS NOT NULL THEN 1 END) AS PWD
            FROM identification
            INNER JOIN demography
            ON demography.idenHouseNo = identification.idenHouseNo
            INNER JOIN disability
            ON demography.demID = disability.demID
            WHERE identification.idenBrgy='{$barangay}'";
        $demoResult = $this->dbbrgy->connection->query($demoSQL);

        return $demoResult->fetch_assoc();
    }

    protected function getHousingData($barangay) {
        $houseSQL =
            "SELECT
            identification.idenBrgy,
            COUNT(CASE WHEN (housingmat.houseMatWalls IN (1,4))
                  AND (housingmat.houseMatRoof IN (1,4)) THEN 1 END) AS STRONG,
            COUNT(CASE WHEN (housingmat.houseMatWalls IN (2,5))
                  AND (housingmat.houseMatRoof IN (2,5)) THEN 1 END) AS LIGHT,
            COUNT(CASE WHEN NOT 
                  ((housingmat.houseMatWalls IN (1,4)) AND (housingmat.houseMatRoof IN (1,4))
                  OR (housingmat.houseMatWalls IN (2,5)) AND (housingmat.houseMatRoof IN (2,5))) THEN 1 END) AS MIXED
            FROM housingmat
            INNER JOIN housing
            ON housing.houseID = housingmat.houseID
            INNER JOIN identification
            ON housing.idenHouseNo = identification.idenHouseNo
            WHERE identification.idenBrgy='{$barangay}'";
        $houseResult = $this->dbbrgy->connection->query($houseSQL);

        return $houseResult->fetch_assoc();
    }

    protected function isExistingBrgy($barangay) {
        $exSQL = "SELECT idenBrgy FROM identification WHERE idenBrgy='{$barangay}'";
        $exResult = $this->dbbrgy->connection->query($exSQL);

        return (mysqli_num_rows($exResult) > 0);
    }
}

?>