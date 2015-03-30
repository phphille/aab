
    <h1><?=$title?></h1>
    <?php foreach($tags as $tag) : ?>
        <div class='tagItem'>
            <label class='qTag'><a class='tag' href='<?=$this->url->create('questions?textField=(tags:'.$tag->name.')')?>'><?=$tag->name?></a></label>
             <i class='fa fa-times'></i>
            <i><?=$tag->TotalCount?></i>
        </div>
    <?php endforeach; ?>

