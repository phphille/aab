<h1><?=$title?></h1>
<div class=''>
    <form method=post>
        <fieldset>
            <label>Acronym:<br><input type='text' name='username' required/></label>
                <br>
            <label>Lösenord:<br><input type='password' name='password' required/></label>
                <br>
            <p><a href='<?=$this->url->create('users/create-user/')?>'>Inte medlem? Klicka här för att skapa ett konto!</a></p>
            <input type='submit' name='doLogin' value='Logga in'/>
        </fieldset>
    </form>
</div>
