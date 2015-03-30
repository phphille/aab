<h1><?=$title?></h1>
<div>
    <form method='get' onchange="form.submit()">
        <fieldset>
        <input type='text' name='textField' value='<?=$textField?>' placeholder='Sök'/>
        </fieldset>
    </form>
</div>
<div class='users'>
    <div class='orderby'>
    <?php
    $selected = "";
    if($orderby == 'points'){
       $selected = "Active";
    }
    echo "<label class='orderby$selected'><a class='tag' href='{$this->di->paginering->getQueryString(array('orderby' => 'points'))}'>Poäng</a></label>";

    $selected = "";
    if($orderby == 'created'){
       $selected = "Active";
    }
    echo "<label class='orderby$selected'><a class='tag' href='{$this->di->paginering->getQueryString(array('orderby' => 'created'))}'>Skapad</a></label>";

    $selected = "";
    if($order == 'asc'){
       $selected = "Active";
    }
    echo "<label class='orderby$selected'><a class='tag' href='{$this->di->paginering->getQueryString(array('order' => 'asc'))}'>ASC</a></label>";

    $selected = "";
    if($order == 'desc'){
       $selected = "Active";
    }
    echo "<label class='orderby$selected'><a class='tag' href='{$this->di->paginering->getQueryString(array('order' => 'desc'))}'>DESC</a></label>";

    ?>
    </div>
    <?=$hits?>
    <?php foreach($users as $user) : ?>
        <?php if($user->deleted != null && $user->inactive != null) : ?>
            <div class='user'>
                <img class='userImgInUsers'src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?s=50" ?>" alt="" />
                <label class='userNameInUsers'><a href='<?=$this->url->create('users/user/'.$user->id)?>'><?=$user->acronym?></a></label>
                <i class='fa fa-trophy'></i><label class='userTotalPointsInUsers'><?=$user->points?></label>
                <label class='userCreatedInUsers'>Blev medlem <?=$user->created?></label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?=$pages?>
</div>


