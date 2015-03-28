
<h1><?=$title?></h1>
<div>
    <form method='get' onchange="form.submit()">
        <fieldset>
        <input id='textField' type='text' name='textField' value='<?=$textField?>' placeholder='Sök'/>
        <i id='input'>Söka på flera taggar ange tex: "(tags: hantel, skivstång)"</i>
        </fieldset>
    </form>
</div>
<?=$hits?>
<div id='indexContainer'>
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
</div>
<?=$pages?>
