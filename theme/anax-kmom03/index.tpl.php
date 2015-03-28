<!doctype html>
<html class='no-js' lang='<?=$lang?>' <?php if($this->request->getGet('show-grid', 32) != null) echo "style='background-image: none;'";?> >
<head>
<meta charset='utf-8'/>
<title><?=$title . $title_append?></title>
<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
<?php foreach($stylesheets as $stylesheet): ?>
<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
<?php endforeach; ?>
<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
<script src='<?=$this->url->asset($modernizr)?>'></script>
</head>

<body>

<div id='wrapper'>


    <?php if ($this->views->hasContent('navbar')) : ?>
    <div id='navbar' style='<?php $this->views->render('background')?>'>
    <?php $this->views->render('navbar')?>
    </div>
    <?php endif; ?>



    <?php if ($this->views->hasContent('jumbotron')) : ?>
        <div id='jumbotron' style='<?php $this->views->render('background')?>'>
            <div id='containerJumbotron'>
                <?php $this->views->render('jumbotron')?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->views->hasContent('container')) : ?>
        <div id='container' style='<?php $this->views->render('background')?>'>

            <br>
            <?php if ($this->views->hasContent('col-md-1')) : ?>
                <div id='col-md-1' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('col-md-1')?>
                </div>
            <?php endif; ?>
            <?php if ($this->views->hasContent('col-md-2')) : ?>
                <div id='col-md-2' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('col-md-2')?>
                </div>
            <?php endif; ?>
            <?php if ($this->views->hasContent('col-md-3')) : ?>
                <div id='col-md-3' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('col-md-3')?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($this->views->hasContent('jumbotron2')) : ?>
        <div id='jumbotron2' style='<?php $this->views->render('background')?>'>
            <div id='containerJumbotron'>
                <?php $this->views->render('jumbotron2')?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->views->hasContent('containerFooter')) : ?>
    <div id='containerFooter' style='<?php $this->views->render('background')?>'>

            <?php if ($this->views->hasContent('footer-col-1')) : ?>
                <div id='footer-col-1' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('footer-col-1')?>
                </div>
            <?php endif; ?>
            <?php if ($this->views->hasContent('footer-col-2')) : ?>
                <div id='footer-col-2' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('footer-col-2')?>
                </div>
            <?php endif; ?>
            <?php if ($this->views->hasContent('footer-col-3')) : ?>
                <div id='footer-col-3' style='<?php $this->views->render('background')?>'>
                    <?php $this->views->render('footer-col-3')?>
                </div>
            <?php endif; ?>
    </div>
    <?php endif; ?>




    <?php if(isset($jquery)):?><script src='<?=$this->url->asset($jquery)?>'></script><?php endif; ?>




<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$this->url->asset($val)?>'></script>
<?php endforeach; endif; ?>

<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

</body>
</html>
