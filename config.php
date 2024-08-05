<?php
@session_start();

define('COMPANY_NAME',    "");
define('PROJECT_NAME',    "");
define('DOMAIN_NAME',     ""); 
define('SMS_FROM',        ""); 
define('TITLE_NAME',      "");
define('MOBILE_NO',       "");
define('ADDRESS',         "");
define('ELE_SUB_TITLE',   "");
define('DASHBOARD_TITLE', "");
define('ADMIN_TITLE',     "");
define('WEB_URL',         "");
define('DASHBOARD_DESC',  "");
define('EMAIL_FROM',      "");
define('EMAIL_TO',        "");
define('IS_RUNON_SERVER', "1");
// IS_RUNON_SERVER 1 = local , 2 = server
define('VIRSION',         "1.0");
define('COPYRIGHT_DESC',  date('Y')." COPYRIGHT_DESC ©  || All right reserved");
define('ALIAS',           "result");
define('DB_HOST',         "localhost");
define('DB_USERNAME',     "root");
define('DB_PASS',         "");
define('DB_NAME',         "");

$con = new mysqli(DB_HOST, DB_USERNAME, DB_PASS, DB_NAME);
if ($con->connect_error) {
    die("Database connection error: " . $con->connect_error);
}

class DBController 
{   
    function runQuery($query) {
        global $con;
        $result = $con->query($query);
        $resultset = array();
        while($row = $result->fetch_assoc()) {
            $resultset[] = $row;
        }        
        return $resultset;
    }

    // $query = "INSERT INTO adm_5_city_tbl (city_name,status, created_by) VALUES (? ,?, ?)";
    // $params =          			     array($city_name, $status, $log_user);
    function runQuerySecure($query, $params = array()) {
        global $con;    
        $stmt = $con->prepare($query);
        if ($stmt === false) {
            return false; 
        }
    
        // Bind parameters to the prepared statement
        if (!empty($params)) {
            $types = "";
            $bindParams = array();
            //print_r($params);
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i"; // Integer
                } elseif (is_float($param)) {
                    $types .= "d"; // Double
                } else {
                    $types .= "s"; // String
                }
                $bindParams[] = $param;
            }
            
            // Add the type string as the first parameter
            array_unshift($bindParams, $types);
            // Use call_user_func_array to bind parameters
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);
        }
    
        // Execute the prepared statement
        $result = $stmt->execute();
    
        // Check for success or failure
        if ($result === true) {
            return true; // Insert successful
        } else {
            return false; // Insert failed
        }
    }

    function updateQuerySecure($query, $params = array()) {
        global $con;
        
        $stmt = $con->prepare($query);
        
        if ($stmt === false) {
            return false;
        }
        if (!empty($params)) {
            
            $types = "";
            $bindParams = array();
    
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= "i"; // Integer
                } elseif (is_float($param)) {
                    $types .= "d"; // Double
                } else {
                    $types .= "s"; // String
                }
                $bindParams[] = $param;
            }
    
            // Add the type string as the first parameter
            array_unshift($bindParams, $types);
    
            // Use call_user_func_array to bind parameters
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);
        }
    
        // Execute the prepared statement
        $result = $stmt->execute();
    
        // Check for success or failure
        if ($result === true) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    
    
    
    function numRows($query) {
        global $con;
        $result = $con->query($query);
        return $result->num_rows;   
    }
    
    //for insert query
    function numQuery($query) {
        global $con;
        $result = $con->query($query);
        return $result; 
    }
    
    function allow_image_type()
    {
        return array("image/jpeg", "image/gif", "image/png");    
    }
    
    function current_date_return() {
        return date("Y-m-d");
    }

    function current_time_return() {
        return date('h:i:s A', time());
    }       
}

$dbcon = new DBController();
?>
