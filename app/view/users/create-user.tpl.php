<h1><?=$title?></h1>
<div id='createUser'>
      <form method=post>
        <fieldset>
            <label>Acronym:<br><input type='text' name='acronym' required/></label>
                <br>
            <label>Förnamn:<br><input type='text' name='firstName' required/></label>
                <br>
            <label>Efternamn:<br><input type='text' name='lastName' required/></label>
                <br>
            <label>Email:<br><input type='email' name='email' required/></label>
                <br>
            <label>Lösenord:<br><input type='password' name='password' required/></label>
                <br>
            <input type='submit' name='doCreateUser' value='Skapa'/>
        </fieldset>
    </form>
</div>

