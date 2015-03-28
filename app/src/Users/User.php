<?php
namespace Anax\Users;

/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{


     public function userUpdateUsersInfo($id, $email, $firstName, $lastName, $password){
         if($password){
             $sql = "
            UPDATE
            users
            SET
            password = md5(concat(?, salt)),
            email = ?,
            firstName = ?,
            lastName = ?,
            updated = NOW()
            WHERE
            id = ?;";

            $this->db->execute($sql, [$password, $email, $firstName, $lastName, $id]);
         }
         else{
            $sql = "
            UPDATE
            users
            SET
            email = ?,
            firstName = ?,
            lastName = ?,
            updated = NOW()
            WHERE
            id = ?;";
            $this->db->execute($sql, [$email, $firstName, $lastName, $id]);
         }

    }



    public function createUser($acronym, $email, $firstName, $lastName, $password){
        if (strlen(trim($acronym)) == 0 || strlen(trim($email)) == 0 || strlen(trim($firstName)) == 0 ||
           strlen(trim($lastName)) == 0 || strlen(trim($password)) == 0){

            $url = $this->url->create('users/create-user');
            $this->response->redirect($url);

        }
        $sql = "
        Insert into
        users
        (acronym, email, firstName, lastName, salt, password, created, active)
        VALUES
        (?,?,?,?, unix_timestamp(), md5(concat(?, salt)), NOW(), NOW());";

        $res = $this->db->execute($sql, [$acronym, $email, $firstName, $lastName, $password], true);

        if($res = "00000"){
            $res = $this->findUser($acronym, $password);
            $this->di->session->set('user', $res[0]);
            $url = $this->di->url->create('users/user/' .$res[0]->id);
            $this->di->response->redirect($url);
        }
    }


    /*
    public function editUsersInfo($id, $email, $firstname, $lastname){

        $form = new \Anax\Form\CFormEditUserInfo($id, $email, $firstname, $lastname);
        $form->setDI($this->di);
        $form->check();

        $this->di->views->add('default/page', [
            'title' => "Ändra uppgifter",
            'content' => $form->getHTML()
        ], 'jumbotron');

    }
 */
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
