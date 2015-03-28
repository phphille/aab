<?php


namespace Anax\Questions;

/**
 * Model for Users.
 *
 */
class Questions extends \Anax\MVC\CDatabaseModel
{



    public function createQuestion($titel, $text, $user, $tags, $newTags){
        $time = date('Y-m-d H:i:s');
        $array = array('titel' => $titel, 'text' => $text, 'user' => $user, 'created' => $time);

        $resQuestion[] = $this->save($array, 'questions');
        $resQuestion[] = $this->db->lastInsertId();

        if($newTags && strlen(trim($newTags)) != 0){
            if (strpos($newTags,',') !== false) {
                $newTags = array_map('trim', explode(',',$newTags));
            }

            if(is_array($newTags)){
                foreach($newTags as $newTag){

                    $this->db
                    ->select('id from tags')
                    ->where("LOWER(name) = LOWER('$newTag')")
                    ->execute();

                    $res = $this->db->fetchAll();

                    //echo "id som redan finns ".var_dump($res);
                    if(!$res){
                        //echo "lägger till";
                        if(trim($newTag) != ''){
                            $nbrWords = explode(' ', $newTag);
                            if(count($nbrWords) == 1 ){
                                $sql = "
                                    INSERT INTO
                                    tags
                                    (name)
                                    VALUES
                                    (?)";

                                $res = $this->db->execute($sql, [$newTag]);

                                $newTagsId[] = $this->db->lastInsertId();
                                //echo "new tag id ".dump($newTagsId);
                            }
                        }
                    }
                    else{
                         //echo "finns redan";
                        $newTagsId[] = $res[0]->id;
                    }
                }
            }
            if(is_array($tags)){
                if(isset($newTagsId)){
                    $tags = array_merge($tags, $newTagsId);
                }

            }
            else{
                $newTagsId[] = $tags;

                $tags = array_filter($newTagsId);
                //var_dump($newTagsId);
            }
        }
        $tags = array_unique($tags);
        //var_dump($tags);
        foreach($tags as $tag){
            if(strlen(trim($tag)) != 0){
                $this->save(array('idQuestions' => $resQuestion[1], 'idTags' => ucfirst($tag)), 'questions2tags');
            }
        }

        return $resQuestion;

    }





    public function createAnswer($text, $userId, $questionId){

        $sql = "
            INSERT INTO
            answers
            (question, user, text, created)
            VALUES
            (?,?,?,NOW())";

        $res = $this->db->execute($sql, [$questionId, $userId, $text]);


        return $res;

    }








    public function commentReply($text, $userId, $answerId, $questionId){

        $sql = "
            INSERT INTO
            replies
            (question, answer, user, text, created)
            VALUES
            (?,?,?,?,NOW())";

        $res = $this->db->execute($sql, [$questionId, $answerId, $userId, $text]);


        return $res;

    }






    public function removeCorrectAnswer($idAnswer){

        $array = array('id' => $idAnswer, 'correctAnswer' => 'nej');

        $res = $this->save($array, 'answers');

        return $res;

    }





    public function setCorrectAnswer($idAnswer){

        $array = array('id' => $idAnswer, 'correctAnswer' => 'ja');

        $res = $this->save($array, 'answers');

        return $res;

    }





    public function editAnswer($id, $text){

        $time = date('Y-m-d H:i:s');
        $array = array('id' => $id, 'text' => $text, 'edited' => $time);

        $res = $this->save($array, 'answers');

        return $res;

    }

    public function editReply($id, $text){

        $time = date('Y-m-d H:i:s');
        $array = array('id' => $id, 'text' => $text, 'edited' => $time);

        $res = $this->save($array, 'replies');

        return $res;

    }



    public function editQuestion($id, $text, $title, $tags){

        $sql = "
        Delete from
        questions2tags
        WHERE idQuestions = $id;";

        $res = $this->db->execute($sql);

        foreach($tags as $tag){
            $sql = "
            INSERT INTO
            questions2tags
            (idTags, idQuestions)
            VALUES
            (?,$id)";

            $res = $this->db->execute($sql, [$tag]);
        }

        $time = date('Y-m-d H:i:s');
        $array = array('id' => $id, 'text' => $text, 'titel' => $title, 'edited' => $time);

        $res = $this->save($array, 'questions');

        return $res;

    }



    public function voteUp($typeId, $userRecivingVote, $userId, $id, $type){

         if($type == 'question'){
            $colName = 'questionId';
            $table = 'pointsQuestions';
        }
        else if($type == 'answer'){
            $colName = 'answerId';
            $table = 'pointsAnswers';
        }
        else if($type == 'reply'){
            $colName = 'replyId';
            $table = 'pointsReplies';
        }
        $date = date('Y-m-d H:i:s');
        $array = array($colName => $typeId, 'userId' => $userId, 'userRecivingVote' => $userRecivingVote, 'value' => 1, 'date' => $date);
        if($id && is_numeric($id)){
            $array = array_merge($array, array('id' => $id));
        }
        $res = $this->save($array, $table);

        return $res;
    }


    public function voteEqual($typeId, $userRecivingVote, $userId, $id, $type){

        if($type == 'question'){
            $colName = 'questionId';
            $table = 'pointsQuestions';
        }
        else if($type == 'answer'){
            $colName = 'answerId';
            $table = 'pointsAnswers';
        }
        else if($type == 'reply'){
            $colName = 'replyId';
            $table = 'pointsReplies';
        }
        $date = date('Y-m-d H:i:s');
        $array = array($colName => $typeId, 'userId' => $userId, 'userRecivingVote' => $userRecivingVote, 'value' => 0, 'date' => $date);
        if($id && is_numeric($id)){
            $array = array_merge($array, array('id' => $id));
        }

        $res = $this->save($array, $table);

        return $res;
    }



    public function voteDown($typeId, $userRecivingVote, $userId, $id, $type){

        if($type == 'question'){
            $colName = 'questionId';
            $table = 'pointsQuestions';
        }
        else if($type == 'answer'){
            $colName = 'answerId';
            $table = 'pointsAnswers';
        }
        else if($type == 'reply'){
            $colName = 'replyId';
            $table = 'pointsReplies';
        }
        $date = date('Y-m-d H:i:s');
        $array = array($colName => $typeId, 'userId' => $userId, 'userRecivingVote' => $userRecivingVote, 'value' => -1, 'date' => $date );
        if($id && is_numeric($id)){
            $array = array_merge($array, array('id' => $id));
        }

        $res = $this->save($array, $table);

        return $res;
    }

}
