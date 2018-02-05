function checkUser(user) {
  var params = "user=" + user.value;

  $.post("checkuser.php", params, function(data) {
    // Display the returned data in browser
    $('#info').html(data);
  });

}
