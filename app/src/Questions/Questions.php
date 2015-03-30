<?php


namespace Anax\Questions;

/**
 * Model for Users.
 *
 */
class Questions extends \Anax\MVC\CDatabaseModel
{



    public function createQuestion($titel, $text, $user, $tags, $newTags){

        if(strlen(trim($titel)) <= 1 || strlen(trim($text)) <= 1){
            return false;
        }
        $theQuestionsTags = 0;
        $nbrOfNewTagsToAdd = 5 - count($tags);
        $time = date('Y-m-d H:i:s');
        $array = array('titel' => $titel, 'text' => $text, 'user' => $user, 'created' => $time);

        $resQuestion[] = $this->save($array, 'questions');
        $resQuestion[] = $this->db->lastInsertId();

        //var_dump($newTags);
        if($newTags && (strlen(trim($newTags)) != 0 || strlen(trim($newTag)) != 1)){

            if (strpos($newTags,',') !== false) {
                $newTags = array_map('trim', explode(',',$newTags));
            }

            if(is_array($newTags)){
               // echo "Det är en array";
                $counter = 0;
                foreach($newTags as $newTag){
                    if($counter < $nbrOfNewTagsToAdd){
                        $this->db
                        ->select('id from tags')
                        ->where("LOWER(name) = LOWER('$newTag')")
                        ->execute();

                        $res = $this->db->fetchAll();

                        //echo "id som redan finns ".var_dump($res)."      ";
                        if(!$res){
                            //echo "lägger till";
                            if(trim($newTag) != ''){
                                $nbrWords = explode(' ', $newTag);
                                if(count($nbrWords) == 1 && (strlen(trim($newTag)) != 0 || strlen(trim($newTag)) != 1) && ctype_alpha($newTag)){
                                    //echo ucfirst($newTag);
                                    $sql = "
                                        INSERT INTO
                                        tags
                                        (name)
                                        VALUES
                                        (?)";

                                    $res = $this->db->execute($sql, [ucfirst($newTag)]);

                                    $newTagsId[] = $this->db->lastInsertId();
                                    //echo "new tag id ".dump($newTagsId);
                                    $theQuestionsTags++;
                                }
                            }
                        }
                        else{
                             //echo "finns redan";
                            $newTagsId[] = $res[0]->id;
                            $theQuestionsTags++;

                        }

                        if($theQuestionsTags == 4){
                            //echo " stoppar ";
                            break;
                        }
                    }
                }
            }
            else{
                if(ctype_alpha($newTags) && strlen(trim($newTags)) > 1){
                    $this->db
                    ->select('id from tags')
                    ->where("LOWER(name) = LOWER('$newTags')")
                    ->execute();
                    $res = $this->db->fetchAll();

                    if(!$res){
                        //echo "lägger till";
                        $sql = "
                            INSERT INTO
                            tags
                            (name)
                            VALUES
                            (?)";

                        $res = $this->db->execute($sql, [ucfirst($newTags)]);

                        $newTagsId[] = $this->db->lastInsertId();
                        //echo "new tag id ".dump($newTagsId);
                    }
                    else{
                         //echo "finns redan";
                        $newTagsId[] = $res[0]->id;
                    }
                }
            }
        }
        if(is_array($tags)){
            if(isset($newTagsId)){
                $tags = array_merge($tags, $newTagsId);
            }
            $theQuestionsTags++;
        }
        else{
            $newTagsId[] = $tags;

            $tags = array_filter($newTagsId);
            //var_dump($newTagsId);
        }
        $tags = array_unique($tags);
        //var_dump($tags);
        if($tags){
            foreach($tags as $tag){
                $this->save(array('idQuestions' => $resQuestion[1], 'idTags' => $tag) , 'questions2tags');
                $theQuestionsTags++;
            }
        }

        if($theQuestionsTags == 0){
            //echo "tar bort frågan";
            $sql = "
            DELETE FROM
            questions
            where id = $resQuestion[1]
            ";

            $res = $this->db->execute($sql);
            return false;
        }
        else{
            return $resQuestion;
        }

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

        $time = date('Y-m-d H:i:s');
        $array = array('id' => $idAnswer, 'correctAnswer' => 'nej', 'correctAnswerDate' => $time);

        $res = $this->save($array, 'answers');

        return $res;

    }





    public function setCorrectAnswer($idAnswer){

        $time = date('Y-m-d H:i:s');
        $array = array('id' => $idAnswer, 'correctAnswer' => 'ja', 'correctAnswerDate' => $time);

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



    public function editQuestion($id, $text, $title){

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
