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


    <div id='navbar'>
    <?php if ($this->views->hasContent('header')) : ?>
        <?php $this->views->render('header') ?>
    <?php endif; ?>

    <?php if ($this->views->hasContent('navbar')) : ?>
        <?php $this->views->render('navbar') ?>
    <?php endif; ?>
    </div>


    <?php if ($this->views->hasContent('jumbotronOnly')) : ?>
        <div id='jumbotron'>
                <?php $this->views->render('jumbotronOnly')?>
        </div>
    <?php endif; ?>


    <?php if ($this->views->hasContent('jumbotron')) : ?>
        <div id='jumbotron'>
            <div id='containerJumbotron'>
                <?php $this->views->render('jumbotron')?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->views->hasContent('jumbotronUsers')) : ?>
        <div id='jumbotron'>
            <div id='containerJumbotronUsers'>
                <?php $this->views->render('jumbotronUsers')?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->views->hasContent('containerLeft') || $this->views->hasContent('containerRight') ) : ?>
            <div id='container2container'>
                <?php if ($this->views->hasContent('containerLeft')) : ?>
                    <div id='containerLeft'>
                        <?php $this->views->render('containerLeft')?>
                    </div>
                <?php endif; ?>

                 <?php if ($this->views->hasContent('containerRight')) : ?>
                    <div id='containerRight'>
                        <?php $this->views->render('containerRight')?>
                    </div>
                <?php endif; ?>
            </div>
    <?php endif; ?>



    <script language="javascript">
        function autoResizeDiv()
        {
            document.getElementById('jumbotron').style.minHeight = (window.innerHeight * 0.617) +'px' ;
        }
        window.onresize = autoResizeDiv;
        autoResizeDiv();
    </script>

    <?php if ($this->views->hasContent('footer')) : ?>
    <div id='containerFooter'>
            <?php $this->views->render('footer')?>
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
