<?php

namespace Helpers;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = trim((string) ($data[$field] ?? ''));

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field][] = 'Trường này là bắt buộc.';
                }

                if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Email không hợp lệ.';
                }

                if (str_starts_with($rule, 'min:') && mb_strlen($value) < (int) explode(':', $rule)[1]) {
                    $errors[$field][] = 'Tối thiểu ' . explode(':', $rule)[1] . ' ký tự.';
                }

                if (str_starts_with($rule, 'max:') && mb_strlen($value) > (int) explode(':', $rule)[1]) {
                    $errors[$field][] = 'Tối đa ' . explode(':', $rule)[1] . ' ký tự.';
                }

                if ($rule === 'integer' && $value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $errors[$field][] = 'Giá trị phải là số nguyên.';
                }

                if ($rule === 'numeric' && $value !== '' && !is_numeric($value)) {
                    $errors[$field][] = 'Giá trị phải là số.';
                }
            }
        }

        return $errors;
    }
}
