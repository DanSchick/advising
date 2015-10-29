
<?php
    include "top.php";

    /* 
     * this function handles getting courses by year, semester, and plan.
     * After we use this, we simply have to print out the data
     */
    function getCoursesBySem($plan, $semester, $year){
        // first, we connect this function to the database
        require_once('../bin/Database.php');
        $dbUserName = get_current_user() . '_reader';
        $whichPass = "r"; //flag for which one to use.
        $dbName = DATABASE_NAME;

        $thisDatabaseReader = new Database($dbUserName, $whichPass, $dbName);

        $dbUserName = get_current_user() . '_writer';
        $whichPass = "w";
        $thisDatabaseWriter = new Database($dbUserName, $whichPass, $dbName);

        // now, execute query
        $query = 'SELECT tblCourses.fldCourseName FROM tblCourses INNER JOIN tblSemesterPlan ON tblSemesterPlan.fnkCourseId=tblCourses.pmkCourseNumber INNER JOIN tbl4YP ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId INNER JOIN tblStudent ON tblStudent.pmkNetID = tbl4YP.fnkNetId
        WHERE tbl4YP.pmkPlanId = ? AND tblSemesterPlan.fnkTerm = ? AND tblSemesterPlan.fldYear = ?';
        $data = array($plan, $semester, $year);
        $info2 = $thisDatabaseReader->select($query, $data, 1, 2, 0, 0, false, false);
        return $info2;
    }

    /*
     * This function prints the query that we received.
     * We seperate getCourses and printQuery because there might be a case where we need to get the information but not print it
     */
    function printQuery($query){
        $highlight = 1; // used to highlight alternate rows
        $columns = 1;
        print '<table>';
        foreach ($query as $rec) {
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
    print '</table>';
    }

    $fall2015 = getCoursesBySem(1, 'Fall', '2015');
    $spring2016 = getCoursesBySem(1, 'Spring', '2016');
    print '<h1>All courses for my sample plan</h1>';
    print '<h2>Fall 2015</h2>';
    print '<aisde>' . printQuery($fall2015) . '</aside>';
    print '<br><h2>Spring 2016</h2>';
    print '<aisde>' . printQuery($spring2016) . '</aside';



include "footer.php";
?>
