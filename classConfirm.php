<?php
include "top.php";

  $CourseId = $_GET['CourseId'];
  $Term = $_GET['Term'];
  $Year = $_GET['Year'];
  $PlanID = $_GET['planID'];

  $nameQ = 'SELECT fldCourseName FROM tblCourses WHERE fnkCourseId = ?';
  $data = array($CourseId);
  $nameA = $thisDatabaseReader->select($nameQ, $data, 1, 0, 0, false, false);

  print '<h2>Please confirm:</h2><br>' . '<p>Course Name: <h2>' . $nameA[0][0] . '</h2></p>';

  if( $_POST ){
    $confirm = $_POST['confirm'];
    if($confirm == "true"){
        $query = "INSERT INTO `DSCHICK_advising`.`tblSemesterPlan` (`fnkPlanId`, `fnkTerm`, `fldYear`, `fnkCourseId`) VALUES (?, ?, ?, ?)";
        $data = array($PlanID, $Term, $Year, $CourseId);
        $insert = $thisDatabaseWriter->insert($query, $data, 0, 0, 0, false, false);
        $test = $thisDatabaseWriter->testQuery($query, $data, 0, 0, 0, false, false);


    }
    header('Location: addClass.php');
  }






?>


<form method="post">
<input type="radio" name="confirm" value="true">Yes<br>
<input type="radio" name="confirm" value="false">No
<input type='submit' value='Submit' />
</form>
