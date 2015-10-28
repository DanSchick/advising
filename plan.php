<?php
include "top.php"; ?>

<h1>All courses for my sample plan</h1>
<?php
$query = 'SELECT tblCourses.fldCourseName FROM tblCourses INNER JOIN tblSemesterPlan ON tblSemesterPlan.fnkCourseId=tblCourses.pmkCourseNumber INNER JOIN tbl4YP ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId INNER JOIN tblStudent ON tblStudent.pmkNetID = tbl4YP.fnkNetId
 WHERE tblStudent.pmkNetID = ?';
    $data = array("dschick");
    $info2 = $thisDatabaseReader->select($query, $data, 1, 0, 0, 0, false, false);


    $highlight = 1; // used to highlight alternate rows
    $columns = 3;
    print '<table>';
    foreach ($info2 as $rec) {
        $highlight++;
        if ($highlight % 2 != 0) {
            $style = ' odd ';
        } else {
            $style = ' even ';
        }
        print '<tr class="' . $style . '">';
        for ($i = 0; $i < $columns; $i++) {
            print '<td>' . $rec[$i] . '</td>';
            print '<br>';
        }
        print '</tr>';
    }

    // all done
    print '</table>';

include "footer.php";
?>
