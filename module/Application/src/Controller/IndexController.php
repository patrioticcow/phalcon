<?php

namespace Application\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

	public function indexAction()
	{

		$arr = [
			'1100100011100101100011001110010011100000110110',
			'11000010011010000111001011000010110001001100110',
			'1110000011000001100101001110010110010101100100',
			'11001100011001001100110011001100110010100111000',
			'11001010110011001100010001101110110010100110000',
			'11010000110000',
		];

		$xxxxx = '
		11011100110111101110110011001010110111001100001
		1101110 1101111 1110110 1100101 1101110 1100001
		novena

	    numb
		';

		$alphas = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9),
			['.', '>', '?', '/', ';', '"', ':', "'", '[', ']', '{', '}', '~', '`', '!', '@', '$', '%', '&', '*', '(', ')', '_', '+', '-', '=']);
		$vvv    = [];
		$ccc    = [];
		$ddd    = '';
		foreach($alphas as $char) {
			$v     = unpack('H*', $char);
			$vv    = base_convert($v[1], 16, 2);
			$vvv[$char] = $vv;

			foreach($arr as $key => $val){
				if(strpos($val, $vv) !== FALSE) {
					$ccc[] = $char;
				}
			}

			$ddd = implode('', $ccc);
		}

		$request = new \Phalcon\Http\Request();
		if($request->isPost() == TRUE) {
			$text       = $request->getPost('text'); // get the original text
			$textLength = strlen(md5($text));
			$split      = [md5($text)];
			if($textLength > 6) {
				$split = str_split(md5($text), 6);
			}

			$array           = [];
			$binary_combined = '';
			foreach($split as $key => $val) {
				$baseEncode      = $val; // encode string
				$value           = unpack('H*', $baseEncode);
				$baseConvert     = base_convert($value[1], 16, 2); // encoded string to binary
				$baseConvertBack = pack('H*', base_convert($baseConvert, 2, 16));
				$baseDecode      = $baseConvertBack;

				$array[$key]['text_chunks']                = $val;
				$array[$key]['base64_encoded']             = $baseEncode;
				$array[$key]['base64_converted_to_binary'] = $baseConvert;

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
			$output .= $baseConvertBack;
		}

		$this->view->setVar('php_init_max', PHP_INT_MAX);
		$this->view->setVar('text', $text);
		$this->view->setVar('array', $array);
		$this->view->setVar('binary_combined', $binary_combined);
		$this->view->setVar('output', $output);
		$this->view->setVar('vv', $vvv);
		$this->view->setVar('ddd', $ddd);
		$this->view->setVar('ccc', $ccc);
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
