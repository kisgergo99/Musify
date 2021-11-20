$(document).ready(function(){
   
   $("#uploadMusic").dialog({
      autoOpen : false, modal : true, show : "blind", hide : "blind", autoResize:true, height:'auto', width:'auto', my: "center",
      at: "center",
      of: window
   });

   // next add the onclick handler
   $("#uploadFileButton").click(function() {
      $("#uploadMusic").dialog("open");
      return false;
   });

   $("#numOfMusic").bind('keyup mouseup onchange', function () {
      $( ".trackContent" ).empty();
      var number = parseInt($('#numOfMusic').val());
      if (number){
         for (var i = 1; i < number+1; i++) {
            $('.trackContent').append("<label for='music_status'>Should this music be public?</label><div class='form-check form-switch'><input type='checkbox' class='form-check-input' name='music_status_"+i+"' id='music_status'></div><label for='music_artist_name'>Music composed by: </label><input type='text' name='music_artist_name_"+i+"' id='music_artist_name' value='' class='form-control' required><label for='music_track_name'>Track name: </label><input type='text' name='music_track_name_"+i+"' id='music_track_name' value='' class='form-control' required><label for='music_artwork_path'>Music artwork path: </label><input type='text' name='music_artwork_path_"+i+"' id='music_artwork_path' value='' class='form-control'><label>Music path: </label><input type='text' name='music_path_"+i+"' id='music_path' class='form-control music_path' value='' required><hr>");
         }
      }
      $(".trackContent").append("<input type='hidden' name='count' value='"+number+"'>");
   });

   $(".but_upload").click(function(){
      var fd = new FormData();
      var files = $('.musicfile')[0].files;
      
      // Check file selected or not
      if(files.length > 0 ){
         fd.append('file',files[0]);

         $.ajax({
            url: 'uploadmusic.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
               if(response != 0){
                  $("#uploaded_path").attr("value", response); 
                  alert("Upload completed!");
                  
               }else{
                  alert('file not uploaded');
               }
            },
         });
      }else{
         alert("Please select a file.");
      }
   });

   $("#albumartwork_but_upload").click(function(){

      var fd = new FormData();
      var files = $('#file_albumartwork')[0].files;
      
      // Check file selected or not
      if(files.length > 0 ){
         fd.append('file',files[0]);

         $.ajax({
            url: 'uploadmusic.php',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
               if(response != 0){
                  $("#album_artwork_path").attr("value",response); 
                  alert("Upload completed!");
               }else{
                  alert('file not uploaded');
               }
            },
         });
      }else{
         alert("Please select a file.");
      }
   });
});