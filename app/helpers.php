<?php

use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function translatableVariables(array $variables, $request)
{
    $rules = [];

    // Transform all variables into the translatable fields array( name  => [] )
    for ($i = 0; $i < count($variables); $i++) {
        $translation[$variables[$i]] = [];
    }

    // Looping through all translatable fields array
    foreach ($translation as $translatable_variable_name => $value) {
        // Loop through all locales
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $localeCode) {
            // Creating a string variable with its locale code.
            $translatable_variable_name_with_locale = $translatable_variable_name . "_" . $localeCode;

            // Adding rules for the translatable variable
            $rules[$translatable_variable_name_with_locale] = 'required';

            // Get translation values from the request.
            $translation[$translatable_variable_name][$localeCode] = $request->get($translatable_variable_name_with_locale);
        }
    }

    return [
        'rules' => $rules,
        'translations' => $translation
    ];
}

function deleteFile($files_storage_path, $fileName)
{
    if (Storage::exists('/' . $files_storage_path . '/' . $fileName)) {
        return Storage::delete($files_storage_path . '/' . $fileName);
    } else {
        return response()->json(['success' => false, 'error' => 'file not found']);
    }
}
function returnData(array $errors, int $code, $data = null, array $message = []): array
{
    return [
        'errors' => $errors,
        'code' => $code,
        'data' => $data,
        'messages' => $message,
    ];
}

function convertToClassName($string)
{
    $formattedString = str_replace('_', ' ', $string);
    $formattedString = ucwords($formattedString);
    $formattedString = str_replace(' ', '', $formattedString);

    return 'App\\Models\\' . $formattedString;
}


function generateRelationshipPath($model, $filters)
{
    $relations = $model::Relations;
    $path = '';
    $loop = true;
    foreach (array_reverse($relations)  as $relation) {
        if (in_array($relation, array_keys($filters)) && $loop) {
            $path = $relation;
            $loop = false;
            continue;
        }
        $path = $relation . '.' . $path;
    }
    return ($path);
}

function findBySlug($model_name, $slug)
{
    $model_name = convertToClassName($model_name);
    $model_instance = $model_name::whereSlug($slug)->firstOrFail();
    return $model_instance;
}
function covertArrToObj($arr)
{
    $object = new stdClass();
    foreach ($arr as $key => $value) {
        $object->$key = $value;
    }
    return $object;
}


