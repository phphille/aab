<?php foreach($user as $item) : ?>
<div id='containerJumbotron' style='text-align: center; margin-bottom: 100px; position:relative;'>
        <h1><?=$title?></h1>
    <?php if($item->deleted != null && $item->inactive != null) : ?>
        <?php if(isset($userLoggedIn->id) && $item->id == $userLoggedIn->id) : ?>
            <?php if($this->request->getGet('accountinfo')){
                    echo "<div id='editAccountInfo'><a href='?accountinfo'><i class='fa fa-indent'></i></a></div>
                    <div id='accountInfo' style='float: none;'>";
                }
                else{
                    echo "<div id='editAccountInfo'><a href='?accountinfo=show'><i class='fa fa-outdent'></i></a></div>
                    <div id='accountInfo' style='width: 0px; visibility: hidden;'>";
                }
            ?>
            <?php if($this->request->getGet('accountinfo')){
                echo "
                <form method='post'>
                    <fieldset style='text-align: left;'>
                        <input id='' type='hidden' name='id' value='{$item->id}' />
                        <label>Mailadress:</label><br/>
                        <input id='' type='email' name='email' value='{$email}' required='required' /><br/>
                        <label>Förnamn:</label><br/>
                        <input id='form-element-firstName' type='text' name='firstName' value='{$firstName}' required='required' /><br/>
                        <label>Efternamn:</label><br/>
                        <input id='' type='text' name='lastName' value='{$lastName}' required='required' /><br/>
                        <label>Lösenord:</label><br/>
                        <input id='' type='password' name='password' placeholder='Fyll bara i om du vill byta lösenord' /><br/>
                        <input id='' type='submit' name='userChangeUserInfo' value='Uppdatera'/>
                    </fieldset>
                </form>";
            }
            else{
                echo "
                <form method='post' style='display: none;>
                    <fieldset style='text-align: left; display: none;'>
                        <input id='' type='hidden' name='id' value='' style='display: none;'/>
                        <label style='display: none;'>Mailadress:</label><br/>
                        <input id='' type='email' name='email' value='' required='required' style='display: none;'/><br/>
                        <label style='width: 0px; visibility: hidden;'>Förnamn:</label><br/>
                        <input id='form-element-firstName' type='text' name='firstName' value='' required='required' style='display: none;'/><br/>
                        <label style='display: none;'>Efternamn:</label><br/>
                        <input id='' type='text' name='lastName' value='' required='required' style='display: none;'/><br/>
                        <label style='display: none;'>Lösenord:</label><br/>
                        <input id='' type='password' name='password' placeholder='Fyll bara i om du vill byta lösenord' style='display: none;'/><br/>
                        <input id='' type='submit' name='userChangeUserInfo' value='Uppdatera'  style='display: none;'/>
                    </fieldset>
                </form>";
            }
             ?>
            </div>
        <?php endif; ?>
            <div id='usersPage'>
                <img id='userImgInUsers' src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $item->email ) ) ) . "?s=100" ?>" alt="" />
                <label id='usersNameInUsers'><?=$item->acronym?></label>
                <label id='userTotalPoints'><i class='fa fa-trophy'></i> <?=$item->points?></label>
                <label id='userMemeberTypeInUsers'>Medlemstyp: <?=$item->medlemsTyp?></label>
                <label id='userCreatedInUsers'>Blev medlem <?=$item->created?></label>
            </div>
        <div class="triangle-down"><div></div></div>
        </div>
