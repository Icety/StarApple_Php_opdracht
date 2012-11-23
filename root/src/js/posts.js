/* savePost()
 * Gathers information from the form and sends through ajax towards /post/save
 * In: Null
 * Out: dataString: containing PostID, type, title, message and token
*/
function savePost() {
  PostID = $('#form_PostID').val();
  type = $('#form_type').val();
  title = $('#form_title').val();
  message = $('#form_message').val();
  token = $('#form__token').val();
  var dataString = 'form[PostID]='+ PostID +'&form[type]='+ type +'&form[title]='+ title + '&form[message]='+ message +'&form[_token]='+ token;
  $.ajax({
  type: "POST",
  url: "/post/save",
  data: dataString,
  }).done(function( msg ) {
    alert(msg);
    return false;
  });
}
