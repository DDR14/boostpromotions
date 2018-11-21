<?php

class CustomHelper extends AppHelper {

    /**
     * method getImgDir
     * description get the $dir of the image using its model type
     */
    public function getImgDir($model) {
        $type = explode('-', $model);
        switch ($type[0]) {
            case "AC":
                $dir = "Academic";
                break;

            case "AW":
                $dir = "Awards";
                break;

            case "AR":
                $dir = "Art";
                break;

            case "AT":
                $dir = "Attendance";
                break;

            case "BE":
                $dir = "Behavior";
                break;

            case "HB":
                $dir = "Birthday";
                break;

            case "BT":
                $dir = "Box Top";
                break;

            case "BU":
                $dir = "Business";
                break;

            case "CP":
                $dir = "Camping";
                break;

            case "CI":
                $dir = "Citizenship";
                break;

            case "CL":
                $dir = "Club";
                break;

            case "CO":
                $dir = "Collectibles";
                break;

            case "HT":
                $dir = "Health";
                break;

            case "MA":
                $dir = "Mascots";
                break;

            case "MT":
                $dir = "Math";
                break;

            case "OR":
                $dir = "Organization";
                break;

            case "PE":
                $dir = "Personal";
                break;

            case "PA":
                $dir = "Parents";
                break;

            case "RE":
                $dir = "Reading";
                break;

            case "SC":
                $dir = "Science";
                break;

            case "SD":
                $dir = "Special Days";
                break;

            case "ST":
                $dir = "States";
                break;

            case "SP":
                $dir = "Sports";
                break;

            case "MI":
                $dir = "Medical";
                break;
            case "HO":
                $dir = "Holiday";
                break;
            case "PRE":
                $dir = "Kinder";
                break;
            case "CCA":
                $dir = "CCA";
                break;
            case "X":
                $dir = "X";
                break;
            case "LDS":
            case "REL":
                $dir = "Religious";
                break;
            case "SCT":
                $dir = "Scouting";
                break;
        }

        return $dir;
    }

    /**
     * method checkIfThereIsCustomization
     * description check if the tag has customazations
     *
     * @param $data {{ array }} contains the customers basket data
     */
    public function checkIfThereIsCustomization($data) {

        if (!empty($data['title']) || !empty($data['background']) || !empty($data['footer']) || !empty($data['upload'])) {
            return true;
        }

        return false;
    }

    /**
     * method displaySelectedModel
     * description prepare the selected models
     *
     * @param $data {{ string }} string the contains the selected models data
     */
    public function displaySelectedModel($data) {
        $models = explode(",", $data);
        $newData = [];

        foreach ($models as $value) {
            $tmpData = explode("=", $value);
            array_push($newData, $tmpData[0]);
        }

        return $newData;
    }

}
