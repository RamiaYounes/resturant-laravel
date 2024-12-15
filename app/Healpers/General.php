<?php
function upload_images($folder,$image){
   $name=$image->getClientOriginalName();
   $imagename =  time().'-'  . $name;
   $imagePath = "images/".$folder;
   $image->move(public_path($imagePath), $imagename);
   $path='images/'.$folder.'/'.$imagename;
   return $path;
}