<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */






    'accepted' => translate('The :attribute field must be accepted.', 'validation'),
    'accepted_if' => translate('The :attribute field must be accepted when :other is :value.', 'validation'),
    'active_url' => translate('The :attribute field must be a valid URL.', 'validation'),
    'after' => translate('The :attribute field must be a date after :date.', 'validation'),
    'after_or_equal' => translate('The :attribute field must be a date after or equal to :date.', 'validation'),
    'alpha' => translate('The :attribute field must only contain letters.', 'validation'),
    'alpha_dash' => translate('The :attribute field must only contain letters, numbers, dashes, and underscores.', 'validation'),
    'alpha_num' => translate('The :attribute field must only contain letters and numbers.', 'validation'),
    'array' => translate('The :attribute field must be an array.', 'validation'),
    'ascii' => translate('The :attribute field must only contain single-byte alphanumeric characters and symbols.', 'validation'),
    'before' => translate('The :attribute field must be a date before :date.', 'validation'),
    'before_or_equal' => translate('The :attribute field must be a date before or equal to :date.', 'validation'),
    'between' => [
        'array' => translate('The :attribute field must have between :min and :max items.', 'validation'),
        'file' => translate('The :attribute field must be between :min and :max kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be between :min and :max.', 'validation'),
        'string' => translate('The :attribute field must be between :min and :max characters.', 'validation'),
    ],
    'boolean' => translate('The :attribute field must be true or false.', 'validation'),
    'can' => translate('The :attribute field contains an unauthorized value.', 'validation'),
    'confirmed' => translate('The :attribute field confirmation does not match.', 'validation'),
    'contains' => translate('The :attribute field is missing a required value.', 'validation'),
    'current_password' => translate('The password is incorrect.', 'validation'),
    'date' => translate('The :attribute field must be a valid date.', 'validation'),
    'date_equals' => translate('The :attribute field must be a date equal to :date.', 'validation'),
    'date_format' => translate('The :attribute field must match the format :format.', 'validation'),
    'decimal' => translate('The :attribute field must have :decimal decimal places.', 'validation'),
    'declined' => translate('The :attribute field must be declined.', 'validation'),
    'declined_if' => translate('The :attribute field must be declined when :other is :value.', 'validation'),
    'different' => translate('The :attribute field and :other must be different.', 'validation'),
    'digits' => translate('The :attribute field must be :digits digits.', 'validation'),
    'digits_between' => translate('The :attribute field must be between :min and :max digits.', 'validation'),
    'dimensions' => translate('The :attribute field has invalid image dimensions.', 'validation'),
    'distinct' => translate('The :attribute field has a duplicate value.', 'validation'),
    'doesnt_end_with' => translate('The :attribute field must not end with one of the following: :values.', 'validation'),
    'doesnt_start_with' => translate('The :attribute field must not start with one of the following: :values.', 'validation'),
    'email' => translate('The :attribute field must be a valid email address.', 'validation'),
    'ends_with' => translate('The :attribute field must end with one of the following: :values.', 'validation'),
    'enum' => translate('The selected :attribute is invalid.', 'validation'),
    'exists' => translate('The selected :attribute is invalid.', 'validation'),
    'extensions' => translate('The :attribute field must have one of the following extensions: :values.', 'validation'),
    'file' => translate('The :attribute field must be a file.', 'validation'),
    'filled' => translate('The :attribute field must have a value.', 'validation'),
    'gt' => [
        'array' => translate('The :attribute field must have more than :value items.', 'validation'),
        'file' => translate('The :attribute field must be greater than :value kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be greater than :value.', 'validation'),
        'string' => translate('The :attribute field must be greater than :value characters.', 'validation'),
    ],
    'gte' => [
        'array' => translate('The :attribute field must have :value items or more.', 'validation'),
        'file' => translate('The :attribute field must be greater than or equal to :value kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be greater than or equal to :value.', 'validation'),
        'string' => translate('The :attribute field must be greater than or equal to :value characters.', 'validation'),
    ],
    'hex_color' => translate('The :attribute field must be a valid hexadecimal color.', 'validation'),
    'image' => translate('The :attribute field must be an image.', 'validation'),
    'in' => translate('The selected :attribute is invalid.', 'validation'),
    'in_array' => translate('The :attribute field must exist in :other.', 'validation'),
    'integer' => translate('The :attribute field must be an integer.', 'validation'),
    'ip' => translate('The :attribute field must be a valid IP address.', 'validation'),
    'ipv4' => translate('The :attribute field must be a valid IPv4 address.', 'validation'),
    'ipv6' => translate('The :attribute field must be a valid IPv6 address.', 'validation'),
    'json' => translate('The :attribute field must be a valid JSON string.', 'validation'),
    'list' => translate('The :attribute field must be a list.', 'validation'),
    'lowercase' => translate('The :attribute field must be lowercase.', 'validation'),
    'lt' => [
        'array' => translate('The :attribute field must have less than :value items.', 'validation'),
        'file' => translate('The :attribute field must be less than :value kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be less than :value.', 'validation'),
        'string' => translate('The :attribute field must be less than :value characters.', 'validation'),
    ],
    'lte' => [
        'array' => translate('The :attribute field must not have more than :value items.', 'validation'),
        'file' => translate('The :attribute field must be less than or equal to :value kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be less than or equal to :value.', 'validation'),
        'string' => translate('The :attribute field must be less than or equal to :value characters.', 'validation'),
    ],
    'mac_address' => translate('The :attribute field must be a valid MAC address.', 'validation'),
    'max' => [
        'array' => translate('The :attribute field must not have more than :max items.', 'validation'),
        'file' => translate('The :attribute field must not be greater than :max kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must not be greater than :max.', 'validation'),
        'string' => translate('The :attribute field must not be greater than :max characters.', 'validation'),
    ],
    'max_digits' => translate('The :attribute field must not have more than :max digits.', 'validation'),
    'mimes' => translate('The :attribute field must be a file of type: :values.', 'validation'),
    'mimetypes' => translate('The :attribute field must be a file of type: :values.', 'validation'),
    'min' => [
        'array' => translate('The :attribute field must have at least :min items.', 'validation'),
        'file' => translate('The :attribute field must be at least :min kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be at least :min.', 'validation'),
        'string' => translate('The :attribute field must be at least :min characters.', 'validation'),
    ],
    'min_digits' => translate('The :attribute field must have at least :min digits.', 'validation'),
    'missing' => translate('The :attribute field must be missing.', 'validation'),
    'missing_if' => translate('The :attribute field must be missing when :other is :value.', 'validation'),
    'missing_unless' => translate('The :attribute field must be missing unless :other is :value.', 'validation'),
    'missing_with' => translate('The :attribute field must be missing when :values is present.', 'validation'),
    'missing_with_all' => translate('The :attribute field must be missing when :values are present.', 'validation'),
    'multiple_of' => translate('The :attribute field must be a multiple of :value.', 'validation'),
    'not_in' => translate('The selected :attribute is invalid.', 'validation'),
    'not_regex' => translate('The :attribute field format is invalid.', 'validation'),
    'numeric' => translate('The :attribute field must be a number.', 'validation'),
    'password' => [
        'letters' => translate('The :attribute field must contain at least one letter.', 'validation'),
        'mixed' => translate('The :attribute field must contain at least one uppercase and one lowercase letter.', 'validation'),
        'numbers' => translate('The :attribute field must contain at least one number.', 'validation'),
        'symbols' => translate('The :attribute field must contain at least one symbol.', 'validation'),
        'uncompromised' => translate('The given :attribute has appeared in a data leak. Please choose a different :attribute.', 'validation'),
    ],
    'present' => translate('The :attribute field must be present.', 'validation'),
    'present_if' => translate('The :attribute field must be present when :other is :value.', 'validation'),
    'present_unless' => translate('The :attribute field must be present unless :other is :value.', 'validation'),
    'present_with' => translate('The :attribute field must be present when :values is present.', 'validation'),
    'present_with_all' => translate('The :attribute field must be present when :values are present.', 'validation'),
    'prohibited' => translate('The :attribute field is prohibited.', 'validation'),
    'prohibited_if' => translate('The :attribute field is prohibited when :other is :value.', 'validation'),
    'prohibited_unless' => translate('The :attribute field is prohibited unless :other is in :values.', 'validation'),
    'prohibits' => translate('The :attribute field prohibits :other from being present.', 'validation'),
    'regex' => translate('The :attribute field format is invalid.', 'validation'),
    'required' => translate('The :attribute field is required.', 'validation'),
    'required_array_keys' => translate('The :attribute field must contain entries for: :values.', 'validation'),
    'required_if' => translate('The :attribute field is required when :other is :value.', 'validation'),
    'required_if_accepted' => translate('The :attribute field is required when :other is accepted.', 'validation'),
    'required_if_declined' => translate('The :attribute field is required when :other is declined.', 'validation'),
    'required_unless' => translate('The :attribute field is required unless :other is in :values.', 'validation'),
    'required_with' => translate('The :attribute field is required when :values is present.', 'validation'),
    'required_with_all' => translate('The :attribute field is required when :values are present.', 'validation'),
    'required_without' => translate('The :attribute field is required when :values is not present.', 'validation'),
    'required_without_all' => translate('The :attribute field is required when none of :values are present.', 'validation'),
    'same' => translate('The :attribute field must match :other.', 'validation'),
    'size' => [
        'array' => translate('The :attribute field must contain :size items.', 'validation'),
        'file' => translate('The :attribute field must be :size kilobytes.', 'validation'),
        'numeric' => translate('The :attribute field must be :size.', 'validation'),
        'string' => translate('The :attribute field must be :size characters.', 'validation'),
    ],
    'starts_with' => translate('The :attribute field must start with one of the following: :values.', 'validation'),
    'string' => translate('The :attribute field must be a string.', 'validation'),
    'timezone' => translate('The :attribute field must be a valid timezone.', 'validation'),
    'unique' => translate('The :attribute has already been taken.', 'validation'),
    'uploaded' => translate('The :attribute failed to upload.', 'validation'),
    'uppercase' => translate('The :attribute field must be uppercase.', 'validation'),
    'url' => translate('The :attribute field must be a valid URL.', 'validation'),
    'ulid' => translate('The :attribute field must be a valid ULID.', 'validation'),
    'uuid' => translate('The :attribute field must be a valid UUID.', 'validation'),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    /*
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    */


    'custom' => [
        'h-captcha-response' => [
            'required' => translate('Please verify that you are not a robot.', 'validation'),
            'hcaptcha' => translate('Captcha error! try again later or contact site admin.', 'validation'),
        ],
        'g-recaptcha-response' => [
            'required' => translate('Please verify that you are not a robot.', 'validation'),
            'captcha' => translate('Captcha error! try again later or contact site admin.', 'validation'),
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'fullname'              => translate('full name', 'validation'),
        'username'              => translate('username', 'validation'),
        'email'                 => translate('email address', 'validation'),
        'firstname'             => translate('first name', 'validation'),
        'lastname'              => translate('last name', 'validation'),
        'password'              => translate('password', 'validation'),
        'password_confirmation' => translate('password confirmation', 'validation'),
        'subject'               => translate('subject', 'validation'),
        'message'               => translate('message', 'validation'),
        'key'                   => translate('key', 'validation'),
        'avatar'                => translate('avatar', 'validation'),
        'current_password'      => translate('current password', 'validation'),
        'domain'                => translate('domain', 'validation'),
        'city'                  => translate('city', 'validation'),
        'country'               => translate('country', 'validation'),
        'address'               => translate('address', 'validation'),
        'phone'                 => translate('phone', 'validation'),
        'mobile'                => translate('mobile', 'validation'),
        'age'                   => translate('age', 'validation'),
        'sex'                   => translate('sex', 'validation'),
        'gender'                => translate('gender', 'validation'),
        'day'                   => translate('day', 'validation'),
        'month'                 => translate('month', 'validation'),
        'year'                  => translate('year', 'validation'),
        'hour'                  => translate('hour', 'validation'),
        'minute'                => translate('minute', 'validation'),
        'second'                => translate('second', 'validation'),
        'title'                 => translate('title', 'validation'),
        'content'               => translate('content', 'validation'),
        'description'           => translate('description', 'validation'),
        'excerpt'               => translate('excerpt', 'validation'),
        'date'                  => translate('date', 'validation'),
        'time'                  => translate('time', 'validation'),
        'available'             => translate('available', 'validation'),
        'size'                  => translate('size', 'validation'),
    ],

];