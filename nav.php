<!-- ######################     Main Navigation   ########################## -->
<nav>
    <ol>
        <?php
        // This sets the current page to not be a link. Repeat this if block for
        //  each menu item
        if ($path_parts['filename'] == "index") {
            print '<li class="activePage">Home</li>';
        } else {
            print '<li><a href="index.php">Home</a></li>';
        }

        if ($path_parts['filename'] == "tables") {
            print '<li class="activePage">Display Tables</li>';
        } else {
            print '<li><a href="tables.php">Display Tables</a></li>';
        }

        if ($path_parts['filename'] == "viewPlans") {
            print '<li class="activePage">Show My Plan </li>';
        } else {
            print '<li><a href="viewPlans.php">Show My Plan</a></li>';
        }

        if ($path_parts['filename'] == "addClass") {
            print '<li class="activePage">Add Classes</li>';
        } else {
            print '<li><a href="addClass.php">Add Classes</a></li>';
        }

        if ($path_parts['filename'] == "newplan") {
            print '<li class="activePage">Create New Plan </li>';
        } else {
            print '<li><a href="newplan.php">Create New Plan </a></li>';
        }



        ?>
    </ol>
</nav>
<!-- #################### Ends Main Navigation    ########################## -->

