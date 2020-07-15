<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN'); ?></a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><?php echo lang('AMIN_HOME'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php"><?php echo lang('ADMIN_CAT'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php"><?php echo lang('ADMIN_ITEMS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php"><?php echo lang('ADMIN_MEMBERS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="comments.php"><?php echo lang('ADMIN_COMMENTS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang('ADMIN_STATISTICS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang('ADMIN_LOGS'); ?></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['full_name']; ?></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownId">
                        <a class="dropdown-item" href="../index.php"><?php echo lang('VISIT'); ?></a>
                        <a class="dropdown-item" href="members.php?do=edit&user_id=<?php echo $_SESSION['id']; ?>"><?php echo lang('HOME_EDIT'); ?></a>
                        <a class="dropdown-item" href="members.php?do=editImage&user_id=<?php echo $_SESSION['id']; ?>"><?php echo lang('AMIN_SETTINGS'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php"><?php echo lang('ADMIN_LOGOUT'); ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>