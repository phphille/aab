
<h1><?=$title?></h1>
<form method='post'>
    <fieldset>
        <input class='textField' type='text' name='titel' placeholder='Titel' tabindex='1' title='Ange en titel på frågan' required/>
        <textarea class='askQuestion' name='text' placeholder='Fråga' tabindex='2' title='Ange frågan' required/></textarea>
        <select name='tags[]' data-placeholder='Välj taggar, minst 1 - högst 5' class='chosen-select-deselect' tabindex='3' multiple >
            <option value=''></option>
            <?php foreach($tags as $tag) : ?>
                <option value='<?=$tag->id?>'><?=$tag->name?></option>
            <?php endforeach; ?>
        </select>
        <input class='newTags' type='text' name='newTags' placeholder='Ange ny tagg/taggar, tex "taggnamn, taggnamn". får totalt bara vara 5 st'/>
    <input type='submit' name='createQuestion' value='Skapa'>
    </fieldset>


    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js' type='text/javascript'></script>
    <script src='js/chosen_v1.4.1/chosen.jquery.js' type='text/javascript'></script>
    <script src='js/chosen_v1.4.1/docsupport/prism.js' type='text/javascript' charset='utf-8'></script>
    <script type='text/javascript'>
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true, width:'100%', max_selected_options:5},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:'95%'}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    </script>
</form>