<div id='userCategories'>
        <div id='aUsersCategory'><h3>Frågor ställda av <?=$item->acronym?></h3>
            <?php if(empty($questions)) : ?>
                <i><?=$item->acronym?> har inte skapat någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($questions)) : ?>
            <?=$qHitsNav?>
                <?php foreach($questions as $question) : ?>
                    <div id='question'>
                        <div id='qPartAttribute'>
                            <div id='qAttributes'>
                                <label id='qNbr'><?=$question->votes?></label>
                                <label id='qNbrDescrip'>Poäng</label>
                            </div>
                            <div id='qAttributes'>
                                <label id='qNbr'><?=$question->nbrOfanswers?></label>
                                <label id='qNbrDescrip'>Antal svar</label>
                            </div>
                            <div id='qAttributes'>
                                <label id='qNbr'> <?php
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
                                <label id='qNbrDescrip'>Korrekt svar</label>
                            </div>
                        </div>
                        <div id='qPartQuestion'>
                            <label id='qTitle'><a href='<?=$this->di->get('url')->create('questions/question/'.$question->id)?>'><?=$question->titel?></a></label>
                            <?php
                                $tags = explode(',',$question->tags);
                                echo "<div id='qTags'>";
                                foreach($tags as $tag){
                                    echo "<label id='qTag'><a id='tag' href='{$this->url->create('questions?textField=(tags:'.$tag.')')}'>$tag</a></label>";
                                }
                                echo "</div>";
                            ?>
                        </div>
                        <div id='qPartUser'>
                            <label id='qUser'>
                                <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $question->userEmail ) ) ) . "?s=25" ?>" alt="" />
                                <a href='<?=$this->di->get('url')->create('users/user/'.$question->user)?>'><label id='userName'><?=$question->userName?></label></a>
                                <i class='fa fa-trophy'></i><label id='userPoints'><?=$question->userPoints?></label>
                            </label>
                            <label id='qCreated'>
                                <?=$question->created?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$qPageNav?>
            <?php endif; ?>
        </div>
        <div id='aUsersCategory'>
            <h3>Angivna svar av <?=$item->acronym?></h3>
            <?php if(empty($answers)) : ?>
                <i><?=$item->acronym?> har inte svarat på någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($answers)) : ?>
            <?=$aHitsNav?>

                <?php foreach($answers as $answer) : ?>
                    <div id='uAnswers'>
                        <div id='uPartAttribute'>
                            <div id='uAttributes'>
                                    <label id='uNbr'> <?php
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
                                    <label id='uNbrDescrip'>Korrekt svar</label>
                            </div>
                            <div id='uAttributes'>
                                <label id='uNbr'><?=$answer->votes?></label>
                                <label id='uNbrDescrip'>Poäng</label>
                            </div>
                        </div>
                        <div id='uPartText'>
                            Svar:<label id='uText'><?php echo $this->di->getTextFilter->doFilter(get_first_sentence($answer->text), 'markdown')?></label>
                            <label><a href='<?=$this->url->create('questions/question/'.$answer->questionId)?>'>Läs mer..</a></label>
                        </div>
                        <div id='uPartQuestion'>
                            Fråga:
                            <br>
                            <a href='<?=$this->url->create('questions/question/'.$answer->questionId)?>'><?=$answer->questionTitel?></a>
                            <label id='uCreated'><?=$answer->created?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$aPageNav?>
            <?php endif; ?>
        </div>



        <div id='aUsersCategory'>
            <h3>Kommentarer gjorda av <?=$item->acronym?></h3>
            <?php if(empty($replies)) : ?>
                <i><?=$item->acronym?> har inte gjort några kommentarer</i>
            <?php endif; ?>
            <?php if(!empty($replies)) : ?>
            <?=$rHitsNav?>
                <?php foreach($replies as $reply) : ?>
                      <div id='uAnswers'>
                        <div id='uPartAttribute'>
                            <div id='uAttributes'>
                                <label id='uNbr'><?=$reply->votes?></label>
                                <label id='uNbrDescrip'>Poäng</label>
                            </div>
                        </div>
                        <div id='uPartText'>
                            Svar:<label id='uText'><?php echo $this->di->getTextFilter->doFilter(get_first_sentence($reply->text), 'markdown')?></label>
                            <label><a href='<?=$this->url->create('questions/question/'.$reply->qId)?>'>Läs mer..</a></label>
                        </div>
                        <div id='uPartQuestion'>
                            Fråga:
                            <br>
                            <a href='<?=$this->url->create('questions/question/'.$reply->qId)?>'><?=$reply->qTitle?></a>
                            <label id='uCreated'><?=$reply->created?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?=$rPageNav?>
            <?php endif; ?>
        </div>




        <div id='aUsersCategory'><h3>De 18 senaste röstningar gjorda av <?=$item->acronym?></h3>
            <div id='uAnswers'>
            <?php if(empty($recentVotes)) : ?>
                <i><?=$item->acronym?> har inte gjort några röstningar</i>
            <?php endif; ?>
            <?php if(!empty($recentVotes)) : ?>
                <?php foreach($recentVotes as $recentVote) : ?>
                    <?php if($recentVote['voteType'] == 'voteQuestion') : ?>
                    <?php
                        if($recentVote['value'] == 0){
                            $html ="<div id='uVoteHistory'>
                                <label>$item->acronym tog bort sin röst på <a href='{$this->url->create('users/user/'.$recentVote['userIdQuestion'])}'>{$recentVote['userNameQuestion']}</a>s fråga <a href='{$this->url->create('questions/question/'.$recentVote['userIdQuestion'])}'>{$recentVote['qTitel']}</a></label>
                    <label>{$recentVote['date']}</label>
                </div>";
                        }
                        else{
                            $html ="<div id='uVoteHistory'>
                                <label>$item->acronym gav <a href='{$this->url->create('users/user/'.$recentVote['userIdQuestion'])}'>{$recentVote['userNameQuestion']}</a> <b>{$recentVote['value']}</b> poäng för sin fråga <a href='{$this->url->create('questions/question/'.$recentVote['userIdQuestion'])}'>{$recentVote['qTitel']}</a></label>
                    <label>{$recentVote['date']}</label>
                </div>";

                        }

                        echo $html;

                    ?>
                    <?php endif; ?>

                    <?php if($recentVote['voteType'] == 'voteAnswer') : ?>
                    <?php
                        if($recentVote['value'] == 0){
                            $html ="<div id='uVoteHistory'>
                                <label>{$item->acronym} tog bort sin röst på <a href='{$this->url->create('users/user/'.$recentVote['userIdAnswer'])}'>{$recentVote['userNameAnswer']}</a>s svar i <a href='{$this->url->create('questions/question/'.$recentVote['qId'])}'>{$recentVote['qTitle']}</a></label>
                                <label>{$recentVote['date']}</label>
                            </div>";
                        }
                        else{
                            $html ="<div id='uVoteHistory'>
                                <label>{$item->acronym} gav <a href='{$this->url->create('users/user/'.$recentVote['userIdAnswer'])}'>{$recentVote['userNameAnswer']}</a> <b>{$recentVote['value']}</b> poäng för sitt svar i <a href='{$this->url->create('questions/question/'.$recentVote['qId'])}'>{$recentVote['qTitle']}</a></label>
                                <label>{$recentVote['date']}</label>
                            </div>";
                        }

                        echo $html;
                    ?>
                    <?php endif; ?>

                    <?php if($recentVote['voteType'] == 'voteReply') : ?>
                    <?php
                        if($recentVote['value'] == 0){
                        $html ="<div id='uVoteHistory'>
                            <label>{$item->acronym} tog bort sin röst på <a href='{$this->url->create('users/user/'.$recentVote['userIdReply'])}'>{$recentVote['userNameReply']}</a>s kommentar i <a href='{$this->url->create('questions/question/'.$recentVote['qId'])}'>{$recentVote['qTitle']}</a></label>
                            <label>{$recentVote['date']}</label>
                        </div>";
                        }
                        else{
                            $html ="<div id='uVoteHistory'>
                            <label>{$item->acronym} gav <a href='{$this->url->create('users/user/'.$recentVote['userIdReply'])}'>{$recentVote['userNameReply']}</a> <b>{$recentVote['value']}</b> poäng för sin kommentar i <a href='{$this->url->create('questions/question/'.$recentVote['qId'])}'>{$recentVote['qTitle']}</a></label>
                            <label>{$recentVote['date']}</label>
                        </div>";
                        }

                        echo $html;
                    ?>
                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>
                </div>
        </div>




        <div id='aUsersCategory'><h3>Taggar som <?=$item->acronym?> är mest involverad i</h3>
            <div id='uAnswers' style='padding-left: 10px;'>
            <?php if(empty($userTags)) : ?>
                <i><?=$item->acronym?> har inte skapat eller svarat på någon fråga</i>
            <?php endif; ?>
            <?php if(!empty($userTags)) : ?>
                <?php foreach($userTags as $tag) : ?>
                    <div id='tagItemTagPage'>
                        <label id='qTag'><a id='tag' href='<?=$this->url->create("questions?textField=(tags:{$tag['name']})")?>'><?=$tag['name']?></a></label>
                         <i class='fa fa-times'></i>
                        <i><?=$tag['total']?></i>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>


