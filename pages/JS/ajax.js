$(document).ready(function(){
  $(".clickableMenu").click(function(){
    $.ajax({
      type: "GET",
      async: false,
      url: '/musify/pages/home/ajaxhandler.php?menu='+$(this).attr('id'),
      data: $(this).serialize(),
      success: function(response2)
      {
        console.log(response2);
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
          console.log(response1);
          var data = JSON.parse(response1);
          $(".music-artwork").attr("src", data.music_artwork_path);
          $(".music-artist-name").text(data.music_artist_name);
          $(".music-track-name").text(data.music_track_name);
          
      }
  });
});

