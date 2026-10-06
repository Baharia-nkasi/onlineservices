<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'string' => 'The :attribute must be a string.',
    'boolean' => 'The :attribute field must be true or false.',
    'numeric' => 'The :attribute must be a number.',
    'in' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'mimes' => 'The :attribute must be a file of type: :values.',
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'numeric' => 'The :attribute may not be greater than :max.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
];