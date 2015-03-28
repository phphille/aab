<?php
/**
 * Config-file for navigation bar.
 *
 */

if($this->di->request->getGet('show-grid', 23) == 23){
     $gridUrl = $this->di->request->getCurrentUrl()."?show-grid";
}
else{
    $gridUrl = substr($this->di->request->getCurrentUrl(), 0, -10);
}

return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'kmom03'  => [
            'text'  => 'Kmom03',
            'url'   => $this->di->get('url')->create('kmom03'),
            'title' => 'Kmom03'
        ],

        'regions'  => [
            'text'  => 'Regioner',
            'url'   => $this->di->get('url')->create('regions'),
            'title' => 'Regioner'
        ],

        'grid'  => [
            'text'  => 'Rutnät',
            'url'   => $gridUrl,
            'title' => ''
        ],

        'typografi'  => [
            'text'  => 'Typografi',
            'url'   => $this->di->get('url')->create('typografi'),
            'title' => 'Typografi'
        ],

        'fontawesome'  => [
            'text'  => 'Font awesome',
            'url'   => $this->di->get('url')->create('fontawesome'),
            'title' => 'Font awesome'
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
