<?php

namespace App\Helper;

class FunctionUtils {

    public static $status = array(0 => 'Inactive', 1 => 'Active');

    public static $postStatus = [0 => 'Draft', 1 => 'Published', 2 => 'In-Active'];

	public static function removeNullValue($data) {
		$data = json_decode(json_encode($data), true);

		array_walk_recursive($data, function (&$item, $key) {
			$item = null === $item ? '' : $item;
		});

		return $data;
	}

	public static function getValidationError($validator) {
		$return['status'] = 0;
		$return['errors'] = $validator->errors();

		$messageArray = Array();
		foreach($validator->errors()->toArray() as $key => $val) {
			$messageArray[] = implode(',',$val);
		}

		$return['message'] = implode(',',$messageArray);

		return FunctionUtils::removeNullValue($return);
	}

    public static function createSlug($string) {
        $string = str_replace(' ', '-', trim($string)); // Replaces all spaces with hyphens.
        $string = str_replace('&', 'and', trim($string)); // Replaces '&' with 'and'.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes all special chars except hyphens.
        $string = preg_replace('/-+/', '-', $string); // Remove duplicate hyphens next to each character.
        $string = strtolower($string);
        return $string;
    }
}
?>
