SELECT tblCourses.fldCourseName FROM tblCourses
INNER JOIN tblSemesterPlan ON tblSemesterPlan.fnkCourseId=tblCourses.pmkCourseNumber
INNER JOIN tbl4YP ON tblSemesterPlan.fnkPlanId=tbl4YP.pmkPlanId
INNER JOIN tblStudent ON tblStudent.pmkNetID = tbl4YP.fnkNetId
