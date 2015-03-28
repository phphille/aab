<h1><?=$title?></h1>
<div>
    <form method='get' onchange="form.submit()">
        <fieldset>
        <input type='text' name='textField' value='<?=$textField?>' placeholder='Sök'/>
        </fieldset>
    </form>
</div>
<div id='users'>
    <?php foreach($users as $user) : ?>
        <?php if($user->deleted != null && $user->inactive != null) : ?>
            <div id='user'>
                <img id='userImgInUsers'src="<?php echo "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) ) . "?s=50" ?>" alt="" />
                <label id='userNameInUsers'><a href='<?=$this->url->create('users/user/'.$user->id)?>'><?=$user->acronym?></a></label>
                <i class='fa fa-trophy'></i><label id='userTotalPointsInUsers'><?=$user->points?></label>
                <label id='userCreatedInUsers'>Blev medlem <?=$user->created?></label>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>


