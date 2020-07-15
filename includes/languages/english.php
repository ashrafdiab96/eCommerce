<?php

    function lang ($phrase)
    {
        static $lang = array(
            // navbar links
            'HOME'          => 'DEBO COMMERCE',
            'HOME_PAGE'     => 'Home',
            'CAT'           => 'Categories',
            'ITEMS'         => 'Items',
            'MEMBERS'       => 'Members',
            'COMMENTS'      => 'Comments',
            'STATISTICS'    => 'Statistics',
            'LOGS'          => 'Logs',
            'EDIT'          => 'Edit Profile',
            'SETTINGS'      => 'Settings',
            'LOGOUT'        => 'Logout',
            '' => '',
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