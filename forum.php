  
<script type="text/javascript">
    /*$.noConflict();*/
    $(document).ready(function () {

      //SETUP TOPIC VARIABLES THAT DEFINED IN MAIN PROGRAM PAGE
      var topic1 = <?php if(isset($topicIds['0'])) {echo  " '$topicIds[0]' ";} else echo ''; ?>;
      var topic2 = <?php if(isset($topicIds['1'])) {echo  " '$topicIds[1]' ";} else echo ''; ?>;
      var topic3 = <?php if(isset($topicIds['2'])) {echo  " '$topicIds[2]' ";} else echo ''; ?>;

      $( ".accred_reqs" ).click(function() {
          
          var target = $(this).attr( "title" );
          window.parent.showTab(target);
          
      });

     //Make a general chain for toggle sections and switch the id of the clicked item.
      $( "#toggle_post_BMS_107_topic_04, #toggle_post_BMS_107_topic_05, #toggle_post_BMS_107_topic_06, #toggle_comments_BMS_107_topic_04, #toggle_comments_BMS_107_topic_05, #toggle_comments_BMS_107_topic_06" ).click(function() {
         var clickedItem = $(this).attr( "id" );
         var toogleItem = '';
       
         switch (clickedItem)
         {
         case 'toggle_post_BMS_107_topic_04':
           toogleItem="#BMS_107_topic_04_form";
           break;
         case 'toggle_post_BMS_107_topic_05':
           toogleItem="#BMS_107_topic_05_form";
           break;
         case 'toggle_post_BMS_107_topic_06':
           toogleItem="#BMS_107_topic_06_form";
           break;
         case 'toggle_comments_BMS_107_topic_04':
           toogleItem="#comments_BMS_107_topic_04";
           break;
         case 'toggle_comments_BMS_107_topic_05':
           toogleItem="#comments_BMS_107_topic_05";
           break;
         case 'toggle_comments_BMS_107_topic_06':
           toogleItem="#comments_BMS_107_topic_06";
           break;
         }

          //Toggle the item
         $(toogleItem).toggle( "slow", function() {
         });

      });

      //Submit form listeners
     $( "#BMS_107_topic_04_form, #BMS_107_topic_05_form, #BMS_107_topic_06_form" ).submit(function( event ) {

          var submittedForm = $(this).attr( "id" );
          var comment = '';
          var topic = '';

          // customize Parsley errors class to append the errorwraper ul inside the container div specified
          $( '#' + submittedForm ).parsley( {
              errors: {
                  container: function (element) {
                      var $container = element.parent().find(".parsley-container");
                      if ($container.length === 0) {
                          $container = $("<div class='parsley-container forum'></div>").insertBefore(element);
                      }
                      return $container;
                  }
              }
          } );

          //since the form is submitted using jQuery event, bind the form with Parsley.
          var form_valid = $( '#' + submittedForm ).parsley( 'validate' );
          
          //prevent form submission if parsley returns false
          if ( !form_valid )
          {
              event.preventDefault();
          }
          
          else{

            //Swith the form that was submitted and set comment and topic id to be sent through Ajax
            switch (submittedForm)
            {
               case 'BMS_107_topic_04_form':
                 comment = $('textarea#comment_BMS_107_topic_04').val();
                 topic = topic1;
                 break;
                case 'BMS_107_topic_05_form':
                 comment = $('textarea#comment_BMS_107_topic_05').val();
                 topic = topic2;
                 break;
                case 'BMS_107_topic_06_form':
                 comment = $('textarea#comment_BMS_107_topic_06').val();
                 topic = topic3;
                 break;
               default:
                 comment="";
                 topic="";
            }

          forum_submitted = { "topic":topic, "comment":comment, "submit_comment":1}; 
          target = "/fr/programs/lib/php/forum_topics.php";
          
          // make an ajax call to save answers in the database
          $.ajax({
              url: target,
              cache: false,
              type: "POST",
              dataType: "html",
              data: forum_submitted
            }) 

          .done(function( data ) {
          	  if (data === "posted"){
              		document.location.reload(true); //reload page in order to update access to post-test form
          	  }

              if (data === "failed"){
                    $( ".parsley-container" ).html( "<ul style='width:562px;font-weight:bold;'> <li>Il s'est produit une erreur, votre commentaire ne peut être enregistré. S'il vous plaît, essayez encore.</li></ul>" );
                    $( ".parsley-container" ).show();
                    $("html, body").animate({
                          scrollTop: 0
                    }, 500);    
              }

          })
          .fail(function() {
                    $( ".parsley-container" ).html( "<ul style='width:562px;font-weight:bold;'> <li>Il s'est produit une erreur, votre commentaire ne peut être enregistré. S'il vous plaît, essayez encore.</li></ul>" );
                    $( ".parsley-container" ).show();
                    $("html, body").animate({
                          scrollTop: 0
                    }, 500);    
          }); //ajax call
        
        }//end else
        
      	  event.preventDefault();

      }); //click function   


     /*--------------------------LOAD TOPIC 1-------------------------------------------------   */

     $("#BMS_107_topic_04").load("/fr/programs/lib/php/forum_topics.php?action=get_rows&topic_id=" + topic1);

      //This is called the first time the document loads
      $.get("/fr/programs/lib/php/forum_topics.php?action=row_count&topic_id=" + topic1, function(data) {
        $("#page_count_BMS_107_topic_04").val(Math.ceil(data / 10)); //Sets hidden input field with the number of pages (total rows/10)
        generateRows(1, topic1);
      });

      /*--------------------------LOAD TOPIC 2-------------------------------------------------   */

      $("#BMS_107_topic_05").load("/fr/programs/lib/php/forum_topics.php?action=get_rows&topic_id=" + topic2);

      //This is called the first time the document loads
      $.get("/fr/programs/lib/php/forum_topics.php?action=row_count&topic_id=" + topic2, function(data) {
        $("#page_count_BMS_107_topic_05").val(Math.ceil(data / 10)); //Sets hidden input field with the number of pages (total rows/10)
        generateRows(1, topic2);
      });

      /*--------------------------LOAD TOPIC 3-------------------------------------------------   */

      $("#BMS_107_topic_06").load("/fr/programs/lib/php/forum_topics.php?action=get_rows&topic_id=" + topic3);

      //This is called the first time the document loads
      $.get("/fr/programs/lib/php/forum_topics.php?action=row_count&topic_id=" + topic3, function(data) {
        $("#page_count_BMS_107_topic_06").val(Math.ceil(data / 10)); //Sets hidden input field with the number of pages (total rows/10)
        generateRows(1, topic3);
      });

  });//end document.ready

