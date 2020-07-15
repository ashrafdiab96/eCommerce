<?php

    function lang ($phrase)
    {
        static $lang = array(
            // navbar links
            'HOME_ADMIN'        => 'ADMIN AREA',
            'AMIN_HOME'         => 'Home',
            'ADMIN_CAT'         => 'Categories',
            'ADMIN_ITEMS'       => 'Items',
            'ADMIN_MEMBERS'     => 'Members',
            'ADMIN_COMMENTS'    => 'Comments',
            'ADMIN_STATISTICS'  => 'Statistics',
            'ADMIN_LOGS'        => 'Logs',
            'HOME_EDIT'         => 'Edit Profile',
            'AMIN_SETTINGS'     => 'Edit Photo',
            'ADMIN_LOGOUT'      => 'Logout',
            'VISIT'             => 'Visit Shop',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
        );
        return $lang[$phrase];
    }

?>