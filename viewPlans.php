<?php
include "top.php";

/**
 * This function will delete a plan
 */
function deletePlan($ID){
    require_once('../bin/Database.php');
    $dbName = DATABASE_NAME;
    $dbUserName = get_current_user() . '_writer';
    $whichPass = "w";
    $thisDatabaseWriter = new Database($dbUserName, $whichPass, $dbName);

    $query = "DELETE FROM `DSCHICK_advising`.`tbl4YP` WHERE `tbl4YP`.`pmkPlanId` = ?";
    $data = $array($ID);
    $del = $thisDatabaseWriter->delete($query, $data, 1, 0, 0, false, false);
    $test = $thisDatabaseWriter->testquery($query, $data, 1, 0, 0, false, false);
}


$username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
print '<br><br><h2>User ' . $username . ' has these plans available:</h2>';
// we do a query to show all the plans a user has
$query = "SELECT pmkPlanId, fldDateCreated FROM tbl4YP WHERE fnkNetId = ?";
$data = array($username);
$plans = $thisDatabaseReader->select($query, $data, 1, 0, 0, false, false);
// print each plan as a link that plan.php will get through $_GET
print '<ul>';
foreach($plans as $plan){
    print '<li>';
    $id = $plan[0];
    $date = $plan[1];
    print '<a href=plan.php?plan=' . $id . '>Plan '. $id . ' | Created on ' . $date . '</a>';

    print '</li>';
}
print '</ul>';



/**
 * Now, we create a method to delete a plan to give the user control
 */
print '<br><br><p> Delete a plan:</p>';

print '<form method="POST" action="viewPlans.php"><select name="planID">';
foreach($plans as $plan){
        print '<option value="' . $plan[0] . '">Plan' . $plan[0] . '</option>';
    }
    print '</select>';
print "<input type='submit' value='Delete' /></form>";

if($_POST){
        //deletePlan($id);
        $id = (int) $_POST["planID"];
        print_r($id);
        $query = "DELETE FROM `DSCHICK_advising`.`tbl4YP` WHERE `tbl4YP`.`pmkPlanId` = ?";
        $data = array($id);
        $del = $thisDatabaseWriter->delete($query, $data, 1, 0, 0, false, false);
        if($del = 1){
            print '<p> Delete Successful</p>';
        }
    }


