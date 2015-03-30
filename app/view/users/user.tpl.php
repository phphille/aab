
<?php foreach($user as $item) : ?>
<div class='containerJumbotron' style='text-align: center; margin-bottom: 100px; position:relative;'>
        <h1><?=$title?></h1>
    <?php if($item->deleted != null && $item->inactive != null) : ?>
        <?php if(isset($userLoggedIn->id) && $item->id == $userLoggedIn->id) : ?>
            <?php if($this->request->getGet('accountinfo')){
                    echo "<div class='editAccountInfo'><a href='?accountinfo' title='Göm användaruppgifter'><i class='fa fa-indent'></i></a></div>
                    <div class='accountInfo' style='float: none;'>";
                }
                else{
                    echo "<div class='editAccountInfo'><a href='?accountinfo=show' title='Visa användaruppgifter'><i class='fa fa-outdent'></i></a></div>
                    <div class='accountInfo' style='width: 0px; visibility: hidden;'>";
                }
            ?>
            <?php if($this->request->getGet('accountinfo')){
                echo "
                <form method='post'>
                    <fieldset style='text-align: left;'>
                        <input class='' type='hidden' name='id' value='{$item->id}' />
                        <label>Mailadress:</label><br/>
                        <input class='' type='email' name='email' value='{$email}' required='required' /><br/>
                        <label>Förnamn:</label><br/>
                        <input class='form-element-firstName' type='text' name='firstName' value='{$firstName}' required='required' /><br/>
                        <label>Efternamn:</label><br/>
                        <input class='' type='text' name='lastName' value='{$lastName}' required='required' /><br/>
                        <label>Lösenord:</label><br/>
                        <input class='' type='password' name='password' placeholder='Fyll bara i om du vill byta lösenord' /><br/>
                        <input class='' type='submit' name='userChangeUserInfo' value='Uppdatera'/>
                    </fieldset>
                </form>";
            }
            else{
                echo "
                <form method='post' style='display: none;>
                    <fieldset style='text-align: left; display: none;'>
                        <input class='' type='hidden' name='id' value='' style='display: none;'/>
                        <label style='display: none;'>Mailadress:</label><br/>
                        <input class='' type='email' name='email' value='' required='required' style='display: none;'/><br/>
                        <label style='width: 0px; visibility: hidden;'>Förnamn:</label><br/>
                        <input class='form-element-firstName' type='text' name='firstName' value='' required='required' style='display: none;'/><br/>
                        <label style='display: none;'>Efternamn:</label><br/>
                        <input class='' type='text' name='lastName' value='' required='required' style='display: none;'/><br/>
                        <label style='display: none;'>Lösenord:</label><br/>
                        <input class='' type='password' name='password' placeholder='Fyll bara i om du vill byta lösenord' style='display: none;'/><br/>
                        <input class='' type='submit' name='userChangeUserInfo' value='Uppdatera'  style='display: none;'/>
                    </fieldset>
                </form>";
            }
             ?>
            </div>
        <?php endif; ?>
            <div class='usersPage'>
                <img class='userImgInUsers' src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $item->email ) ) ) . "?s=100" ?>" alt="" />
                <label class='usersNameInUsers'><?=$item->acronym?></label>
                <label class='userTotalPoints'><i class='fa fa-trophy'></i> <?=$item->points?></label>
                <label class='userMemeberTypeInUsers'>Medlemstyp: <?=$item->medlemsTyp?></label>
                <label class='userCreatedInUsers'>Blev medlem <?=$item->created?></label>
            </div>
        <div class="triangle-down"><div></div></div>
        </div>
