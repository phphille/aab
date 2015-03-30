<?php


namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
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
        $this->theme->setTitle("Användare");

        $orderby = $this->request->getGet('orderby', 'points');
        $order = $this->request->getGet('order', 'desc');
        $hits = $this->request->getGet('hits', 9);
        $page = $this->request->getGet('page', 1);
        $offset = (($page-1)*$hits);


        if($this->request->getGet('textField')!=null || !empty($this->request->getGet('textField'))){

            $this->db
            ->select('
            u.*,
            sum(
            IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
            +
            IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
            +
            IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
            +
            ((select count(*) from answers where user = u.id) + (select count(*) from replies where user = u.id) + (select count(*) from questions where user = u.id))
            ) as points
            from users as u')
            ->where('u.acronym LIKE "%'.strip_tags($this->request->getGet('textField')).'%"')
            ->groupBy('u.id')
            ->execute();

            $count = $this->db->fetchAll();


             $this->db
            ->select('
            u.*,
            sum(
            IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
            +
            IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
            +
            IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
            +
            ((select count(*) from answers where user = u.id) + (select count(*) from replies where user = u.id) + (select count(*) from questions where user = u.id))
            ) as points
            from users as u')
            ->where('u.acronym LIKE "%'.strip_tags($this->request->getGet('textField')).'%"')
            ->groupBy('u.id')
            ->orderBy(" $orderby $order ")
            ->limit("$hits")
            ->offset("$offset")
            ->execute();

        }
        else{
            $this->db
            ->select('
            sum(
            IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
            +
            IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
            +
            IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
            +
            ((select count(*) from answers where user = u.id) + (select count(*) from replies where user = u.id) + (select count(*) from questions where user = u.id))
            ) as points,
            u.*
            from users as u')
            ->groupBy('u.id')
            ->execute();
            $count = $this->db->fetchAll();

            $this->db
            ->select('
            sum(
            IF((select sum(value) from pointsAnswers where userId = u.id) is null, 0, (select sum(value) from pointsAnswers where userId = u.id))
            +
            IF((select sum(value) from pointsQuestions where userId = u.id) is null, 0, (select sum(value) from pointsQuestions where userId = u.id))
            +
            IF((select sum(value) from pointsReplies where userId = u.id) is null, 0, (select sum(value) from pointsReplies where userId = u.id))
            +
            ((select count(*) from answers where user = u.id) + (select count(*) from replies where user = u.id) + (select count(*) from questions where user = u.id))
            ) as points,
            u.*
            from users as u')
            ->groupBy('u.id')
            ->orderBy(" $orderby $order ")
            ->limit("$hits")
            ->offset("$offset")
            ->execute();



        }

        $this->paginering->setTotalRows($hits, $page, count($count));

        $users = $this->db->fetchAll();
        $textField = $this->request->getGet('textField');

        $this->views->add('users/users', [
            'users' => $users,
            'title' => "Användare",
            'hits' => $this->paginering->getNbrOfHitsPerPage2(3,6,9),
            'pages' => $this->paginering->getPageNav(),
            'orderby' => $orderby,
            'order' => $order,
            'textField' => $textField
        ], 'jumbotronUsers');

    }







    public function userAction($id)
    {

        if(!is_numeric($id)){
            $url = $this->url->create('users/');
            $this->response->redirect($url);
        }

        if($this->session->get('user', null) && $id == $this->session->get('user', null)->id){

            if($this->request->getPost('userChangeUserInfo')){
                $this->users->userUpdateUsersInfo($this->request->getPost('id'), $this->request->getPost('email'), $this->request->getPost('firstName'), $this->request->getPost('lastName'), $this->request->getPost('password'));
            }

            $this->db
                ->select('
                email, firstName, lastName
                FROM users')
                ->where('id = '.$id)
                ->execute();

            $res = $this->db->fetchAll();
            $email = $res[0]->email;
            $lastName = $res[0]->lastName;
            $firstName = $res[0]->firstName;
        }
        else{
            $email = null;
            $lastName = null;
            $firstName = null;
        }
        /*
        $this->db
            ->select('
            group_concat(id) as ids from answers')
            ->where('user = '.$id)
            ->execute();

        $res = $this->db->fetchAll();

        $answersIds = str_replace(',', ' OR answerId = ', $res[0]->ids);

         $this->db
            ->select('
            group_concat(id) as ids from questions')
            ->where('user = '.$id)
            ->execute();

        $res = $this->db->fetchAll();

        $questionsIds = str_replace(',', ' OR questionId = ', $res[0]->ids);

        $this->db
            ->select('
            group_concat(id) as ids from replies')
            ->where('user = '.$id)
            ->execute();

        $res = $this->db->fetchAll();

        $repliesIds = str_replace(',', ' OR replyId = ', $res[0]->ids);

        $select =  "u.*,
            sum(";
            if($answersIds){
                $select .= 'IF((select sum(value) from pointsAnswers where answerId = '.$answersIds.') is null, 0, (select sum(value) from pointsAnswers where answerId = '.$answersIds.'))';
            }
            else{
                $select .= "0";
            }

            $select .= " + ";
            if($questionsIds){
                $select .= 'IF((select sum(value) from pointsQuestions where questionId = '.$questionsIds.') is null, 0, (select sum(value) from pointsQuestions where questionId = '.$questionsIds.'))';
            }
            else{
                $select .= "0";
            }
            $select .= " + ";
            if($repliesIds){
                $select .= 'IF((select sum(value) from pointsReplies where replyId = '.$repliesIds.') is null, 0, (select sum(value) from pointsReplies where replyId = '.$repliesIds.'))';
            }
            else{
                $select .= "0";
            }
            $select .= " +
            (select count(*) + sum((select count(*) from answers where user = $id) + (select count(*) from replies where user = $id)) from questions where user = $id)
            ) as points
            from users as u";


        $this->db
            ->select($select)
            ->where('u.id = '.$id)
            ->groupBy('u.id')
            ->execute();

        $user = $this->db->fetchAll();
        */

        $this->db
            ->select('
            u.*,
            sum(
            IF((select sum(value) from pointsAnswers where userRecivingVote = u.id) is null, 0, (select sum(value) from pointsAnswers where userRecivingVote = u.id))
            +
            IF((select sum(value) from pointsQuestions where userRecivingVote = u.id) is null, 0, (select sum(value) from pointsQuestions where userRecivingVote = u.id))
            +
            IF((select sum(value) from pointsReplies where userRecivingVote = u.id) is null, 0, (select sum(value) from pointsReplies where userRecivingVote = u.id))
            +
            ((select count(*) from answers where user = u.id) + (select count(*) from replies where user = u.id) + (select count(*) from questions where user = u.id))
            ) as points
            from users as u')
            ->where('u.id = '.$id)
            ->groupBy('u.id')
            ->execute();
        $user = $this->db->fetchAll();


        $this->db
            ->select('pr.*,
            q.titel as qTitle,
            q.id as qId,
            "voteReply" as voteType,
            u.acronym as userNameReply,
            u.id as userIdReply
            from pointsReplies as pr')
            ->join('replies as r', 'pr.replyId = r.id')
            ->join('questions as q','q.id = r.question')
            ->join('users as u','u.id = r.user')
            ->where('pr.userId = '.$id)
            ->orderBy('date DESC')
            ->limit(6)
            ->execute();

        $votesUserReplies = $this->db->fetchAll();

        $this->db
            ->select('pa.*,
            "voteAnswer" as voteType,
            q.titel as qTitle,
            q.id as qId,
            u.acronym as userNameAnswer,
            u.id as userIdAnswer
            from pointsAnswers as pa')
            ->join('answers as a', 'pa.answerId = a.id')
            ->join('questions as q','q.id = a.question')
            ->join('users as u','u.id = a.user')
            ->where('pa.userId = '.$id)
            ->orderBy('date DESC')
            ->limit(6)
            ->execute();

        $votesUserAnswers = $this->db->fetchAll();

        $this->db
            ->select('pq.*,
            q.titel as qTitel,
            "voteQuestion" as voteType,
            u.acronym as userNameQuestion,
            u.id as userIdQuestion
            from pointsQuestions as pq')
            ->join('questions as q','q.id = pq.questionId')
            ->join('users as u','u.id = q.user')
            ->where('userId = '.$id)
            ->orderBy('date DESC')
            ->limit(6)
            ->execute();

        $votesUserQuestions = $this->db->fetchAll();


        $this->db
            ->select('a.*,
            q.id as qId,
            q.titel as qTitel,
            u.*
            from answers as a')
            ->join('questions as q','q.id = a.question')
            ->join('users as u','a.user = u.id')
            ->where('q.user = '.$id)
            ->orderBy('correctAnswerDate DESC')
            ->limit(6)
            ->execute();

        $userTrohpies = $this->db->fetchAll();

        /*
        $votesArray = array_merge($votesUserAnswers, $votesUserReplies, $votesUserQuestions);

        $votesArray = array_map(function ($v) {
            return (array) $v ; // convert to array
        }, $votesArray);

        $dates = array();
        foreach($votesArray as $key => $row) {
            $dates[$key] = $row['date'];
        }
        array_multisort($dates, SORT_DESC, $votesArray);
        */

        $aOrderby = $this->request->getGet('aorderby', 'created');
        $aOrder = $this->request->getGet('aorder', 'DESC');
        $aHits = $this->request->getGet('ahits', 4);
        $aPage = $this->request->getGet('apage', 1);
        $aOffset = (($aPage-1)*$aHits);

        $this->db
            ->select('
            a.*,
            IF((select sum(value) from pointsAnswers where answerId = a.id) is null, 0, (select sum(value) from pointsAnswers where answerId = a.id)) as votes,
			(select distinct id from questions where a.question = id)
			as questionId,

			(select distinct titel from questions where a.question = id)
			as questionTitel

             FROM answers as a ')
            ->where('user = '.$id)
            ->execute();

        $aCount = $this->db->fetchAll();

        $this->db
            ->select('
            a.*,
            IF((select sum(value) from pointsAnswers where answerId = a.id) is null, 0, (select sum(value) from pointsAnswers where answerId = a.id)) as votes,
			(select distinct id from questions where a.question = id)
			as questionId,

			(select distinct titel from questions where a.question = id)
			as questionTitel

             FROM answers as a ')
            ->where('user = '.$id)
            ->orderBy("$aOrderby $aOrder")
            ->limit("$aHits")
            ->offset("$aOffset")
            ->execute();

        $answers = $this->db->fetchAll();

        $this->pagineringNameUrl->setTotalRows($aHits, $aPage, count($aCount));
        $aHitsNav = $this->pagineringNameUrl->getNbrOfHitsPerPage('ahits');
        $aPageNav = $this->pagineringNameUrl->getPageNav('apage');

        $rOrderby = $this->request->getGet('rorderby', 'created');
        $rOrder = $this->request->getGet('rorder', 'DESC');
        $rHits = $this->request->getGet('rhits', 4);
        $rPage = $this->request->getGet('rpage', 1);
        $rOffset = (($rPage-1)*$rHits);

        $this->db
            ->select('
            r.*,
            q.titel as qTitle,
            IF((select sum(value) from pointsReplies where replyId = r.id) is null, 0, (select sum(value) from pointsReplies where replyId = r.id)) as votes
             FROM replies as r ')
            ->join('questions as q','r.question = q.id')
            ->where('r.user = '.$id)
            ->execute();

        $rCount = $this->db->fetchAll();

        $this->db
            ->select('
            r.*,
            q.titel as qTitle,
            q.id as qId,
            IF((select sum(value) from pointsReplies where replyId = r.id) is null, 0, (select sum(value) from pointsReplies where replyId = r.id)) as votes
             FROM replies as r ')
            ->join('questions as q','r.question = q.id')
            ->where('r.user = '.$id)
            ->orderBy("$rOrderby $rOrder")
            ->limit("$rHits")
            ->offset("$rOffset")
            ->execute();

        $replies = $this->db->fetchAll();

        $this->pagineringNameUrl->setTotalRows($rHits, $rPage, count($rCount));
        $rHitsNav = $this->pagineringNameUrl->getNbrOfHitsPerPage('rhits');
        $rPageNav = $this->pagineringNameUrl->getPageNav('rpage');

        $qOrderby = $this->request->getGet('qorderby', 'created');
        $qOrder = $this->request->getGet('qorder', 'DESC');
        $qHits = $this->request->getGet('qhits', 4);
        $qPage = $this->request->getGet('qpage', 1);
        $qOffset = (($qPage-1)*$qHits);

        $this->db
            ->select('
            q.*,
            IF((select sum(value) from pointsQuestions where questionId = q.id) is null, 0, (select sum(value) from pointsQuestions where questionId = q.id)) as votes
             FROM questions as q')
            ->where('user = '.$id)
            ->execute();

        $qCount = $this->db->fetchAll();

        $this->db
            ->select('
            * FROM VsearchQuestions')
            ->where('user = '.$id)
            ->orderBy("$qOrderby $qOrder")
            ->limit("$qHits")
            ->offset("$qOffset")
            ->execute();

        $questions = $this->db->fetchAll();

        $this->pagineringNameUrl->setTotalRows($qHits, $qPage, count($qCount));
        $qHitsNav = $this->pagineringNameUrl->getNbrOfHitsPerPage('qhits');
        $qPageNav = $this->pagineringNameUrl->getPageNav('qpage');

        $this->db
            ->select('
             question as id
             FROM answers')
            ->where('user = '.$id)
            ->execute();

        $res = $this->db->fetchAll();
        $q1 = array();
        if(!empty($res)){
            foreach($res as $item){
                    $q1[] = $item->id;
            }
        }

        $this->db
            ->select('
            (select group_concat(id) from questions where user = '.$id.'), (select group_concat(question) from answers where user = '.$id.'), (select group_concat(question) from replies where user = '.$id.')
            from questions')
            ->limit('1')
            ->execute();

        $res = $this->db->fetchAll();

        //$qIDs = str_replace(',', '', $res[0]->ids);
        //dump($res);
        foreach($res as $value){
            foreach($value as $val){
                $q[] = explode(',', $val);
            }
        }
        $qIDs = array();
        foreach ($q as $key) {
            foreach($key as $val){
                $qIDs[] = $val;
            }
        }
        $qIDs = array_filter($qIDs);

        $vals = array_count_values($qIDs);
        //dump($vals);
        foreach($vals as $key => $val){
            $this->db
                ->select('
                idTags, t.name, (COUNT(*) * '.$val.') as total
                FROM questions2tags')
                ->join('tags as t', 'idTags = t.id')
                ->where('idQuestions = '.$key)
                ->groupBy('idTags')
                ->orderBy('3 DESC')
                ->execute();

            $res = $this->db->fetchAll();
            //dump($res);
            $tags[] = $res;
        }
        //dump($tags);
        $newTags = null;
        if(isset($tags)){
            $newTags = tags($tags);
        }

        $this->theme->setTitle("Användare ". $user[0]->acronym);
        $this->views->add('users/user', [
            'user' => $user,
            'answers' => $answers,
            'rHitsNav' => $rHitsNav,
            'rPageNav' => $rPageNav,
            'aHitsNav' => $aHitsNav,
            'aPageNav' => $aPageNav,
            'qHitsNav' => $qHitsNav,
            'qPageNav' => $qPageNav,
            //'recentVotes' => $votesArray,
            'userTrophies' => $userTrohpies,
            'recentVotesReplies' => $votesUserReplies,
            'recentVotesQuestions' => $votesUserQuestions,
            'recentVotesAnswers' => $votesUserAnswers,
            'questions' => $questions,
            'replies' => $replies,
            'userTags' => $newTags,
            'userLoggedIn' => $this->session->get('user', null),
            'title' => $user[0]->acronym,
            'email' => $email,
            'lastName' => $lastName,
            'firstName' => $firstName
        ], 'jumbotron');

    }



    public function createUserAction()
    {
        if($this->request->getPost('doCreateUser')){

            $res = $this->users->createUser(strip_tags($this->request->getPost('acronym')), strip_tags($this->request->getPost('email')), strip_tags($this->request->getPost('firstName')), strip_tags($this->request->getPost('lastName')), strip_tags($this->request->getPost('password')));

            dump($res);
        }
        $this->theme->setTitle("Skapa ett konto");
        $this->views->add('users/create-user', [
            'title' => 'Skapa ett konto',

        ], 'jumbotron');
    }

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
    }





}
