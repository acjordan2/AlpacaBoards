<?php
/*
 * Upload.class.php
 * 
 * Copyright (c) 2012 Andrew Jordan
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the 
 * "Software"), to deal in the Software without restriction, including 
 * without limitation the rights to use, copy, modify, merge, publish, 
 * distribute, sublicense, and/or sell copies of the Software, and to 
 * permit persons to whom the Software is furnished to do so, subject to 
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
 
 class Upload{
	 
	private $allowed_images = array("gif", "jpeg", "jpg", "png");
	
	private $allowed_image_types = array("gif", "jpeg", "jpg", "pjpeg", "x-png", "png");
	
	private $size_limit = 1000000;
	
	private $pdo_conn;
	
	private $user_id;
	
	public function __construct($db, $aUserID){
		$this->pdo_conn = $db;
		$this->user_id = $aUserID;
	}
	
	public function uploadImage($file){
		$file_name = $file["name"];
		$file_type = $file["type"];
		
		$extension = end(explode(".", $file_name));
		$file_type = explode("/", $file_type);
		if($file_type[0] == "image" && in_array($file_type[1], $this->allowed_image_types) && in_array($extension, $this->allowed_images)){
			if($file["error"] > 0)
				return FALSE;
			else{
				$sha1_sum = sha1_file($file["tmp_name"]);
				$base_dir = "./usercontent/image_uploads/";
				$thumb_dir = "/t";
				$image_dir = "/n";
				$target_dir = "/".substr($sha1_sum,0,2);
				$target_file = "/".$sha1_sum.".".$extension;
				if(!file_exists($base_dir.$image_dir.$target_dir.$target_file)){
					if(!file_exists($base_dir.$image_dir.$target_dir)){
						mkdir($base_dir.$image_dir.$target_dir);
						mkdir($base_dir.$thumb_dir.$target_dir);
					}
					move_uploaded_file($file["tmp_name"], $base_dir.$image_dir.$target_dir.$target_file);
                    print $target_file;
                    $size = getimagesize($base_dir.$image_dir.$target_dir.$target_file);
					$thumb = new PHPThumb\GD($base_dir.$image_dir.$target_dir.$target_file, array('resizeUp' => false));
					$thumb->resize(150, 150);
                    #$thumb->pad(150, 150, [255, 255, 255, 127]);
					$thumb->save($base_dir.$thumb_dir.$target_dir."/".$sha1_sum.".".$extension);
					$thumbsize = getimagesize($base_dir.$thumb_dir.$target_dir."/".$sha1_sum.".".$extension);
					$sql = "INSERT INTO UploadedImages(user_id, sha1_sum, width, height, thumb_width, thumb_height, created)
								VALUES(".$this->user_id.", \"$sha1_sum\", ?, ?, ?, ?, ".time().")";
					$statement = $this->pdo_conn->prepare($sql);
					$statement->execute(array($size['0'], $size['1'], $thumbsize['0'], $thumbsize['1']));
				}
				$sql_getImageID = "SELECT image_id FROM UploadedImages WHERE sha1_sum='$sha1_sum'";
				$statement_getImageID = $this->pdo_conn->query($sql_getImageID);
				$result = $statement_getImageID->fetch();
				$sql_dupeUpload = "INSERT INTO UploadLog (user_id, image_id, filename, created)
											VALUES(".$this->user_id.", ".$result['image_id'].", ?, ".time().")";
				$statement_dupeUpload = $this->pdo_conn->prepare($sql_dupeUpload);
				$statement_dupeUpload->execute(array(substr($file_name,0,-1*(strlen($extension))).$extension));
				$result = array("uploadlog_id" => $this->pdo_conn->lastInsertId(),
								"sha1_sum" => $sha1_sum,
								"filename" => substr($file_name,0,-1*(strlen($extension))).$extension);
				return $result;
			}
		}
	}
	
 }
 
 ?>
