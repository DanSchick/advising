<?php
    /*
     * Plan.php prints out the plan. We pass the plan variable in the URL
     */
    include "top.php";

    /*
     * This function handles getting courses by year, semester, and plan.
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
     * We seperate getCourses and printQuery because there might be a case where we need to get the
     * information but not print it
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

    /*
     * This function gets every semester and catalog year that a certain plan has.
     * From this function we can determine how much to loop through
     */
    function getTerms($plan){
        // first, we connect this function to the database
        require_once('../bin/Database.php');
        $dbUserName = get_current_user() . '_reader';
        $whichPass = "r"; //flag for which one to use.
        $dbName = DATABASE_NAME;
        $thisDatabaseReader = new Database($dbUserName, $whichPass, $dbName);
        $dbUserName = get_current_user() . '_writer';
        $whichPass = "w";
        $thisDatabaseWriter = new Database($dbUserName, $whichPass, $dbName);

        // now we execute query
        $query = 'SELECT DISTINCT tblSemesterPlan.fldYear, tblSemesterPlan.fnkTerm FROM tblSemesterPlan INNER JOIN tblCourses ON tblSemesterPlan.fnkCourseId=tblCourses.pmkCourseNumber INNER JOIN tbl4YP ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId INNER JOIN tblStudent ON tblStudent.pmkNetID = tbl4YP.fnkNetId
        WHERE tbl4YP.pmkPlanId = ?';
        $data = array($plan);
        $info2 = $thisDatabaseReader->select($query, $data, 1, 0, 0, 0, false, false);
        return $info2;
    }

        /**
     * @param array $array
     * @param string $value
     * @param bool $asc - ASC (true) or DESC (false) sorting
     * @param bool $preserveKeys
     * @return array
     * */
    function sortBySubValue($array, $value, $asc = true, $preserveKeys = false) {
        if ($preserveKeys) {
            $c = array();
            if (is_object(reset($array))) {
                foreach ($array as $k => $v) {
                    $b[$k] = strtolower($v->$value);
                }
            } else {
                foreach ($array as $k => $v) {
                    $b[$k] = strtolower($v[$value]);
                }
            }
            $asc ? asort($b) : arsort($b);
            foreach ($b as $k => $v) {
                $c[$k] = $array[$k];
            }
            $array = $c;
        } else {
            if (is_object(reset($array))) {
                usort($array, function ($a, $b) use ($value, $asc) {
                    return $a->{$value} == $b->{$value} ? 0 : ($a->{$value} - $b->{$value}) * ($asc ? 1 : -1);
                });
            } else {
                usort($array, function ($a, $b) use ($value, $asc) {
                    return $a[$value] == $b[$value] ? 0 : ($a[$value] - $b[$value]) * ($asc ? 1 : -1);
                });
            }
        }

        return $array;
    }


    /*
     * Now, we print out the courses for each term in a certain plan.
     * We use _get to be able to view different plans
     */
    $planID = (int) $_GET['plan'];
    print '<br><h1>Plan ID: ' . $planID . '</h1>';
    $planTerms = sortBySubValue(getTerms($planID), 'fldYear', true, false);
    foreach ($planTerms as $chunk){
        $year = $chunk[0];
        $sem = $chunk[1];
        $q = getCoursesBySem($planID, $sem, $year);
        print '<br><h2>' . $sem . ' ' . $year . '</h2>';
        print '<aside>' . printQuery($q) . '</aside>';
    }
?>
