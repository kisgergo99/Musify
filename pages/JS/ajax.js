$(document).ready(function(){
  $(".clickableMenu").click(function(){
    $.ajax({
      type: "GET",
      url: '/musify/pages/home/menuquery.php?menu='+$(this).attr('id'),
      data: $(this).serialize(),
      success: function(response)
      {
          $("#framepage").html(response);
      }
    });
  });
});
