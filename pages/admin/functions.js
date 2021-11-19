$(document).ready(function(){

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
                    $("#music_path").attr("value", response); 
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