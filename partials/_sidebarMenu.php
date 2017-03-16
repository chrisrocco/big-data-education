<?php
$isAdmin = false;
$authorized_emails = [
    'pcapers@uab.edu',
    'chris.rocco7@gmail.com',
    'awbrown@uab.edu'
];
if (in_array($_SESSION['email'], $authorized_emails)) {
    $isAdmin = true;
}
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu" draggable="true">
    <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
            <?php if ($_SESSION['role'] == 2): ?>
                <li><a href="myClasses.php"><i class="fa fa-graduation-cap"></i> My Classes</a></li>
            <?php endif; ?>
            <li><a href="myAssignments.php"><i class="fa fa-home"></i> Assignments</a></li>
            <li><a href="variables.php"><i class="fa fa-book"></i> Code-Book</a></li>
            <li><a href="videos.php"><i class="fa fa-play"></i> Video Tutorials</a></li>
            <?php if ($isAdmin): ?>
                <li><a href="admin.php"><i class="fa fa-lock"></i> Admin Page</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>