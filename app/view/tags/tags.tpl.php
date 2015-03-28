<h1><?=$title?></h1>
<div>
    <form method='get' onchange="form.submit()">
        <fieldset>
        <input id='textField' type='text' name='textField' value='<?=$textField?>' placeholder='Sök'/>
        </fieldset>
    </form>
</div>
    <div id='tag'>
        <?php foreach($tags as $tag) : ?>
            <div id='tagItemTagPage'>
                <label id='qTag'><a id='tag' href='<?=$this->url->create('questions?textField=(tags:'.$tag->name.')')?>'><?=$tag->name?></a></label>
                 <i class='fa fa-times'></i>
                <i><?=$tag->count?></i>
            </div>
        <?php endforeach; ?>
</div>
