<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>phpCRUD</title>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="bootstrap.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body style="background-image:url('crud.jpg')">
 <br>
<br>
<div class="container">
    <h2 align="center">
    Create, Read, Update & Delete
    </h2>
    <br> <br>
    <div align="right">
     <button type="button" name="create_folder" id="create_folder" class="btn btn-success" >Create</button>
    </div>
    <div id="folder_table" class="table-responsive">

    </div>
</div>   
</body>
</html>
<div id="folderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
               <div align="left">
                <h4 class="modal-title" id="change_title">Create Folder</h4>
                </div>
            </div>
            <div class="modal-body">
                <p>
                    Enter Folder Name
                    <input type="text" name="folder_name" id="folder_name" class="form-control">
                </p>
                <br>
                <input type="hidden" id="action" name="action">
                <input type="hidden" id="old_name" name="old_name">
                <input type="button" value="Create" name="folder_button" id="folder_button" class="btn btn-info">
            </div>
            <div class="modal-footer">
            <button type="button" data-dismiss="modal"  class="btn btn-default" >Close</button>
            </div>
        </div>
    </div>
</div>

<!-- # upload file modal -->

<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
               <div align="left">
                <h4 class="modal-title" id="change_title">Upload File</h4>
                </div>
            </div>
            <div class="modal-body">
               <form method="post" id="upload_form" action="" enctype='multipart/form-data'>
               <p>Select Image
               <input type="file" name="upload_file">
               </p>
               <br>
               <input type="hidden" name="hidden_folder_name" id="hidden_folder_name">
               <input type="submit" name="upload_button" class="btn btn-info" value="Upload">
               </form>
            </div>
            <div class="modal-footer">
            <button type="button" data-dismiss="modal"  class="btn btn-default" >Close</button>
            </div>
        </div>
    </div>
</div>

<!-- list files from folder -->

<div id="filelistModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
               <div align="left">
                <h4 class="modal-title" id="change_title">File List</h4>
                </div>
            </div>
            <div class="modal-body" id="file_list">
               
            </div>
            <div class="modal-footer">
            <button type="button" data-dismiss="modal"  class="btn btn-default" >Close</button>
            </div>
        </div>
    </div>
</div>


<script>
  $(document).ready(function () {
      load_folder_list();
      function load_folder_list() {
          var action = "fetch";
          $.ajax({
              url : "action.php",
              method:"POST",
              data: {action:action},
              success : function(data)
              {
               $('#folder_table').html(data);
              } 
          });
      }
      $(document).on('click','#create_folder',function () {
         $('#action').val('create');
         $('#folder_name').val('');
         $('#folder_button').val('Create');
         $('#old_name').val('');
         $('#change_title').text('Create Folder');
         $('#folderModal').modal('show');
      });
      $(document).on('click','#folder_button',function(){
        var folder_name = $('#folder_name').val();
        var action = $('#action').val();
        var old_name= $('#old_name').val(); //for changing the name
        if (folder_name !='') {
            $.ajax({
                url : "action.php",
                method: "POST",
                data:{folder_name:folder_name,old_name:old_name,action:action},
                success: function(data){
                  $('#folderModal').modal('hide');
                  load_folder_list();
                  alert(data);
                }
            })
        } else {
            alert("Enter Folder Name,please");
        }
            
      });
      //Rename Folder name
       $(document).on('click','.update',function () {
            var folder_name = $(this).data("name");
           $('#old_name').val(folder_name);
           $('#folder_name').val(folder_name);
           $('#action').val("change");
           $('#folder_button').val('Update');
           $('#change_title').text("Change Folder Name");
           $('#folderModal').modal("show");

       });
       // Upload file jquery
       $(document).on('click','.upload',function () {
            var folder_name = $(this).data("name");
           $('#hidden_folder_name').val(folder_name);
        //    $('#folder_name').val(folder_name);
        //    $('#action').val("change");
        //    $('#folder_button').val('Update');
        //    $('#change_title').text("Change Folder Name");
           $('#uploadModal').modal("show");

       });

       $('#upload_form').on('submit',function () {
           $.ajax({
               url: "upload.php",
               method:"POST",
               data: new FormData(this),
               contentType : false,
               cache: false,
               processData: false,
               success: function (data) {
                   load_folder_list();
                   alert(data);
               }
           })
       });
//ajax jquery list file
       $(document).on('click','.view_files',function(){
          var folder_name=$(this).data("name");
          var action = "fetch_files";
          $.ajax({
              url: "action.php",
              method:"POST",
              data:{action:action,folder_name:folder_name},
              success: function(data){
                  $('#file_list').html(data);
                  $('#filelistModal').modal('show');
              }

          });
        
       });
     //jquery remove file
     $(document).on('click','.remove_file',function(){
      var path = $(this).attr("id");
      var action = "remove_file";
      if(confirm("Are you sure?")){
       $.ajax({
           url:"action.php",
           method : "POST",
           data:{path:path,action:action},
           success: function (data) {
               alert(data);
               $('#filelistModal').modal('hide');
               load_folder_list();
           }
       })
      }
      else{
          return false;
      }
     });
     $(document).on('click','.delete',function(){
      var folder_name = $(this).data("name");
      var action = "delete";
      if (confirm("ARE YOU SURE?")) {
         $.ajax({
             url: "action.php",
             method: "POST",
             data: {folder_name:folder_name,action:action},
             success: function(data){
                 load_folder_list();
                 alert(data);
             }
         }) 
      }
     });
     //change file name
      $(document).on('blur','.change_file_name',function(){

          var folder_name= $(this).data("folder_name");
          var old_file_name= $(this).data("file_name");
          var new_file_name = $(this).text();
          var action = "change_file_name";
          $.ajax({
            url: "action.php",
            method : "POST",
            data:{folder_name:folder_name,old_file_name:old_file_name,new_file_name:new_file_name,action:action},
            success: function (data) {
                alert(data);
            }

          });
      });

  });  
</script>
