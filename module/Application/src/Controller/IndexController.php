<?php

namespace Application\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

	public function indexAction()
	{


	}

	public function testAction()
	{
		$request = new \Phalcon\Http\Request();
		if($request->isPost() == TRUE) {
			$text       = $request->getPost('text'); // get the original text
			$textLength = strlen($text);
			$split      = [$text];
			if($textLength > 6) {
				$split = str_split($text, 6);
			}

			$array           = [];
			$binary_combined = '';
			foreach($split as $key => $val) {
				$baseEncode      = base64_encode($val); // encode string
				$value           = unpack('H*', $baseEncode);
				$baseConvert     = base_convert($value[1], 16, 2); // encoded string to binary
				$baseConvertBack = pack('H*', base_convert($baseConvert, 2, 16));
				$baseDecode      = base64_decode($baseConvertBack);

				$array[$key]['text_chunks']                = $val;
				$array[$key]['base64_encoded']             = $baseEncode;
				$array[$key]['base64_converted_to_binary'] = $baseConvert;
				$array[$key]['convert_back_to_base64']     = $baseConvertBack;
				$array[$key]['base64_decoded_to_string']   = $baseDecode;

				$binary_combined .= $baseConvert;
			}

		}

		// split the string to convert
		$textLength = strlen($binary_combined);
		$new_split  = [$binary_combined];

		if($textLength > 63) {
			$new_split = str_split($binary_combined, 63);
		}

		$output = '';
		foreach($new_split as $val) {
			$baseConvertBack = pack('H*', base_convert($val, 2, 16));

			$output .= base64_decode($baseConvertBack);
		}

		$this->view->setVar('php_init_max', PHP_INT_MAX);
		$this->view->setVar('array', $array);
		$this->view->setVar('binary_combined', $binary_combined);
		$this->view->setVar('output', $output);
	}
}
