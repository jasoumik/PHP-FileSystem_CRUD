<?php
if ($_FILES["upload_file"]["name"]!='')
 {
    # code...
    $data= explode(".",$_FILES["upload_file"]["name"]);
    $extension = $data[1];
    $allowed_extension= array("jpg","png","PNG","gif");
    if (in_array($extension,$allowed_extension)) {
        $new_file_name=rand() . '.' . $extension;
        $path = $_POST["hidden_folder_name"]. '/'.$new_file_name;
        if(move_uploaded_file($_FILES["upload_file"]["tmp_name"],$path)){
         echo'Image Uploaded';
        }
        else {
            echo 'There is some error';
        }

    } else {
        echo 'invalid image file';
    }
    
}
else {
    echo'PLease Select Image';
}