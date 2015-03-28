<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'me'  => [
            'text'  => 'Me <i class="fa fa-user fa-lg"></i>',
            'url'   => $this->di->get('url')->create('me'),
            'title' => 'Me'
        ],

        // This is a menu item
        'report'  => [
            'text'  => 'Redovisning <i class="fa fa-archive fa-lg"></i>',
            'url'   => $this->di->get('url')->create('report'),
            'title' => 'Mina redovisningar',
        ],

        'dicegame'  => [
            'text'  => 'Tärningsspel <i class="fa fa-gamepad fa-lg"></i>',
            'url'   => $this->di->get('url')->create('dicegame'),
            'title' => 'Spela tärningsspelet',
        ],

        'calender'  => [
            'text'  => 'Kalender <i class="fa fa-calendar fa-lg"></i>',
            'url'   => $this->di->get('url')->create('calender'),
            'title' => 'Kolla kalendern',
        ],

        'comments'  => [
            'text'  => 'Kommentarer <i class="fa fa-comments fa-lg"></i>',
            'url'   => $this->di->get('url')->create('comment'),
            'title' => 'Skriv kommentarer',
            'submenu' => [
                'items' => [

                        // This is a menu item of the submenu
                        'resetComments'  => [
                            'text'  => 'Återställ Kommentarer',
                            'url'   => $this->di->get('url')->create('comment/setup'),
                            'title' => 'Visa alla'
                        ],
                    ],
                ],
        ],

        'second-comments'  => [
            'text'  => 'Kommentarer avdelning 2 <i class="fa fa-comments fa-lg"></i>',
            'url'   => $this->di->get('url')->create('second-comment'),
            'title' => 'Skriv kommentarer',
            'submenu' => [
                'items' => [

                        // This is a menu item of the submenu
                        'resetSecondComments'  => [
                            'text'  => 'Återställ Kommentarer',
                            'url'   => $this->di->get('url')->create('second-comment/setup'),
                            'title' => 'Visa alla'
                        ],
                    ],
                ],
        ],

        'users'  => [
            'text'  => 'Användare <i class="fa fa-users fa-lg"></i>',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Användare',

            'submenu' => [
                'items' => [

                        // This is a menu item of the submenu
                        'showAll'  => [
                            'text'  => 'Visa alla',
                            'url'   => $this->di->get('url')->create('users/list'),
                            'title' => 'Visa alla'
                        ],

                        // This is a menu item of the submenu
                        'setupUsers'  => [
                            'text'  => 'Återställ databasen',
                            'url'   => $this->di->get('url')->create('users/setup'),
                            'title' => 'Återställ databasen'
                        ],

                        // This is a menu item of the submenu
                        'createUser'  => [
                            'text'  => 'Skapa användare',
                            'url'   => $this->di->get('url')->create('users/add'),
                            'title' => 'Skapa användare',
                        ],
                    ],
                ],
        ],

        // This is a menu item
        'soruceCode' => [
            'text'  =>'Källkod  <i class="fa fa-code fa-lg"></i>',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Källkod',
        ],

    ],



    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($this->di->get('request')->getCurrentUrl($url) == $this->di->get('url')->create($url)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
