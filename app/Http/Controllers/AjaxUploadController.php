<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers;

use App\Http\Models\Hr\Device;
use App\Http\Models\Hr\HrDocument;
use App\Http\Models\Hr\HrMail;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Upload;
use App\Library\PHPThumb\ThumbImg;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Config;

class AjaxUploadController extends BaseAdminController{

	function upload(){
		$action = addslashes(Request::get('act', ''));
		switch( $action ){
			case 'upload_image' :
				$this->upload_image();
				break;
			case 'remove_image' :
				$this->remove_image();
				break;
			case 'get_image_insert_content' :
				$this->get_image_insert_content();
				break;
			case 'upload_ext' :
				$this->upload_ext();
				break;
			default:
				$this->nothing();
				break;
		}
	}
	//Default
	function nothing(){
		die("Nothing to do...");
	}
	//Upload
	function upload_image() {
		$id_hiden =  FunctionLib::outputId(Request::get('id', 0));

		$type = Request::get('type', 1);
		$dataImg = $_FILES["multipleFile"];
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";


		switch( $type ){
			case 1 ://Img device
				$aryData = $this->uploadImageToFolderOnce($dataImg, $id_hiden, Define::FOLDER_DEVICE, $type);
				break;
			default:
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function uploadImageToFolderOnce($dataImg, $id_hiden, $folder, $type){
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload Img!";
		$item_id = 0;

		if (!empty($dataImg)) {
			if($id_hiden == 0){

				switch($type){
					case 1://Img Banner
						$new_row['device_status'] = Define::IMAGE_ERROR;
						$item_id = Device::createItem($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}
			if($item_id > 0){
				$aryError = $tmpImg = array();

				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif',
					$_max_file_size = 10*1024*1024,
					$_folder = $folder,
					$type_json=0);

				if($file_name != '' && empty($aryError)) {
					$tmpImg['name_img'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case 1://Img device
							$result = Device::getItemById($item_id);
							if($result != null){
								$path_image = ($result->device_image != '') ? $result->device_image : '';
								if($path_image != ''){
									$folder_image = 'uploads/'.$folder;
									$this->unlinkFileAndFolder($path_image, $folder_image, 0, 0);
									$folder_thumb = 'uploads/thumbs/'.$folder;
									$this->unlinkFileAndFolder($path_image, $folder_thumb, 0, 0);
								}

								$path_image = $file_name;
								$new_row['device_image'] = $path_image;
                                Device::updateItem($item_id, $new_row);
								$arrSize = Define::$arrSizeImage;
								if(isset($arrSize[Define::sizeImage_300])){
                                    $size = $arrSize[Define::sizeImage_300];
									if(!empty($size)){
										$x = (int)$size['w'];
										$y = (int)$size['h'];
									}else{
										$x = $y = Define::sizeImage_300;
									}
								}
								$url_thumb = ThumbImg::thumbBaseNormal(Define::FOLDER_DEVICE, $file_name, $x, $y, '', true, true);
								$tmpImg['src'] = $url_thumb;
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = FunctionLib::inputId($item_id);
					$aryData['info'] = $tmpImg;
				}
			}

		}
		return $aryData;
	}
	function uploadImageToFolder($dataImg, $id_hiden, $folder, $type){

		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload Img!";
		$item_id = 0;

		if (!empty($dataImg)) {

			if($id_hiden == 0){
				switch($type){
					case 2://Img News
						$new_row['news_created'] = time();
						$new_row['news_status'] = CGlobal::IMAGE_ERROR;
						$item_id = News::addData($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}

			if($item_id > 0){
				$aryError = $tmpImg = array();

				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif',
					$_max_file_size = 10*1024*1024,
					$_folder = $folder.'/'.$item_id,
					$type_json=0);

				if ($file_name != '' && empty($aryError)){

					$tmpImg['name_img'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case 2://Img News
							$result = News::getById($item_id);
							if($result != null){
								$aryTempImages = ($result->news_image_other != '') ? unserialize($result->news_image_other) : array();

								$aryTempImages[] = $file_name;

								$new_row['news_image_other'] = serialize($aryTempImages);
								News::updateData($item_id, $new_row);

								$path_image = $file_name;

								$arrSize = CGlobal::$arrSizeImg;
								if(isset($arrSize['4'])){
									$size = explode('x', $arrSize['4']);
									if(!empty($size)){
										$x = (int)$size[0];
										$y = (int)$size[1];
									}else{
										$x = $y = 400;
									}
								}
								$url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_NEWS, $item_id, $file_name, $x, $y, '', true, true);
								$tmpImg['src'] = $url_thumb;
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = $item_id;
					$aryData['info'] = $tmpImg;
				}
			}
		}
		return $aryData;
	}
	//Delte Img
	function remove_image(){
		$id =  (int)FunctionLib::outputId(Request::get('id', 0));
		$nameImage = Request::get('nameImage', '');
		$type = (int)Request::get('type', 1);

		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Remove Img!";
		$aryData['nameImage'] = $nameImage;

		switch( $type ){
			case 1://Img device
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case 9://File mail
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case 10://File document
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			default:
				$folder_image = '';
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function delete_image_item($id, $nameImage, $type){
		$delete_action = 0;
		$aryImages  = array();
		$folder_image =  $folder_thumb = '';
		//get img in DB and remove it
		switch( $type ){
			case 1://Img device
				$result = Device::getItemById($id);
				if($result != null){
					$aryImages = array($result->device_image);
				}
				$folder_image = 'uploads/'.Define::FOLDER_DEVICE;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_DEVICE;
				break;
			case 9://File mail
				$result = HrMail::getItemById($id);
				if($result != null){
					$aryImages = unserialize($result->hr_mail_files);
				}
				$folder_image = 'uploads/'.Define::FOLDER_MAIL;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_MAIL;
				break;
			case 10://File document
				$result = HrDocument::getItemById($id);
				if($result != null){
					$aryImages = unserialize($result->hr_document_files);
				}
				$folder_image = 'uploads/'.Define::FOLDER_DOCUMENT;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_DOCUMENT;
				break;
			default:
				$folder_image = '';
				$folder_thumb = '';
				break;
		}

		if(is_array($aryImages) && count($aryImages) > 0) {
			foreach ($aryImages as $k => $v) {
				if($v === $nameImage){
					if($type == 1){//Img device
						$this->unlinkFileAndFolder($nameImage, $folder_image, true, 0);
						$this->unlinkFileAndFolder($nameImage, $folder_thumb, true, 0);
					}else{
						$this->unlinkFileAndFolder($nameImage, $folder_image, true, $id);
						$this->unlinkFileAndFolder($nameImage, $folder_thumb, true, $id);
					}

					unset($aryImages[$k]);
					if(!empty($aryImages)){
						$aryImages = array_values($aryImages);
						$aryImages = serialize($aryImages);
					}else{
						$aryImages = '';
					}

					switch( $type ){
						case 1://Img device
							$new_row['device_image'] = $aryImages;
							Device::updateItem($id, $new_row);
							break;
						case 9://File mail
							$new_row['hr_mail_files'] = $aryImages;
							HrMail::updateItem($id, $new_row);
							break;
						case 10://File document
							$new_row['hr_document_files'] = $aryImages;
							HrDocument::updateItem($id, $new_row);
							break;
						default:
							$folder_image = '';
							break;
					}

					$delete_action = 1;
					break;
				}
			}
		}

		//xoa khi chua update vao db, anh moi up load
		if($delete_action == 0){
			$this->unlinkFileAndFolder($nameImage, $folder_image, true, $id);
			$delete_action = 1;
		}
		return $delete_action;
	}
	function unlinkFileAndFolder($file_name = '', $folder = '', $is_delDir = 0, $id = 0){

		if($file_name != '') {
			//Remove Img
			$paths = '';
			if($folder != ''){
                if($id >0){
                    $path = Config::get('config.DIR_ROOT').'/'.$folder.'/'.$id;
                }else{
                    $path = Config::get('config.DIR_ROOT').'/'.$folder;
                }
			}

			if($file_name != ''){
				if($path != ''){
					if(is_file($path.'/'.$file_name)){
						@unlink($path.'/'.$file_name);
					}
				}
			}
			//Remove Folder Empty
			if($is_delDir) {
				if($path != ''){
					if(is_dir($path)) {
						@rmdir($path);
					}
				}
			}
			//Remove Img thumb
			$arrSize = Define::$arrSizeImage;
			foreach($arrSize as $k=>$size){
				if(!empty($size)){
					$x = (int)$size['w'];
					$y = (int)$size['h'];
				}else{
					$x = $y = Define::sizeImage_300;
				}

				$paths = '';
				if($folder != ''){
					if($id >0){
						$path = Config::get('config.DIR_ROOT').$folder.'/'.$x.'x'.$y.'/'.$id;
					}else{
						$path = Config::get('config.DIR_ROOT').$folder.'/'.$x.'x'.$y;
					}
				}
				if($file_name != ''){
					if($path != ''){
						if(is_file($path.'/'.$file_name)){
							@unlink($path.'/'.$file_name);
						}
					}
				}
				if($is_delDir) {
					if($path != ''){
						if(is_dir($path)) {
							@rmdir($path);
						}
					}
				}
			}
		}
	}

	//Get Img Content
	function get_image_insert_content(){

		$id_hiden = (int)Request::get('id_hiden', 0);
		$type = (int)Request::get('type', 1);
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";

		if($id_hiden > 0){
			switch( $type ){
				case 2://Img News
					$aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_NEWS, $type);
					break;
				default:
					break;
			}
		}
		echo json_encode($aryData);
		exit();
	}
	function getImgContent($id_hiden, $folder, $type){

		$aryImages = array();
		$aryData = array();

		switch( $type ){
			case 2://Img device
				$result = News::getById($id_hiden);
				if($result != null){
					$aryImages = ($result->news_image_other != '') ? unserialize($result->news_image_other) : array();
				}
				break;
			default:
				break;
		}

		if(is_array($aryImages) && !empty($aryImages)){
			foreach($aryImages as $k => $item){
				$aryData['item'][$k]['large'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 800, 800, '', true, true);
				$aryData['item'][$k]['small'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 400, 400, '', true, true);
			}
		}

		$aryData['intIsOK'] = 1;
		$aryData['msg'] = "Data exists!";
		return $aryData;
	}

	//Upload document
	function upload_ext() {
		$id_hiden =  Request::get('id', 0);
		$id_hiden = FunctionLib::outputId($id_hiden);
		$type = Request::get('type', 1);
		$dataFile = $_FILES["multipleFile"];
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";

		switch( $type ){
			case 9 ://File Mail
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_MAIL, $type);
				break;
			case 10 ://File Document
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_DOCUMENT, $type);
				break;
			default:
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function uploadDocumentToFolder($dataImg, $id_hiden, $folder, $type){
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload file!";
		$item_id = 0;
		if (!empty($dataImg)) {
			if($id_hiden == 0){

				switch($type){
					case 9://File document
						$new_row['hr_mail_created'] = time();
						$new_row['hr_mail_status'] = Define::IMAGE_ERROR;
						$item_id = HrMail::createItem($new_row);
						break;
					case 10://File document
						$new_row['hr_document_created'] = time();
						$new_row['hr_document_status'] = Define::IMAGE_ERROR;
						$item_id = HrDocument::createItem($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}
			if($item_id > 0){
				$aryError = $tmpImg = array();
				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif,txt,ppt,pptx,xls,xlsx,doc,docx,pdf,rar,zip,tar,mp4,flv,avi,3gp,mov',
					$_max_file_size = 50*1024*1024,
					$_folder = $folder.'/'.$item_id,
					$type_json=0);
				if($file_name != '' && empty($aryError)) {
					$tmpImg['name_file'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case 9://File Mail
							$result = HrMail::getItemById($item_id);
							if($result != null){
								$arr_file = ($result->hr_mail_files != '') ? unserialize($result->hr_mail_files) : array();
								$arr_file[] = $file_name;
								$new_row['hr_mail_files'] = serialize($arr_file);
								HrMail::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;
						case 10://File Document
							$result = HrDocument::getItemById($item_id);
							if($result != null){
								$arr_file = ($result->hr_document_files != '') ? unserialize($result->hr_document_files) : array();
								$arr_file[] = $file_name;
								$new_row['hr_document_files'] = serialize($arr_file);
								HrDocument::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = FunctionLib::inputId($item_id);
					$aryData['info'] = $tmpImg;
				}
			}
		}
		return $aryData;
	}
}