function generateRows(selected, topicId) {
  var pages = $("#page_count_" + topicId).val();  //number of pages in the hidden input field
  //selected is the field passed to this function (number of pages)
  
  if (pages <= 5) {
    //inserts all numbers after content
    $("#" + topicId).after("<div id='paginator_" + topicId + "'><ul class='pagor_group'><li class='pagor_" + topicId + " selected'>1</li><li class='pagor_" + topicId + "'>2</li><li  class='pagor_" + topicId + "'>3</li><li  class='pagor_" + topicId + "'>4</li><li  class='pagor_" + topicId + "'>5</li><div style='clear:both;'></div></ul></div>");
    //inserts rows based on the index of the number
    $(".pagor_" + topicId).click(function() {
      var index = $(".pagor_" + topicId).index(this);
      $("#" + topicId).load("/fr/programs/lib/php/forum_topics.php?action=get_rows&topic_id=" + topicId + "&start=" + index);
      $(".pagor_" + topicId).removeClass("selected");
      $(this).addClass("selected");
    });   
  } else {
    if (selected < 5) {  
      // Draw the first 5 then have ... link to last
      var pagers = "<div id='paginator_" + topicId + "'><ul class='pagor_group'>";
      for (i = 1; i <= 5; i++) {
        if (i == selected) {
          pagers += "<li class='pagor_" + topicId + " selected'>" + i + "</li>";
        } else {
          pagers += "<li class='pagor_" + topicId + "'>" + i + "</li>";
        }       
      }
      //last number should be 5 with ... before
      pagers += "<div style='float:left;padding-left:6px;padding-right:6px;'>...</div><li class='pagor_" + topicId + "'>" + Number(pages) + "</li><div style='clear:both;'></div></ul></div>";
      
      $("#paginator_" + topicId + "").remove();
      $("#" + topicId).after(pagers);
      $(".pagor_" + topicId).click(function(  ) {
        updatePage(this, topicId);
      });
    } else if (selected > (Number(pages) - 4)) {
      // Draw ... link to first then have the last 5
      var pagers = "<div id='paginator_" + topicId + "'><ul class='pagor_group'><li class='pagor_" + topicId + "'>1</li><div style='float:left;padding-left:6px;padding-right:6px;'>...</div>";
      for (i = (Number(pages) - 4); i <= Number(pages); i++) {
        if (i == selected) {
          pagers += "<li class='pagor_" + topicId + " selected'>" + i + "</li>";
        } else {
          pagers += "<li class='pagor_" + topicId + "'>" + i + "</li>";
        }       
      }     
      pagers += "<div style='clear:both;'></div></ul></div>";
      
      $("#paginator_" + topicId + "").remove();
      $("#" + topicId).after(pagers);
      $(".pagor_" + topicId).click(function( ) {
        updatePage(this, topicId);
      });   
    } else {
      // Draw the number 1 element, then draw ... 2 before and two after and ... link to last
      var pagers = "<div id='paginator_" + topicId + "'><ul class='pagor_group'><li class='pagor_" + topicId + "'>1</li><div style='float:left;padding-left:6px;padding-right:6px;'>...</div>";
      for (i = (Number(selected) - 2); i <= (Number(selected) + 2); i++) {
        if (i == selected) {
          pagers += "<li class='pagor_" + topicId + " selected'>" + i + "</li>";
        } else {
          pagers += "<li class='pagor_" + topicId + "'>" + i + "</li>";
        }
      }
      pagers += "<div style='float:left;padding-left:6px;padding-right:6px;'>...</div><li class='pagor_" + topicId + "'>" + pages + "</li><div style='clear:both;'></div></ul></div>";
      
      $("#paginator_" + topicId + "").remove();
      $("#" + topicId).after(pagers);
      $(".pagor_" + topicId).click(function( ) {
        updatePage(this, topicId);
      });     
    }
  }
}

