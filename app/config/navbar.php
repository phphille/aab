<?php


if($this->di->request->getGet('show-grid', 23) == 23){
     $gridUrl = $this->di->request->getCurrentUrl()."?show-grid";
}
else{
    $gridUrl = substr($this->di->request->getCurrentUrl(), 0, -10);
}




$navbar =

    [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'index'  => [
            'text'  => 'AAB',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Startsidan'
        ],

        'questions'  => [
            'text'  => 'Frågor',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => 'Kolla frågor'
        ],

        // This is a menu item
        'tags'  => [
            'text'  => 'Taggar',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => 'Kolla taggar',
        ],

        'users'  => [
            'text'  => 'Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => 'Kolla avändare',
        ],

        'askQuestion'  => [
            'text'  => 'Ställ en fråga',
            'url'   => $this->di->get('url')->create('ask-question'),
            'title' => 'Kräver att man är inloggad',
        ],

        'about'  => [
            'text'  => 'Om AAB',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'Om aab',
        ],





    ],


/*
'soruceCode' => [
            'text'  =>'Källkod',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Källkod',
        ],
        */
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
//dump($this->di->session->get('user'));
$items = $this->di->ccheckUser->GetLoginMenu($this->di->session->get('user'));

$navbar['items'] = array_merge($navbar['items'],$items);


return $navbar;
