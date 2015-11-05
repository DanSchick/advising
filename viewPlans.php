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



print '<br><br><h2>User ' . get_current_user() . ' has these plans available:</h2>';
// we do a query to show all the plans a user has
$query = "SELECT pmkPlanId FROM tbl4YP WHERE fnkNetId = ?";
$data = array(get_current_user());
$plans = $thisDatabaseReader->select($query, $data, 1, 0, 0, false, false);
// print each plan as a link that plan.php will get through $_GET
print '<ol>';
foreach($plans as $plan){
    print '<li>';
    $id = $plan[0];
    print '<a href=plan.php?plan=' . $id . '>Plan '. $id . '</a>';

    print '</li>';
}
print '</ol>';



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


