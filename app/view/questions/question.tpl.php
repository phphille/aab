<div class='containerComments'>
<h2><?=$title?></h2>
    <?php foreach ($question as $value) : ?>
        <?php if ($this->di->session->get('user') != null && $value->userID == $this->di->session->get('user')->id) : ?>
                    <?=$this->di->ccheckUser->editQuestion($this->di->session->get('user'), $value->userID, $value->id)?>
        <?php endif; ?>

        <?php if ($this->di->session->get('user') != null && $this->di->session->get('user')->id != $value->userID) : ?>
            <?=$this->di->ccheckUser->voteUpAnswer($this->di->session->get('user'), $value->userID, $value->id, $question[0]->id, 'question')?>
            <?=$this->di->ccheckUser->voteDownAnswer($this->di->session->get('user'), $value->userID, $value->id, $question[0]->id, 'question')?>
        <?php endif; ?>
        <label><i class='fa fa-trophy'></i> <?=$value->votes?></label>
        <p><?php echo $this->di->getTextFilter->doFilter($value->text, 'markdown')?></p>
        <p class='timestamp'>Skapad: <?=$value->created?></p>
            <?php if ($value->edited != 0) : ?>
                <p class='timestamp'>Uppdaterad: <?=$value->edited?></p>
            <?php endif; ?>
        <div id='divider'></div>
        <div id='qQuestionTags'>
            <?php
            $tags = explode(',',$value->tags);
            echo "<div id='qTags'>";
            foreach($tags as $tag){
                echo "<label id='qTag'><a id='tag' href='{$this->url->create("questions?textField=(tags:$tag)")}'>$tag</a></label>";
            }
            echo "</div>";
            ?>
        </div>
        <div id='qUserInfo'>
            <img id='qQuestionUser' src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $value->userEmail ) ) ) . "?s=50" ?>" alt="" />
            <label id='qQuestionUserName'><a href='<?=$this->url->create("users/user/$value->userID")?>'><?=$value->userName?></a></label>
            <label id='qQuestionUserPoints'><i class='fa fa-trophy'></i> <?=$value->userPoints?></label>
        </div>
    <?php endforeach; ?>
</div>

