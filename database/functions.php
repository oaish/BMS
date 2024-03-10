<?php
function getAllBeneficiary($mainAccNo){
    global $con;
    $sql = "SELECT * FROM Beneficiary WHERE MainAccountNo = '".$mainAccNo."'";
    $all_beneficiary = array();
    $result = mysqli_query($con, $sql);
    $i = 0;
    while($result && $myrow = mysqli_fetch_array($result)){
        $all_beneficiary[$i] = $myrow['AccountNo'];
        $i++;
    }
    return $all_beneficiary;
}

function printError($error){
    echo '<pre class="error">';
    print_r($error);
    echo '</pre>';
}
?>