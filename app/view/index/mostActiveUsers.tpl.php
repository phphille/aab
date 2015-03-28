<div id='indexContainer'>
    <h1><?=$title?></h1>
    <?php foreach($users as $user) : ?>
        <div class='inlineBlock'>
            <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?s=25" ?>" alt="" />
            <a href='<?=$this->di->get('url')->create('users/user/'.$user->id)?>'><label id='userName'><?=$user->acronym?></label></a>
            <i class='fa fa-trophy'></i><label id='userPoints'><?=$user->points?></label>
        </div>
    <?php endforeach; ?>
</div>
