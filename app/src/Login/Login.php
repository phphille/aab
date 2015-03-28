<?php
namespace Anax\Login;

/**
 * Model for Users.
 *
 */
class Login extends \Anax\MVC\CDatabaseModel
{


    public function loginUser($userName, $password){

        $res = $this->findUser($userName, $password);

        if($res){
            $this->di->session->set('user', $res[0]);
            $url = $this->di->url->create('users/user/' .$res[0]->id);
            $this->di->response->redirect($url);
        }

    }


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
            'inactive' => ['datetime'],
            'active' => ['datetime'],
            'deleted' => ['datetime'],
        ]
    )->execute();

    $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active', 'inactive', 'deleted' ]
    );

    $now = gmdate('Y-m-d H:i:s');

    $this->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now,
        'null',
        'null'
    ]);

    $this->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now,
        'null',
        'null'
    ]);

    $this->db->execute([
        'ph',
        'ph@dbwebb.se',
        'ph',
        password_hash('ph', PASSWORD_DEFAULT),
        $now,
        'null',
        $now,
        'null'
    ]);

    $this->db->execute([
        'ab',
        'ab@dbwebb.se',
        'ab',
        password_hash('ab', PASSWORD_DEFAULT),
        $now,
        'null',
        'null',
        $now
    ]);

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