function updatePage(elem, topicId) {
  // Retrieve the number stored and position elements based on that number
  var selected = $(elem).text();

  // First update 
  $("#" + topicId).load("/fr/programs/lib/php/forum_topics.php?action=get_rows&topic_id=" + topicId + "&start=" + (selected - 1));
  
  // Then update links
  generateRows(selected, topicId);
}
</script>
<table border="0" cellspacing="0">
<tr valign="top">
<td style="padding:0;width:620px;">
<div id="intro">
<h2 style="margin:0 0 15px 0;">Forum de discussion</h2>
<h3 style="line-height:25px;">Pour recevoir votre certificat, <span class="accred_reqs" title="tab7">cliquez ici</span>. Notez que si vous avez des exigences d'agrément non satisfaites, vous serez redirigé vers une liste de documents à compléter afin de recevoir votre certificat.
</h3>
<hr/>
<?php if(isset($_SESSION['posted']) && $_SESSION['posted']) { echo "<h3 id='assessment'>Nous vous remercions de votre participation dans le forum de discussion de ce programme.</h3>"; unset($_SESSION['posted']); }?>
<div class="parsley-container" style="display:none;"> </div>

<div class="question forum" ><p>1) Quelles sont les principales forces et lacunes des données en situation réelle comparativement à celles provenant d’études contrôlées à répartition aléatoire?</p>
</div>

<!-- THIS SECTION IS A TOGGLE -->
<div class="toggle" >
    <p id="toggle_post_BMS_107_topic_04" ><sub style="font-size:15px;">&#8618;</sub> Ajouter un commentaire</p> 
    <p id="toggle_comments_BMS_107_topic_04">&nbsp;&nbsp;<sub style="font-size:15px;">&#8618;</sub> Voir les commentaires </p>
</div>
<!-- THIS SECTION IS A TOGGLE -->

<!-- THIS SECTION IS A FORM -->
<form class="jotform-form" action="" method="POST" data-ajax="false" name="BMS_107_topic_04_form" id="BMS_107_topic_04_form" accept-charset="utf-8" style="display:none;">
  <div class="program_evaluation" style="width:620px;">
    <ul class="form-section">
      <li class="form-line" style="margin:0;padding:0;">
          <textarea class="form-textarea" name="comment" parsley-required="true" parsley-error-message="Veuillez entrer un commentaire (500 caractères maximum)" parsley-maxlength="500" id="comment_BMS_107_topic_04" rows="5" style="width: 620px;max-width: 620px;margin:0;padding:0;"></textarea>
          <div class="form-textarea-limit-indicator"><span class="comment_BMS_107_topic_04">500 caractères maximum</span>
              </div>
      </li>
      <li id="BMS_107_topic_04_actions">
          <div style="padding:10px 0 15px 0;" class="form-buttons-wrapper">
            <button id="submit_BMS_107_topic_04_form" type="submit" class="form-submit-button-cool_grey_rounded" >Poster</button>
            <button id="reset_BMS_107_topic_04_form" type="reset" class="form-submit-button-cool_grey_rounded">Effacer</button>
          </div>
      </li>
    </ul>
  </div>
</form>
<!-- THIS SECTION IS A FORM -->

<!-- THIS SECTION IS A PAGINATION -->
<div class="pagination" id="comments_BMS_107_topic_04" style="display:none;">
    <div id="BMS_107_topic_04"></div>
  <input type="hidden" name="page_count_BMS_107_topic_04" id="page_count_BMS_107_topic_04" />
</div>
<!-- THIS SECTION IS A PAGINATION -->

<div class="question forum">
<p>2) Lorsque vous êtes en présence d’un patient atteint de FANV, quel système d’évaluation du risque utilisez-vous pour déterminer si vous instaurez ou non une anticoagulothérapie orale pour la prévention de l’AVC? Veuillez expliquer pourquoi. </p>
</div>

