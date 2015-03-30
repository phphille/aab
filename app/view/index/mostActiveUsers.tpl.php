<div class='indexContainer'>
    <h1><?=$title?></h1>
    <?php foreach($users as $user) : ?>
        <div class='inlineBlock'>
            <img src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?s=25" ?>" alt="" />
            <a href='<?=$this->di->get('url')->create('users/user/'.$user->id)?>'><label class='userName'><?=$user->acronym?></label></a>
            <i class='fa fa-trophy'></i><label class='userPoints'><?=$user->points?></label>
        </div>
    <?php endforeach; ?>
</div>
