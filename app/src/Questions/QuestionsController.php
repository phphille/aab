<?php


namespace Anax\Questions;

/**
 * A controller for users and admin related events.
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
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
        $orderby = $this->request->getGet('orderby', 'created');
        $order = $this->request->getGet('order', 'DESC');
        $hits = $this->request->getGet('hits', 8);
        $page = $this->request->getGet('page', 1);
        $offset = (($page-1)*$hits);

        if($this->request->getGet('textField')!=null || !empty($this->request->getGet('textField'))){
            if (strpos($this->request->getGet('textField'),'(tags:') !== false) {
                $res = getStringBetween($this->request->getGet('textField'), "(tags:", ")");
                if (strpos($res,',') !== false) {
                    $tags = array_map('trim',explode(',',$res));
                }
                else{
                    $tags = trim($res);
                }
                $text = trim(preg_replace("/\([^)]+\)/","",$this->request->getGet('textField')));
                $whereTags = '';
                if(count($tags) == 1){
                    $whereTags .= "tags LIKE '%".$tags."%'";
                }
                else{
                    for($i = 0; $i<count($tags); $i++){
                        $whereTags .= "tags LIKE '%".$tags[$i]."%'";
                        if($i != (count($tags)-1)){
                            $whereTags .= " AND ";
                        }
                    }
                }

                $this->db->select('
                *
                from VsearchQuestions')
                ->where('titel LIKE "%'.$text.'%"')
                ->andWhere("$whereTags")
                ->groupBy('id')
                ->execute();
            $count = $this->db->fetchAll();

            $this->db->select('
                *
                from VsearchQuestions')
                ->where('titel LIKE "%'.$text.'%"')
                ->andWhere("$whereTags")
                ->orderBy("'".$orderby." ".$order."'")
                ->limit("$hits")
                ->offset("$offset")
                ->execute();

            }
            else{
             $this->db->select('
                *
                from VsearchQuestions')
                ->where('titel LIKE "%'.strip_tags($this->request->getGet('textField')).'%"')
                ->execute();
            $count = $this->db->fetchAll();

            $this->db->select('
                *
                from VsearchQuestions')
                ->where('titel LIKE "%'.strip_tags($this->request->getGet('textField')).'%"')
                ->orderBy(" $orderby $order ")
                ->limit("$hits")
                ->offset("$offset")
                ->execute();
            }
        }
        else{
            $this->db
                ->select('
                *
                from VsearchQuestions')
                ->execute();
            $count = $this->db->fetchAll();


            $this->db
                ->select('
                *
                from VsearchQuestions')
                ->orderBy("$orderby $order")
                ->limit("$hits")
                ->offset("$offset")
                ->execute();
        }

        $this->paginering->setTotalRows($hits, $page, count($count));
        $questions = $this->db->fetchAll();

        $textFiled = $this->request->getGet('textField');
        $this->views->add('questions/questions', [
            'questions' => $questions,
            'textField' => $textFiled,
            'hits' => $this->paginering->getNbrOfHitsPerPage(),
            'pages' => $this->paginering->getPageNav(),
            'title' => "Frågor",
        ], 'jumbotron');

    }




    public function questionAction($id)
    {
        if(!is_numeric($id)){
            $url = $this->url->create('questions/');
            $this->response->redirect($url);
        }
        if($this->request->getPost('commentReply') !=null ){
            if(!is_numeric($this->request->getPost('userId')) || !is_numeric($this->request->getPost('answerId')) || !is_numeric($this->request->getPost('questionId'))){
                $url = $this->url->create("questions/question/$id");
                $this->response->redirect($url);
            }
            $this->questions->commentReply(strip_tags($this->request->getPost('text')), $this->request->getPost('userId'), $this->request->getPost('answerId'), $this->request->getPost('questionId'));
        }
        else if($this->request->getPost('createAnswer') != null){
            if(!is_numeric($this->request->getPost('userId')) || !is_numeric($this->request->getPost('questionId'))){
                $url = $this->url->create("questions/question/$id");
                $this->response->redirect($url);
            }
            $this->questions->createAnswer(strip_tags($this->request->getPost('text')), $this->request->getPost('userId'), $this->request->getPost('questionId'));
        }




         $this->db
            ->select('
            q.*,
            IF((select sum(value) from pointsQuestions where questionId = q.id) is null, 0, (select sum(value) from pointsQuestions where questionId = q.id)) as votes,
            u.id as userID,
            u.acronym as userName,
            u.points as userPoints,
            u.email as userEmail,
            GROUP_CONCAT(DISTINCT t.name ORDER BY t.name) as tags
            from questions as q')
            ->join('questions2tags as Q2T', 'q.id = Q2T.idQuestions')
            ->join('VusersWithTotalPoints as u', 'q.user = u.id')
            ->join('tags as t', 'Q2T.idTags = t.id')
            ->where('q.id='.$id)
            ->groupBy('q.id')
            ->orderBy('q.created DESC')
            ->execute();

        $question = $this->db->fetchAll();


        $orderby = $this->request->getGet('orderby', 'created');
        $order = $this->request->getGet('order', 'asc');

        $this->db
            ->select('
            a.*,
            IF((select sum(value) from pointsAnswers where answerId = a.id) is null, 0, (select sum(value) from pointsAnswers where answerId = a.id)) as votes,
            u.acronym as userName,
            u.id as userID,
            u.email as userEmail,
            u.points as userPoints
            from answers as a')
            ->join('VusersWithTotalPoints as u', 'a.user = u.id')
            ->where('a.question='.$id)
            ->groupBy('a.id')
            ->orderBy("$orderby $order")
            ->execute();

        $answers = $this->db->fetchAll();

        $this->db
            ->select('
            r.*,
            IF((select sum(value) from pointsReplies where replyId = r.id) is null, 0, (select sum(value) from pointsReplies where replyId = r.id)) as votes,
            u.acronym as userName,
            u.id as userID,
            u.email as userEmail,
            u.points as userPoints
            from replies as r')
            ->join('VusersWithTotalPoints as u', 'r.user = u.id')
            ->where('r.question='.$id)
            ->groupBy('r.id')
            ->orderBy("$orderby $order")
            ->execute();

        $replies = $this->db->fetchAll();

        $this->views->add('questions/question', [
            'question' => $question,
            'title' => $question[0]->titel,
            'answers' => $answers,
            'replies' => $replies,
            'orderby' => $orderby,
            'order' => $order
        ], 'jumbotron');



    }




    public function askQuestionAction()
    {
        if(!$this->di->session->get('user')){
            $url = $this->di->url->create('login');
            $this->di->response->redirect($url);
        }
        if($this->request->getPost('createQuestion') !=null ){

            $res = $this->questions->createQuestion(strip_tags($this->request->getPost('titel')), strip_tags($this->request->getPost('text')), $this->di->session->get('user')->id, $this->request->getPost('tags'), strip_tags($this->request->getPost('newTags')) );

            if($res[0]){
                $url = $this->di->url->create("questions/question/$res[1]");
                $this->di->response->redirect($url);
            }
        }


        $this->db
            ->select('*
            from tags')
            ->orderBy('name DESC')
            ->execute();

        $tags = $this->db->fetchAll();

        $this->views->add('questions/askQuestion', [
            'title' => 'Skapa en fråga',
            'tags' => $tags
        ], 'jumbotron');


    }





     public function editQuestionAction($idUser, $questionUserId, $idQuestion)
    {

        if(!is_numeric($idUser) || !is_numeric($idQuestion) || !is_numeric($questionUserId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions');
            $this->di->response->redirect($url);
        }
        if($idUser == $questionUserId){

            if($this->request->getPost('editQuestion') !=null ){
                if(!is_numeric($this->request->getPost('id'))){
                    $url = $this->di->url->create('questions');
                    $this->di->response->redirect($url);
                }
                $this->questions->editQuestion($this->request->getPost('id'), strip_tags($this->request->getPost('text')), strip_tags($this->request->getPost('titel')), $this->request->getPost('tags'));

                $url = $this->di->url->create('questions/question/'.$idQuestion);
                $this->di->response->redirect($url);

            }

            $this->db
            ->select('
            q.text,
            q.titel,
            q.id,
            GROUP_CONCAT(DISTINCT t.name ORDER BY t.name) as tags
            from questions as q')
            ->join('questions2tags as Q2T', 'q.id = Q2T.idQuestions')
            ->join('tags as t', 'Q2T.idTags = t.id')
            ->where('q.id = '.$idQuestion.' AND q.user = '.$questionUserId)
            ->execute();

        $res = $this->db->fetchAll();

        $selectedTags = explode(",", $res[0]->tags);

        $this->db
            ->select('*
            from tags as t')
            ->execute();
        $tags = $this->db->fetchAll();
        //var_dump($tags);

        $this->theme->setTitle("Uppdatera svaret");
        $this->views->add('questions/editQuestion', [
            'title' => 'Uppdatera svar',
            'id' => $res[0]->id,
            'text' => $res[0]->text,
            'titel' => $res[0]->titel,
            'selectedTags' => $selectedTags,
            'tags' => $tags
        ], 'jumbotron');

        }
    }






    public function rcaAction($idUser, $idQuestion, $idAnswer)
    {
        if(!is_numeric($idUser) || !is_numeric($idQuestion) || !is_numeric($idAnswer) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions');
            $this->di->response->redirect($url);
        }
        $this->db
            ->select('*
            from questions')
            ->where('id = '.$idQuestion)
            ->andWhere('user = '.$idUser)
            ->execute();

        $tags = $this->db->fetchAll();
        if(!empty($tags)){
            $this->questions->removeCorrectAnswer($idAnswer);
            $url = $this->di->url->create('questions/question/'.$idQuestion);
            $this->di->response->redirect($url);
        }

    }





    public function scaAction($idUser, $idQuestion, $idAnswer)
    {

         if(!is_numeric($idUser) || !is_numeric($idQuestion) || !is_numeric($idAnswer) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions');
            $this->di->response->redirect($url);
        }
        $this->db
            ->select('*
            from questions')
            ->where('id = '.$idQuestion)
            ->andWhere('user = '.$idUser)
            ->execute();

        $tags = $this->db->fetchAll();
        if(!empty($tags)){
            $this->questions->setCorrectAnswer($idAnswer);
            $url = $this->di->url->create('questions/question/'.$idQuestion);
            $this->di->response->redirect($url);
        }
    }

















    public function editAnswerAction($idUser, $answerId, $answerUserId, $idQuestion)
    {

        if(!is_numeric($idUser) || !is_numeric($answerId) || !is_numeric($answerUserId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions');
            $this->di->response->redirect($url);
        }
        if($idUser == $answerUserId){

            if($this->request->getPost('edit') !=null ){
                if(!is_numeric($this->request->getPost('id'))){
                    $url = $this->di->url->create('questions');
                    $this->di->response->redirect($url);
                }
                $this->questions->editAnswer($this->request->getPost('id'), strip_tags($this->request->getPost('text')));

                $url = $this->di->url->create('questions/question/'.$idQuestion);
                $this->di->response->redirect($url);
            }

            $this->db
            ->select('id, text
            from answers')
            ->where('id = '.$answerId.' AND user = '.$answerUserId)
            ->execute();

        $res = $this->db->fetchAll();

        $this->views->add('questions/editReplyAnswer', [
            'title' => 'Uppdatera svar',
            'id' => $res[0]->id,
            'text' => $res[0]->text
        ], 'jumbotron');

        }
    }









    public function editReplyAction($idUser, $replyId, $replyUserId, $idQuestion)
    {

        if(!is_numeric($idUser) || !is_numeric($replyId) || !is_numeric($replyUserId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions');
            $this->di->response->redirect($url);
        }
        if($idUser == $replyUserId){

            if($this->request->getPost('edit') != null ){

                if(!is_numeric($this->request->getPost('id'))){
                    $url = $this->di->url->create('questions');
                    $this->di->response->redirect($url);
                }
                $this->questions->editReply($this->request->getPost('id'), strip_tags($this->request->getPost('text')));

                $url = $this->di->url->create('questions/question/'.$idQuestion);
                $this->di->response->redirect($url);
            }

            $this->db
            ->select('id, text
            from replies')
            ->where('id = '.$replyId.' AND user = '.$replyUserId)
            ->execute();

        $res = $this->db->fetchAll();

        $this->views->add('questions/editReplyAnswer', [
            'title' => 'Uppdatera kommentar',
            'id' => $res[0]->id,
            'text' => $res[0]->text
        ], 'jumbotron');

        }
    }












    public function voteUpAction($typeId, $userRecivingVote, $userId, $id, $questionId, $type){
        if(!is_numeric($userId) || !is_numeric($typeId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions/question/'.$questionId);
            $this->di->response->redirect($url);
        }
        $this->questions->voteUp($typeId, $userRecivingVote, $userId, $id, $type);

        $url = $this->di->url->create('questions/question/'.$questionId);
        $this->di->response->redirect($url);

    }


    public function voteEqualAction($typeId, $userRecivingVote, $userId, $id, $questionId, $type){
        if(!is_numeric($userId) || !is_numeric($typeId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions/question/'.$questionId);
            $this->di->response->redirect($url);
        }
        $this->questions->voteEqual($typeId, $userRecivingVote, $userId, $id, $type);

        $url = $this->di->url->create('questions/question/'.$questionId);
        $this->di->response->redirect($url);

    }



    public function voteDownAction($typeId, $userRecivingVote, $userId, $id, $questionId, $type){
        if(!is_numeric($userId) || !is_numeric($typeId) || !$this->di->session->get('user')){
            $url = $this->di->url->create('questions/question/'.$questionId);
            $this->di->response->redirect($url);
        }
        $this->questions->voteDown($typeId, $userRecivingVote, $userId, $id, $type);

        $url = $this->di->url->create('questions/question/'.$questionId);
        $this->di->response->redirect($url);

    }







    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->questions = new \Anax\Questions\Questions();
        $this->questions->setDI($this->di);
    }





}
