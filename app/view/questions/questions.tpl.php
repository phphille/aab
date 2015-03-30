
<h1><?=$title?></h1>
    <form method='get' onchange="form.submit()">
        <fieldset>
        <input type='text' name='textField' value='<?=$textField?>' placeholder='Sök'/>
        <i class='input'>Kombinera sök ord med taggar, ange tex: "(tags: hantel, skivstång) problem med grepp"</i>
        </fieldset>
    </form>
<?=$hits?>
<div class='indexContainer'>
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
</div>
<?=$pages?>
