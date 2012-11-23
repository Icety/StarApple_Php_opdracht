/* author_savePost()
 * Gathers information from the form and sends through ajax towards /author/save
 * In: Null
 * Out: dataString: containing all information filled into the form.
 * Extra: If no password is send through with a save, the url will have /false behind it, 
 * stating the password shouldn't be updated, else it says /true
*/

function author_savePost() {
  AuthorID = $('#form_AuthorID').val();
  type = $('#form_type').val();
  firstname = $('#form_firstname').val();
  lastname = $('#form_lastname').val();
  password1 = $('#form_password1').val();
  password2 = $('#form_password2').val();
  street = $('#form_street').val();
  streetnr = $('#form_streetnr').val();
  zipcode = $('#form_zipcode').val();
  city = $('#form_city').val();
  telephone = $('#form_telephone').val();
  email = $('#form_email').val();
  token = $('#form__token').val();
  var url = '/author/save';
  if (!password1) {
    url += '/false';
  }
  else {
    url += '/true';
  }

  var dataString = 'form[AuthorID]='+ AuthorID +'&form[type]='+ type +'&form[firstname]='+ firstname +'&form[lastname]='+ lastname +'&form[password1]='+ password1 +'&form[password2]='+ password2 +'&form[street]='+ street + '&form[streetnr]='+ streetnr +'&form[zipcode]='+ zipcode +'&form[city]='+ city +'&form[telephone]='+ telephone +'&form[email]='+ email +'&form[_token]='+ token;
  $.ajax({
  type: "POST",
  url: url,
  data: dataString,
  }).done(function( msg ) {
    alert(msg);
    return false;
  });
  return false;
}
