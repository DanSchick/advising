<?php
include "top.php";

if( $_POST ){

  // first, we get all values
  $CourseId = $_POST['CourseId'];
  $Term = $_POST['Term'];
  $Year = $_POST['Year'];
  $PlanID = $_POST['planID'];
  // now, we build the url to confirm the add
  $url = 'Location: classConfirm.php?CourseId=' . $CourseId . '&Term=' . $Term . '&Year=' . $Year . '&planID=' . $PlanID;
  // go to confirm page
  header($url);


}
?>

<!-- BEGIN HTML FORM -->
<br><br>
<form method='post'>
  CourseId: <input type='text' name='CourseId' id='CourseId' /><br />

  Term: <input type='text' name='Term' id='Term' /><br />

  Year: <input type='text' name='Year' id='Year' /><br />

  <?php $query = "SELECT pmkPlanId FROM tbl4YP WHERE fnkNetId = ?";
    $username = htmlentities($_SERVER["REMOTE_USER"], ENT_QUOTES, "UTF-8");
    $data = array($username);
    $plans = $thisDatabaseReader->select($query, $data, 1, 0, 0, false, false);
    print '<select name="planID" id="planID">';
    foreach($plans as $plan){
        print '<option value="' . $plan[0] . '">Plan' . $plan[0] . '</option>';
    }
    print '</select>';
    ?>



  <input type='submit' value='Submit' />
</form>

