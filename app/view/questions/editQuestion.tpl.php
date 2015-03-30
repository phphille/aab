
<h1><?=$title?></h1>
<form method='post'>
    <fieldset>
        <input type='hidden' name='id' value='<?=$id?>' required/>
        <input class='textField' type='text' name='titel' placeholder='Titel' value='<?=$titel?>' tabindex='1' required/>
        <textarea class='askQuestion' name='text' placeholder='Fråga' tabindex='2' required/><?=$text?></textarea>
    <input type='submit' name='editQuestion' value='Uppdatera'>
    </fieldset>

</form>
