$(document).ready(function(){
  $(".clickableMenu").click(function(){
    $.ajax({
      type: "GET",
      async: false,
      url: '/musify/pages/home/ajaxhandler.php?menu='+$(this).attr('id'),
      data: $(this).serialize(),
      success: function(response2)
      {
          $("#framepage").html(response2);
      }
    });
  });
});

$(document).on('click','.singleMusicMenu',function() {
  $.ajax({
      type: "GET",
      async: false,
      url: '/musify/pages/home/ajaxhandler.php?musicInfo=2&musicid='+$(this).attr('music-id')+'&musicpath='+$(this).attr('data-value'),
      data: $(this).serialize(),
      success: function(response1)
      {
          var data = JSON.parse(response1);
          $(".music-artwork").attr("src", data.music_artwork_path);
          $(".music-artist-name").text(data.music_artist_name);
          $(".music-track-name").text(data.music_track_name);
          
      }
  });
});

$(document).on('click','.album-item',function() {
  $.ajax({
    type: "GET",
    async: false,
    url: '/musify/pages/home/albums.php?albumPage='+$(this).attr("album-id"),
    data: $(this).serialize(),
    success: function(response3)
    {
      $("#framepage").html(response3);
        
    }
  });
});


$("input[name=search-textbox]").keyup(function(e) {
});

$(document).on('change keyup paste',".search-textbox", function() {
  $.ajax({
    type: "GET",
    async: false,
    url: '/musify/pages/home/ajaxhandler.php?s='+$('input[name=search-textbox]').val(),
    data: $(this).serialize(),
    success: function(response4)
    {
      $(".music-result").html(response4);
        
    }
  });
 });
