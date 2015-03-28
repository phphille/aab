<?php

namespace Anax\Login;



class CCheckUser {

        private $di;

        public function __construct($di) {
            $this->di = $di;
        }

        public function GetLoginMenu($values) {

            if(!isset($values)){
              $array = array('login'  => [
                            'text'  => 'Logga in',
                            'url'   => $this->di->get('url')->create('login'),
                            'title' => 'Logga in',
                        ]);

            }
            /*
            else if($values->medlemsTyp == 'admin'){
                $array = array('login'  => [
                            'text'  => $values->acronym,
                            'url'   => $this->di->get('url')->create("users/user/$values->id"),
                            'title' => 'Gå till din sida',
                        ],
                        'adminTools'  => [
                            'text'  => 'Administrations verktyg',
                            'url'   => $this->di->get('url')->create(""),
                            'title' => 'Gå till adminsidan',
                        ],
                        'logout'  => [
                            'text'  => 'Logga ut',
                            'url'   => $this->di->get('url')->create("login/logout"),
                            'title' => 'Logga ut',
                        ]);
            }
            */
            else{
                $array = array('login'  => [
                            'text'  => $values->acronym,
                            'url'   => $this->di->get('url')->create("users/user/$values->id"),
                            'title' => 'Gå till din sida',
                        ],
                        'logout'  => [
                            'text'  => 'Logga ut',
                            'url'   => $this->di->get('url')->create("login/logout"),
                            'title' => 'Logga ut',
                        ]);
            }

            return $array;
        }







        public function removeCorrectAnswer($values, $questionId, $answerId){

            $html = "";
            if(isset($values) && $values->id == $questionId){
                $html = "<label><a href='".$this->di->get('url')->create('questions/rca/'.$values->id.'/'.$questionId.'/'.$answerId)."' title='Ta bort korrekt svar'><i class='fa fa-close'></i></a></label>";
            }

            return $html;
        }







        public function setCorrectAnswer($values, $questionId, $answerId){

            $html = "";
            if(isset($values) && $values->id == $questionId){

                $this->di->db
                ->select('correctAnswer
                from answers')
                ->where('question = '.$questionId)
                ->execute();

                $res = $this->di->db->fetchAll();

                $vals=array();
                foreach($res as $val){
                    $vals[] = $val->correctAnswer;
                }

                if(!in_array('ja',$vals)){
                    $html = "<label><a href='".$this->di->get('url')->create('questions/sca/'.$values->id.'/'.$questionId.'/'.$answerId)."' title='Ange som korrekt svar'><i class='fa fa-check'></i></a></label>";
                }

            }

            return $html;
        }







        public function editAnswer($values, $answerId, $answerUserId, $questionId){

            $html = "";
            if(isset($values) && $values->id == $answerUserId){

                $html = "<label id='edit'><a href='".$this->di->get('url')->create('questions/edit-answer/'.$values->id.'/'.$answerId.'/'.$answerUserId.'/'.$questionId)."' title='Ändra svaret'><i class='fa fa-edit fa-2x'></i></a></label>";

            }

            return $html;
        }





        public function editQuestion($values, $questionUserID, $questionId){

            $html = "";
            if(isset($values) && $values->id == $questionUserID){

                $html = "<label id='edit'><a href='".$this->di->get('url')->create('questions/edit-question/'.$values->id.'/'.$questionUserID.'/'.$questionId)."' title='Ändra frågan'><i class='fa fa-edit fa-2x'></i></a></label>";

            }

            return $html;
        }



        public function editReply($values, $replyId, $replyUserID, $questionId){

            $html = "";
            if(isset($values) && $values->id == $replyUserID){

                $html = "<label id='edit'><a href='".$this->di->get('url')->create('questions/edit-reply/'.$values->id.'/'.$replyId.'/'.$replyUserID.'/'.$questionId)."' title='Ändra kommentaren'><i class='fa fa-edit fa-2x'></i></a></label>";

            }

            return $html;
        }







        public function voteUpAnswer($values, $userRecivingVote, $typeId, $questionId, $type){

            if($type == 'question'){
                $titleUp = "Rösta upp frågan";
                $titleDown = "Rösta ner frågan";
                $action = 'question';
                $table = 'Questions';
            }
            else if($type == 'answer'){
                $titleUp = "Rösta upp svaret";
                $titleDown = "Rösta ner svaret";
                $action = 'answer';
                $table = 'Answers';
            }
            else if($type == 'reply'){
                $titleUp = "Rösta upp kommentaren";
                $titleDown = "Rösta ner kommentaren";
                $action = 'reply';
                $table = 'Replies';
            }


            $html = "<i class='fa fa-chevron-up fa-2x' title='Du har redan röstat upp detta svar'></i>";
            if(isset($values)){

                $this->di->db
                ->select('value, id
                from points'.$table)
                ->where($action.'Id = '.$typeId.' AND userId = '.$values->id )
                ->execute();

                $res = $this->di->db->fetchAll();
                $idPointsType = !empty($res) ? $res[0]->id : 'e';

                if(empty($res) || $res[0]->value == 0 ){

                    $html = "<label><a href='".$this->di->get('url')->create('questions/vote-up/'.$typeId.'/'.$userRecivingVote.'/'.$values->id.'/'.$idPointsType.'/'.$questionId.'/'.$type)."' title='".$titleUp."'><i class='fa fa-chevron-up'></i></a></label>";
                }
                else if(!empty($res) && $res[0]->value == -1){

                    $html = "<label><a href='".$this->di->get('url')->create('questions/vote-equal/'.$typeId.'/'.$userRecivingVote.'/'.$values->id.'/'.$idPointsType.'/'.$questionId.'/'.$type)."' title='".$titleDown."'><i class='fa fa-chevron-up'></i></a></label>";
                }
            }

            return $html;

        }


        public function voteDownAnswer($values, $userRecivingVote, $typeId, $questionId, $type){


            if($type == 'question'){
                $titleUp = "Rösta upp frågan";
                $titleDown = "Rösta ner frågan";
                $action = 'question';
                $table = 'Questions';
            }
            else if($type == 'answer'){
                $titleUp = "Rösta upp svaret";
                $titleDown = "Rösta ner svaret";
                $action = 'answer';
                $table = 'Answers';
            }
            else if($type == 'reply'){
                $titleUp = "Rösta upp kommentaren";
                $titleDown = "Rösta ner kommentaren";
                $action = 'reply';
                $table = 'Replies';
            }


            $html = "<i class='fa fa-chevron-down fa-2x' title='Du har redan röstat ner detta svar'></i>";
            if(isset($values)){

                $this->di->db
                ->select('value, id
                from points'.$table)
                ->where($action.'Id = '.$typeId.' AND userId = '.$values->id)
                ->execute();

                $res = $this->di->db->fetchAll();
                $idPointsType = !empty($res) ? $res[0]->id : 'e';

                if(empty($res) || $res[0]->value == 0){

                    $html = "<label><a href='".$this->di->get('url')->create('questions/vote-down/'.$typeId.'/'.$userRecivingVote.'/'.$values->id.'/'.$idPointsType.'/'.$questionId.'/'.$type)."' title='Rösta ner svaret'><i class='fa fa-chevron-down'></i></a></label>";
                }
                else if(!empty($res) && $res[0]->value == 1){

                    $html = "<label><a href='".$this->di->get('url')->create('questions/vote-equal/'.$typeId.'/'.$userRecivingVote.'/'.$values->id.'/'.$idPointsType.'/'.$questionId.'/'.$type)."' title='Rösta ner svaret'><i class='fa fa-chevron-down'></i></a></label>";
                }
            }

            return $html;
        }



    }
