<?php


namespace Anax\Index;

/**
 * A controller for users and admin related events.
 *
 */
class IndexController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
    \Anax\MVC\TRedirectHelpers;


    /**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->initialize();

         $this->db
            ->select('
            *
                from VsearchQuestions')
            ->orderBy('created DESC')
            ->limit('6')
            ->execute();

        $newestQuestions = $this->db->fetchAll();

        $this->views->add('index/newestQuestions', [
            'questions' => $newestQuestions,
            'title' => "De 6 senaste trådarna",
        ], 'jumbotron');


        $this->db
            ->select('t.name, COUNT(*) TotalCount
FROM questions2tags')
            ->join('tags as t', 't.id = idTags')
            ->groupBy('t.id')
            ->orderBy('COUNT(*) DESC')
            ->limit('6')
            ->execute();

        $popularTags = $this->db->fetchAll();

        $this->views->add('index/popularTags', [
            'tags' => $popularTags,
            'title' => "De 6 populäraste taggarna",
        ], 'containerLeft');


        $this->db
            ->select('u.acronym,
            u.id,
            sum(
            (SELECT COUNT(*)
            FROM questions where u.id = user)
            +
            (SELECT COUNT(*)
            FROM Answers
            WHERE u.id = user)
            +
            (SELECT COUNT(*)
            FROM Replies
            WHERE u.id = user)
            +
            IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
            +
            IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
            +
            IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
            )
            As activity,
            u.email as email,
            sum(
				IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
				+
				IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
				+
				IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
				+
				(select count(*) from answers where user = u.id)
                +
                (select count(*) from replies where user = u.id)
                +
                (select count(*) from questions where user = u.id)
				) as points
            from users as u')
            ->groupBy('u.id')
            ->orderBy('3 DESC')
            ->limit('6')
            ->execute();

        $mostActiveUsers = $this->db->fetchAll();

        $this->views->add('index/mostActiveUsers', [
            'users' => $mostActiveUsers,
            'title' => "De 6 mest aktiva användarna",
        ], 'containerRight');


    }



    /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $this->initialize();

        $all = $this->users->findAll();

        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "View all users",
        ], 'jumbotron');
    }






    public function setupAction()
    {
        $this->initialize();

        $this->users->setupUsers();

        $this->views->addString('<h1>Återställ databasen</h1><p>Nu är databasen återställd. <a href='.$this->url->create('users').'>Länk</a> för att se alla användare</p>', 'jumbotron');

    }















    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $this->initialize();

        $user = $this->users->find($id);
        $this->theme->setTitle("View user with id");
        $this->views->add('users/view', [
            'user' => $user,
            'title' => 'View a user'
        ], 'jumbotron');
    }







    /**
     * Add new user.
     *
     * @param string $acronym of user to add.
     *
     * @return void
     */
    public function addAction()
    {
        $this->initialize();
        $this->users->createUser();

    }








    public function updateAction($id = null)
    {
        $this->initialize();

        $user = $this->users->find($id);

        $this->users->updateUser($user->id, $user->acronym, $user->name, $user->email, $user->created, $user->deleted, $user->active);
    }









    /**
     * Delete user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function deleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $res = $this->users->delete($id);

        $url = $this->url->create('users/');
        $this->response->redirect($url);
    }










    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function softDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->deleted = $now;
        $user->active = null;
        $user->save();

        $url = $this->url->create('users');
        $this->response->redirect($url);
    }











    /**
     * Delete (soft) user.
     *
     * @param integer $id of user to delete.
     *
     * @return void
     */
    public function undoSoftDeleteAction($id = null)
    {
        if (!isset($id)) {
            die("Missing id");
        }

        $now = gmdate('Y-m-d H:i:s');

        $user = $this->users->find($id);

        $user->deleted = null;
        $user->active = $now;
        $user->save();

        $url = $this->url->create('users');
        $this->response->redirect($url);
    }










    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function activeAction()
    {
        $all = $this->users->query()
            ->where('active != 0 OR active is NOT NULL')
            ->andWhere('deleted = 0 OR deleted is NULL')
            ->orderBy('id ASC')
            ->execute();

        $this->theme->setTitle("Users that are active");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are active",
        ], 'jumbotron');
    }









    /**
     * List all active and not deleted users.
     *
     * @return void
     */
    public function deletedAction()
    {
        $all = $this->users->query()
            ->where('active = 0 OR active is NULL')
            ->andWhere('deleted != 0 OR deleted is NOT NULL')
            ->orderBy('id ASC')
            ->execute();

        $this->theme->setTitle("Users that are active");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Users that are active",
        ], 'jumbotron');
    }

















    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->index = new \Anax\Index\Index();
        $this->index->setDI($this->di);
    }





}
