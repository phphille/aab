<?php


namespace Anax\Tags;

/**
 * Model for Users.
 *
 */
class Tags extends \Anax\MVC\CDatabaseModel
{
    /*
    public function setupUsers(){
    //$this->db->setVerbose();

    $this->db->dropTableIfExists('user')->execute();

    $this->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();

    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'deleted', 'active']
    );

    $now = gmdate('Y-m-d H:i:s');

    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        'null',
        $now
    ]);

    $this->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        'null',
        $now
    ]);

    $this->db->execute([
        'ph',
        'ph@dbwebb.se',
        'ph',
        password_hash('ph', PASSWORD_DEFAULT),
        $now,
        $now,
        'null'
    ]);

    }



    public function createUser(){

        $form = new \Anax\Form\CFormCreateUser();
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Skapa en användare");

        $this->di->views->add('default/page', [
            'title' => "Fyll i och skapa en användare",
            'content' => $form->getHTML()
        ], 'jumbotron');

    }


    public function updateUser($id, $acronym, $name, $email, $created, $deleted, $active){

        $form = new \Anax\Form\CFormUpdateUser($id, $acronym, $name, $email, $created, $deleted, $active);
        $form->setDI($this->di);
        $form->check();

        $this->di->theme->setTitle("Updatera en användare");

        $this->di->views->add('default/page', [
            'title' => "Ändra i fälten och updatera användaren",
            'content' => $form->getHTML()
        ], 'jumbotron');

    }
    */

}
