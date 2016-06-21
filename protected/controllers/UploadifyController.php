<?php

class UploadifyController extends Controller {

	public $defaultAction = "view";

	public function accessRules() {
		$accessRule = array(
			array('allow',
				'actions' => array('uploadify', 'view', 'checkExist'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
		return array_merge($accessRule,parent::accessRules());
	}

	public function actionView() {
		$this->render('index');
	}

	public function actionUploadify() {
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$targetFolder = YII::app()->basePath.'/uploads/'; // Relative to the root
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $targetFolder;
			$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

			//Validate the file type
			$fileTypes = array('txt','csv',); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);

			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				echo '<pre>';var_dump($targetFile);die('-=-');
				echo '上传成功';
			} else {
				echo 'Invalid file type.';
			}
	 	} else {
	 		echo '请上传文件';
	 	}
	}

	/**
	 * 检查上传文件是不是已经存在
	 * @return 1:已存在; 0:不存在
	 */
	public function actionCheckExist() {
		$targetFolder = YII::app()->basePath.'/uploads/';
		if (file_exists($targetFolder . '/' . $_POST['filename'])) {
			echo 1;
		} else {
			echo 0;
		}
	}
}