<!-- THIS SECTION IS A TOGGLE -->
<div class="toggle" ><p id="toggle_post_BMS_107_topic_05" ><sub style="font-size:15px;">&#8618;</sub> Ajouter un commentaire</p> <p id="toggle_comments_BMS_107_topic_05">&nbsp;&nbsp;<sub style="font-size:15px;">&#8618;</sub> Voir les commentaires </p></div>
<!-- THIS SECTION IS A TOGGLE -->

<!-- THIS SECTION IS A FORM -->
<form class="jotform-form" action="" method="POST" data-ajax="false" name="BMS_107_topic_05_form" id="BMS_107_topic_05_form" accept-charset="utf-8" style="display:none;">
  <div class="program_evaluation" style="width:620px;">
    <ul class="form-section">
      <li class="form-line" style="margin:0;padding:0;">
          <textarea class="form-textarea" name="comment" parsley-required="true" parsley-error-message="Veuillez entrer un commentaire (500 caractères maximum)" parsley-maxlength="500" id="comment_BMS_107_topic_05" rows="5" style="width: 620px;max-width: 620px;margin:0;padding:0;"></textarea>
          <div class="form-textarea-limit-indicator"><span class="comment_BMS_107_topic_05" >500 caractères maximum</span>
              </div>
      </li>
      <li id="BMS_107_topic_05_actions">
          <div style="padding:10px 0 15px 0;" class="form-buttons-wrapper">
            <button id="submit_BMS_107_topic_05_form" type="submit" class="form-submit-button-cool_grey_rounded" >Poster</button>
            <button id="reset_BMS_107_topic_05_form" type="reset" class="form-submit-button-cool_grey_rounded">Effacer</button>
          </div>
      </li>
    </ul>
  </div>
</form>
<!-- THIS SECTION IS A FORM -->

<!-- THIS SECTION IS A PAGINATION -->
<div class="pagination" id="comments_BMS_107_topic_05" style="display:none;">
    <div id="BMS_107_topic_05"></div>
  <input type="hidden" name="page_count_BMS_107_topic_05" id="page_count_BMS_107_topic_05" />
</div>
<!-- THIS SECTION IS A PAGINATION -->

<div class="question forum">
<p>3) En ce qui concerne l’efficacité, quels AOD recommanderiez-vous d’utiliser chez un patient atteint de FA présentant un risque élevé d’AVC? Veuillez expliquer pourquoi.</p>
</div>

<!-- THIS SECTION IS A TOGGLE -->
<div class="toggle" ><p id="toggle_post_BMS_107_topic_06" ><sub style="font-size:15px;">&#8618;</sub> Ajouter un commentaire</p> <p id="toggle_comments_BMS_107_topic_06"> &nbsp;&nbsp;<sub style="font-size:15px;">&#8618;</sub> Voir les commentaires </p></div>
<!-- THIS SECTION IS A TOGGLE -->

<!-- THIS SECTION IS A FORM -->
<form class="jotform-form" action="" method="POST" data-ajax="false" name="BMS_107_topic_06_form" id="BMS_107_topic_06_form" accept-charset="utf-8" style="display:none;">
  <div class="program_evaluation" style="width:620px;">
    <ul class="form-section">
      <li class="form-line" style="margin:0;padding:0;">
          <textarea class="form-textarea" name="comment" parsley-required="true" parsley-error-message="Veuillez entrer un commentaire (500 caractères maximum)" parsley-maxlength="500" id="comment_BMS_107_topic_06" rows="5" style="width: 620px;max-width: 620px;margin:0;padding:0;"></textarea>
          <div class="form-textarea-limit-indicator"><span class="comment_BMS_107_topic_06" >500 caractères maximum</span>
              </div>
      </li>
      <li id="BMS_107_topic_06_actions">
          <div style="padding:10px 0 15px 0;" class="form-buttons-wrapper">
            <button id="submit_BMS_107_topic_06_form" type="submit" class="form-submit-button-cool_grey_rounded" >Poster</button>
            <button id="reset_BMS_107_topic_06_form" type="reset" class="form-submit-button-cool_grey_rounded">Effacer</button>
          </div>
      </li>
    </ul>
  </div>
</form>
<!-- THIS SECTION IS A FORM -->

<!-- THIS SECTION IS A PAGINATION -->
<div class="pagination" id="comments_BMS_107_topic_06" style="display:none;">
    <div id="BMS_107_topic_06"></div>
  <input type="hidden" name="page_count_BMS_107_topic_06" id="page_count_BMS_107_topic_06" />
</div>
<!-- THIS SECTION IS A PAGINATION -->

</td>
  </tr>
</table>