</p>
<?php if (!empty($answers)) : ?>
        <h3><?=count($answers)." Svar"?></h3>
    <div id='orderby'>
    <?php
    $selected = "";
    if($orderby == 'votes'){
       $selected = "Active";
    }
    echo "<label id='orderby$selected'><a id='tag' href='{$this->di->paginering->getQueryString(array('orderby' => 'votes'))}'>Votes</a></label>";

    $selected = "";
    if($orderby == 'created'){
       $selected = "Active";
    }
    echo "<label id='orderby$selected'><a id='tag' href='{$this->di->paginering->getQueryString(array('orderby' => 'created'))}'>Created</a></label>";

    $selected = "";
    if($order == 'asc'){
       $selected = "Active";
    }
    echo "<label id='orderby$selected'><a id='tag' href='{$this->di->paginering->getQueryString(array('order' => 'asc'))}'>ASC</a></label>";

    $selected = "";
    if($order == 'desc'){
       $selected = "Active";
    }
    echo "<label id='orderby$selected'><a id='tag' href='{$this->di->paginering->getQueryString(array('order' => 'desc'))}'>DESC</a></label>";

    ?>
    </div>
    <?php foreach ($answers as $answer) : ?>
            <div class='commentContainer'>

                <?php if ($this->di->session->get('user') != null && $answer->userID == $this->di->session->get('user')->id) : ?>
                    <?=$this->di->ccheckUser->editAnswer($this->di->session->get('user'), $answer->id, $answer->userID, $question[0]->id)?>
                <?php endif; ?>

                <?php if ($answer->correctAnswer == 'Ja' || $answer->correctAnswer == 'ja'){
                    echo "<label><i class='fa fa-check fa-2x'></i></label>";
                    echo $this->di->ccheckUser->removeCorrectAnswer($this->di->session->get('user'), $question[0]->id, $answer->id);
                }
                else{
                    echo $this->di->ccheckUser->setCorrectAnswer($this->di->session->get('user'), $question[0]->id, $answer->id);
                }
                ?>
                <div>

                    <?php if ($this->di->session->get('user') != null && $this->di->session->get('user')->id != $answer->userID) : ?>
                        <?=$this->di->ccheckUser->voteUpAnswer($this->di->session->get('user'), $answer->userID, $answer->id, $question[0]->id, 'answer')?>
                        <?=$this->di->ccheckUser->voteDownAnswer($this->di->session->get('user'), $answer->userID, $answer->id, $question[0]->id, 'answer')?>
                    <?php endif; ?>
                    <label><i class='fa fa-trophy'></i> <?=$answer->votes?></label>

                </div>
                <p><?php echo $this->di->getTextFilter->doFilter($answer->text, 'markdown')?></p>

                <p class='timestamp'>Skapad: <?=$answer->created?></p>
                <?php if ($answer->edited != 0) : ?>
                    <p class='timestamp'>Uppdaterad <?=$answer->edited?></p>
                <?php endif; ?>

                <div id='aUserInfo'>
                    <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $answer->userEmail ) ) ) . "?s=50" ?>" alt="" style='margin-right: 5px;'/>
                    <label id='qQuestionUserName'><a href='<?=$this->url->create("users/user/$answer->userID")?>'><?=$answer->userName?></a></label>
                    <label id='qQuestionUserPoints'><i class='fa fa-trophy'></i> <?=$answer->userPoints?></label>
                </div>
                <?php if(!empty($replies)) : ?>
                    <?php foreach ($replies as $reply) : ?>
                        <?php if($reply->answer == $answer->id) : ?>
                            <div class='commentReply'>
                                <i class='reply'>Kommentar</i>
                                <br>
                                <?php if ($this->di->session->get('user') != null && $reply->userID == $this->di->session->get('user')->id) : ?>
                                    <?=$this->di->ccheckUser->editReply($this->di->session->get('user'), $reply->id, $reply->userID, $question[0]->id)?>
                                <?php endif; ?>
                                <?php if ($this->di->session->get('user') != null && $this->di->session->get('user')->id != $reply->userID) : ?>
                                    <?=$this->di->ccheckUser->voteUpAnswer($this->di->session->get('user'), $reply->userID, $reply->id, $question[0]->id, 'reply')?>
                                    <?=$this->di->ccheckUser->voteDownAnswer($this->di->session->get('user'), $reply->userID, $reply->id, $question[0]->id, 'reply')?>
                                <?php endif; ?>
                                <label><i class='fa fa-trophy'></i> <?=$reply->votes?></label>
                                <p><?php echo $this->di->getTextFilter->doFilter($reply->text, 'markdown')?></p>

                                <p class='timestamp'>Skapad: <?=$reply->created?></p>
                                <?php if ($reply->edited != 0) : ?>
                                    <p class='timestamp'>Uppdaterad: <?=$reply->edited?></p>
                                <?php endif; ?>
                                <div id='aUserInfo'>
                                    <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $reply->userEmail ) ) ) . "?s=25" ?>" alt="" style='margin-right: 5px;'/>
                                    <label id='qQuestionUserName'><a href='<?=$this->url->create("users/user/$reply->userID")?>'><?=$reply->userName?></a></label>
                                    <label id='qQuestionUserPoints'><i class='fa fa-trophy'></i> <?=$reply->userPoints?></label>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if($this->di->session->get('user')) : ?>
                    <div>
                        <form method='post'>
                            <fieldset>
                                <textarea class='comment' id='textFieldReplyAnswer' name='text' placeholder='Kommentera' required></textarea>
                                <input type='hidden' name='userId' value='<?=$this->di->session->get('user')->id?>'/>
                                <input type='hidden' name='answerId' value='<?=$answer->id?>'/>
                                <input type='hidden' name='questionId' value='<?=$question[0]->id?>'/>
                                <input type='submit' name='commentReply' value='Kommentera' style='border: 2px solid #F4F4F4;'>
                            </fieldset>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if($this->di->session->get('user')) : ?>
    <div id='writeAnswer'>
        <h3>Ange ett svar</h3>
        <form method='post'>
                <textarea id='textAreaAnswer' name='text' placeholder='Ange ett svar' required></textarea>
                <input type='hidden' name='userId' value='<?=$this->di->session->get('user')->id?>'/>
                <input type='hidden' name='questionId' value='<?=$question[0]->id?>'/>
                <input type='submit' name='createAnswer' value='Svara'/>
        </form>
    </div>
<?php endif; ?>
