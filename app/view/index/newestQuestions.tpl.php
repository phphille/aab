
    <h1><?=$title?></h1>
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
                <a href='<?=$this->di->get('url')->create('users/user/'.$question->userId)?>'><label id='userName'><?=$question->userName?></label></a>
                <i class='fa fa-trophy'></i><label id='userPoints'><?=$question->userPoints?></label>
            </label>
            <label id='qCreated'>
                <?=$question->created?>
            </label>
        </div>
    </div>
    <div id='containerQuestionScore'></div>
    <?php endforeach; ?>