<div class='userCategories'>
        <div class='aUsersCategory'><h3>Frågor ställda av <?=$item->acronym?></h3>
            <?php if(empty($questions)) : ?>
                <i><?=$item->acronym?> har inte skapat någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($questions)) : ?>
            <?=$qHitsNav?>
                <?php foreach($questions as $question) : ?>
                    <div class='question'>
                        <div class='qPartAttribute'>
                            <div class='qAttributes'>
                                <label class='qNbr'><?=$question->votes?></label>
                                <label class='qNbrDescrip'>Poäng</label>
                            </div>
                            <div class='qAttributes'>
                                <label class='qNbr'><?=$question->nbrOfanswers?></label>
                                <label class='qNbrDescrip'>Antal svar</label>
                            </div>
                            <div class='qAttributes'>
                                <label class='qNbr'> <?php
                                           if($question->cAnswer == 'ja'){
                                                echo "
                                                <i class='fa fa-check'></i>
                                                ";
                                           }
                                           else{
                                                echo "
                                                <i class='fa fa-minus'></i>
                                                ";
                                           }
                                        ?>
                                </label>
                                <label class='qNbrDescrip'>Korrekt svar</label>
                            </div>
                        </div>
                        <div class='qPartQuestion'>
                            <label class='qTitle'><a href='<?=$this->di->get('url')->create('questions/question/'.$question->id)?>'><?=$question->titel?></a></label>
                            <?php
                                $tags = explode(',',$question->tags);
                                echo "<div class='qTags'>";
                                foreach($tags as $tag){
                                    echo "<label class='qTag'><a class='tag' href='{$this->url->create('questions?textField=(tags:'.$tag.')')}'>$tag</a></label>";
                                }
                                echo "</div>";
                            ?>
                        </div>
                        <div class='qPartUser'>
                            <label class='qUser'>
                                <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $question->userEmail ) ) ) . "?s=25" ?>" alt="" />
                                <a href='<?=$this->di->get('url')->create('users/user/'.$question->user)?>'><label class='userName'><?=$question->userName?></label></a>
                                <i class='fa fa-trophy'></i><label class='userPoints'><?=$question->userPoints?></label>
                            </label>
                            <label class='qCreated'>
                                <?=$question->created?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$qPageNav?>
            <?php endif; ?>
        </div>
        <div class='aUsersCategory'>
            <h3>Angivna svar av <?=$item->acronym?></h3>
            <?php if(empty($answers)) : ?>
                <i><?=$item->acronym?> har inte svarat på någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($answers)) : ?>
            <?=$aHitsNav?>

                <?php foreach($answers as $answer) : ?>
                    <div class='uAnswers'>
                        <div class='uPartAttribute'>
                            <div class='uAttributes'>
                                <label class='uNbr'><?=$answer->votes?></label>
                                <label class='uNbrDescrip'>Poäng</label>
                            </div>
                            <div class='uAttributes'>
                                    <label class='uNbr'> <?php
                                               if($answer->correctAnswer == 'ja'){
                                                    echo "
                                                    <i class='fa fa-check'></i>
                                                    ";
                                               }
                                               else{
                                                    echo "
                                                    <i class='fa fa-minus'></i>
                                                    ";
                                               }
                                            ?>
                                    </label>
                                    <label class='uNbrDescrip'>Korrekt svar</label>
                            </div>
                        </div>
                        <div class='uPartText'>
                            Svar:<label class='uText'><?php echo $this->di->getTextFilter->doFilter(get_first_sentence($answer->text), 'markdown')?></label>
                            <label><a href='<?=$this->url->create('questions/question/'.$answer->questionId.'#a'.$answer->id)?>'>Läs mer..</a></label>
                        </div>
                        <div class='uPartQuestion'>
                            Fråga:
                            <br>
                            <a href='<?=$this->url->create('questions/question/'.$answer->questionId)?>'><?=$answer->questionTitel?></a>
                            <label class='uCreated'><?=$answer->created?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$aPageNav?>
            <?php endif; ?>
        </div>



        <div class='aUsersCategory'>
            <h3>Kommentarer gjorda av <?=$item->acronym?></h3>
            <?php if(empty($replies)) : ?>
                <i><?=$item->acronym?> har inte gjort några kommentarer</i>
            <?php endif; ?>
            <?php if(!empty($replies)) : ?>
            <?=$rHitsNav?>
                <?php foreach($replies as $reply) : ?>
                      <div class='uAnswers'>
                        <div class='uPartAttribute'>
                            <div class='uAttributes' style='width: 100%;'>
                                <label class='uNbr'><?=$reply->votes?></label>
                                <label class='uNbrDescrip'>Poäng</label>
                            </div>
                        </div>
                        <div class='uPartText'>
                            Svar:<label class='uText'><?php echo $this->di->getTextFilter->doFilter(get_first_sentence($reply->text), 'markdown')?></label>
                            <label><a href='<?=$this->url->create('questions/question/'.$reply->qId.'#r'.$reply->id)?>'>Läs mer..</a></label>
                        </div>
                        <div class='uPartQuestion'>
                            Fråga:
                            <br>
                            <a href='<?=$this->url->create('questions/question/'.$reply->qId)?>'><?=$reply->qTitle?></a>
                            <label class='uCreated'><?=$reply->created?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$rPageNav?>
            <?php endif; ?>
        </div>





        <div class='aUsersCategory'><h3>De 6 senaste <i class='fa fa-trophy'></i> som <?=$item->acronym?> har delat ut</h3>
            <div class='uAnswers' style='padding-left: 10px;'>
            <?php if(empty($userTrophies)) : ?>
                <i><?=$item->acronym?> har inte delat ut några</i> <i class='fa fa-trophy'></i>
            <?php endif; ?>
            <?php if(!empty($userTrophies)) : ?>
                <?php foreach($userTrophies as $userTrophy) : ?>
                    <?php
                    if($userTrophy->correctAnswerDate){
                        if($userTrophy->correctAnswer == 'ja'){
                            $html ="<div class='uVoteHistory'>
                                <label>$item->acronym <b>gav</b> <a href='{$this->url->create('users/user/'.$userTrophy->user)}'>{$userTrophy->acronym}</a> en <i class='fa fa-trophy'></i> för hens svar i <a href='{$this->url->create('questions/question/'.$userTrophy->qId)}'>{$userTrophy->qTitel}</a></label>
                    <i>{$userTrophy->correctAnswerDate}</i>
                </div>";
                        }
                        else{
                            $html ="<div class='uVoteHistory'>
                                <label>$item->acronym <b>tog bort</b> <a href='{$this->url->create('users/user/'.$userTrophy->user)}'>{$userTrophy->acronym}</a>s <i class='fa fa-trophy fa-lg'></i> för hens svar i <a href='{$this->url->create('questions/question/'.$userTrophy->qId)}'>{$userTrophy->qTitel}</a></label>
                    <i>{$userTrophy->correctAnswerDate}</i>
                </div>";

                        }

                        echo $html;
                    }
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>






        <div class='aUsersCategory'><h3>Röstningar gjorda av <?=$item->acronym?></h3>
            <div class='uAnswers'>
            <?php if(empty($recentVotesQuestions) && empty($recentVotesAnswers) && empty($recentVotesReplies)) : ?>
                <i class='votes'><?=$item->acronym?> har inte gjort några röstningar</i>
            <?php endif; ?>
            <?php if(!empty($recentVotesQuestions) || !empty($recentVotesAnswers) || !empty($recentVotesReplies)) : ?>
            <?php if(!empty($recentVotesQuestions)) : ?>
                <h5>6 senaste röstningarna på frågor</h5>
                <?php foreach($recentVotesQuestions as $recentVoteQuestion) : ?>
                    <?php
                        if($recentVoteQuestion->value == 0){
                            $html ="<div class='uVoteHistory'>
                                <label>$item->acronym <b>tog bort</b> sin röst på <a href='{$this->url->create('users/user/'.$recentVoteQuestion->userIdQuestion)}'>{$recentVoteQuestion->userNameQuestion}</a>s fråga <a href='{$this->url->create('questions/question/'.$recentVoteQuestion->userIdQuestion)}'>{$recentVoteQuestion->qTitel}</a></label>
                    <i>{$recentVoteQuestion->date}</i>
                </div>";
                        }
                        else{
                            $html ="<div class='uVoteHistory'>
                                <label>$item->acronym gav <a href='{$this->url->create('users/user/'.$recentVoteQuestion->userIdQuestion)}'>{$recentVoteQuestion->userNameQuestion}</a> <b>{$recentVoteQuestion->value}</b> poäng för sin fråga <a href='{$this->url->create('questions/question/'.$recentVoteQuestion->userIdQuestion)}'>{$recentVoteQuestion->qTitel}</a></label>
                    <i>{$recentVoteQuestion->date}</i>
                </div>";

                        }

                        echo $html;

                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(empty($recentVotesQuestions)) : ?>
                <h5>6 senaste röstningarna på frågor</h5>
                <i class='votes'><?=$item->acronym?> har inte gjort röstningar på någon fråga.</i>
            <?php endif; ?>


            <?php if(!empty($recentVotesAnswers)) : ?>
                <h5>6 senaste röstningarna på svar</h5>
                <?php foreach($recentVotesAnswers as $recentVotesAnswer) : ?>
                    <?php
                        if($recentVotesAnswer->value == 0){
                            $html ="<div class='uVoteHistory'>
                                <label>{$item->acronym} <b>tog bort</b> sin röst på <a href='{$this->url->create('users/user/'.$recentVotesAnswer->userIdAnswer)}'>{$recentVotesAnswer->userNameAnswer}</a>s <a href ='{$this->url->create('questions/question/'.$recentVotesAnswer->qId.'#a'.$recentVotesAnswer->answerId)}'>svar</a> i <a href='{$this->url->create('questions/question/'.$recentVotesAnswer->qId)}'>{$recentVotesAnswer->qTitle}</a></label>
                                <i>{$recentVotesAnswer->date}</i>
                            </div>";
                        }
                        else{
                            $html ="<div class='uVoteHistory'>
                                <label>{$item->acronym} gav <a href='{$this->url->create('users/user/'.$recentVotesAnswer->userIdAnswer)}'>{$recentVotesAnswer->userNameAnswer}</a> <b>{$recentVotesAnswer->value}</b> poäng för sitt <a href ='{$this->url->create('questions/question/'.$recentVotesAnswer->qId.'#a'.$recentVotesAnswer->answerId)}'>svar</a> i <a href='{$this->url->create('questions/question/'.$recentVotesAnswer->qId)}'>{$recentVotesAnswer->qTitle}</a></label>
                                <i>{$recentVotesAnswer->date}</i>
                            </div>";
                        }

                        echo $html;
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(empty($recentVotesAnswers)) : ?>
                <h5>6 senaste röstningarna på svar</h5>
                <i class='votes'><?=$item->acronym?> har inte gjort röstningar på något svar.</i>
            <?php endif; ?>


            <?php if(!empty($recentVotesReplies)) : ?>
                <h5>6 senaste röstningarna på kommentarer</h5>
                <?php foreach($recentVotesReplies as $recentVotesReply) : ?>
                    <?php
                        if($recentVotesReply->value == 0){
                        $html ="<div class='uVoteHistory'>
                            <label>{$item->acronym} <b>tog bort</b> sin röst på <a href='{$this->url->create('users/user/'.$recentVotesReply->userIdReply)}'>{$recentVotesReply->userNameReply}</a>s <a href ='{$this->url->create('questions/question/'.$recentVotesReply->qId.'#r'.$recentVotesReply->replyId)}'>kommentar</a> i <a href='{$this->url->create('questions/question/'.$recentVotesReply->qId)}'>{$recentVotesReply->qTitle}</a></label>
                            <i>{$recentVotesReply->date}</i>
                        </div>";
                        }
                        else{
                            $html ="<div class='uVoteHistory'>
                            <label>{$item->acronym} gav <a href='{$this->url->create('users/user/'.$recentVotesReply->userIdReply)}'>{$recentVotesReply->userNameReply}</a> <b>{$recentVotesReply->value}</b> poäng för sin <a href ='{$this->url->create('questions/question/'.$recentVotesReply->qId.'#r'.$recentVotesReply->replyId)}'>kommentar</a> i <a href='{$this->url->create('questions/question/'.$recentVotesReply->qId)}'>{$recentVotesReply->qTitle}</a></label>
                            <i>{$recentVotesReply->date}</i>
                        </div>";
                        }

                        echo $html;
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(empty($recentVotesReplies)) : ?>
                <h5>6 senaste röstningarna på kommentarer</h5>
                <i class='votes'><?=$item->acronym?> har inte gjort röstningar på någon kommentar.</i>
            <?php endif; ?>

            <?php endif; ?>
                </div>
        </div>




        <div class='aUsersCategory'><h3>Taggar som <?=$item->acronym?> är mest involverad i</h3>
            <div class='uAnswers' style='padding-left: 10px;'>
            <?php if(empty($userTags)) : ?>
                <i><?=$item->acronym?> har inte skapat eller svarat på någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($userTags)) : ?>
                <?php foreach($userTags as $tag) : ?>
                    <div class='tagItemTagPage'>
                        <label class='qTag'><a class='tag' href='<?=$this->url->create("questions?textField=(tags:{$tag['name']})")?>'><?=$tag['name']?></a></label>
                         <i class='fa fa-times'></i>
                        <i><?=$tag['total']?></i>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>


