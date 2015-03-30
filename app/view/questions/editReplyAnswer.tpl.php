
<h1><?=$title?></h1>
<div>
    <form method='post'>
        <fieldset>
            <input type='hidden' name='id' value='<?=$id?>' required/>
            <textarea class='textAreaAnswer' name='text' required><?=$text?></textarea>
            <input type='submit' name='edit' value='Uppdatera'>
        </fieldset>
    </form>
</div>
