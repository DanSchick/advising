<?php
    /*
     * Plan.php prints out the plan. We pass the plan variable in the URL
     */
    include "top.php";

    /**
     * To start, we print out information about the plan using queries
     */
    $planID = (int) $_GET['plan'];
    $query = "SELECT fldStudentName, fldAdvisorName, fldDateCreated FROM tbl4YP
    INNER JOIN tblAdvisor ON tbl4YP.fldAdvisorId = tblAdvisor.pmkNetId WHERE pmkPlanId=?";
    $data = array($planID);
    $names = $thisDatabaseWriter->select($query, $data, 1, 0, 0, false, false);
    print '<div class="info">';
    print '<h1>Student Name: </h1><p class="italic">' . $names[0][0] . '</p>';
    print '<h1>Advisor Name: </h1><p class="italic">' . $names[0][1] . '</p>';
    print '<h1>Date Created: </h1><p class="italic">' . $names[0][2] . '</p>';
    print '</div>';


    /**
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

        // now, execute query
        $query = 'SELECT tblCourses.fnkCourseId, tblCourses.fldCourseName FROM tblCourses INNER JOIN tblSemesterPlan
        ON tblSemesterPlan.fnkCourseId=tblCourses.fnkCourseId INNER JOIN tbl4YP
        ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId INNER JOIN tblStudent ON tblStudent.pmkNetID = tbl4YP.fnkNetId
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
        $columns = 2;
        print '<ul>';
        foreach ($query as $rec) {
            print '<li>' . $rec[0] . '</li>';

        }
        print '</ul>';
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

        // now we execute query
        $query = 'SELECT DISTINCT tblSemesterPlan.fldYear, tblSemesterPlan.fnkTerm FROM tblSemesterPlan
        INNER JOIN tblCourses ON tblSemesterPlan.fnkCourseId=tblCourses.fnkCourseId
        INNER JOIN tbl4YP ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId INNER JOIN tblStudent
        ON tblStudent.pmkNetID = tbl4YP.fnkNetId WHERE tbl4YP.pmkPlanId = ?';
        $data = array($plan);
        $info2 = $thisDatabaseReader->select($query, $data, 1, 0, 0, 0, false, false);
        return $info2;
    }

    /**
     * This function sorts the array of all terms by year, making this page print the courses in order.
     * $value is the value of the subarray key you want to sort by.
     */
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

    /**
     * This returns the sum of the credits for each semester and term
     */
    function getCredits($plan, $term, $year){
        // connect to database
        require_once('../bin/Database.php');
        $dbUserName = get_current_user() . '_reader';
        $whichPass = "r"; //flag for which one to use.
        $dbName = DATABASE_NAME;
        $thisDatabaseReader = new Database($dbUserName, $whichPass, $dbName);

        // now execute query
        $query = "SELECT SUM(fldCredits) FROM tblCourses INNER JOIN tblSemesterPlan
        ON tblSemesterPlan.fnkCourseId = tblCourses.fnkCourseId
        WHERE tblSemesterPlan.fnkTerm = ? AND tblSemesterPlan.fldYear = ? AND tblSemesterPlan.fnkPlanId = ?";
        $data = array($term, $year, $plan);
        $info2 = $thisDatabaseReader->select($query, $data, 1, 2, 0, 0, false, false);
        return $info2;


    }


    /*********************************************************
     * Now, we print out the courses for each term in a certain plan as well as the credits
     * We use $_get to be able to view different plans
     */
    print '<br><h1>Plan ID: ' . $planID . '</h1>';
    $planTerms = sortBySubValue(getTerms($planID), 'fldYear', true, false);
    $lastYear = 'NULL'; // to create newlines
    foreach ($planTerms as $chunk){
        $year = $chunk[0];
        $sem = $chunk[1];
        if($year != $lastYear){ // this will create a newline
            print '<div class="newline">';}
        print '<section class="' . $sem . '">';
        $q = getCoursesBySem($planID, $sem, $year);
        // we have to loop through the getCredits array because we have to pull out the number
        foreach(getCredits($planID, $sem, $year) as $i){
            $credits = $i[0];
        }
        print '<br><h2>' . $sem . ' ' . $year . '</h2>';
        print '<aside>' . printQuery($q) . '</aside>';
        print '<aside>Credits: ' . $credits . '</aside>';
        print '</section>';
        if($year != $lastYear){ // for a new line
            print '</div>';}
        $lastYear = $year;
    }
?>
