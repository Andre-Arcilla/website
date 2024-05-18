<?php 
if (isset($_POST["requ"])) {
    require "gpsTracking.php";
    switch ($_POST["req"]) {
        default: echo "Invalid request"; break;

        case "update":
            echo $_TRACK->update($_POST["id"], $_POST["lng"], $_POST["lat"])
            ? "OK" : $_TRACK->error;
            break;
        
        //last known loacation
        case "get" :
            echo json_encode ($_TRACK->get(
                isset($_POST["id"] ? $_POST["id"] : null)
            )) 


    }
}


?>