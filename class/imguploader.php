<?php

class ImgUploader {
	use trait_file_uploader;

	private $type = 'image';
	private $allowed_extensions = ['jpg', 'jpeg', 'png', 'bpm', 'gif'];
}