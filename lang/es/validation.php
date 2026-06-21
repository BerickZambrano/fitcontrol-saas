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

	'accepted' => 'El campo :attribute debe ser aceptado.',
	'accepted_if' => 'El campo :attribute debe ser aceptado cuando :other es :value.',
	'active_url' => 'El campo :attribute no es una URL válida.',
	'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
	'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
	'alpha' => 'El campo :attribute solo puede contener letras.',
	'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
	'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
	'array' => 'El campo :attribute debe ser un array.',
	'ascii' => 'El campo :attribute debe contener solo caracteres alfanuméricos de un byte y símbolos.',
	'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
	'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
	'between' => [
		'numeric' => 'El campo :attribute debe ser un valor entre :min y :max.',
		'file' => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
		'string' => 'El campo :attribute debe contener entre :min y :max caracteres.',
		'array' => 'El campo :attribute debe contener entre :min y :max elementos.',
	],
	'boolean' => 'El campo :attribute debe ser verdadero o falso.',
	'can' => 'El campo :attribute contiene un valor no autorizado.',
	'confirmed' => 'El campo confirmación de :attribute no coincide.',
	'current_password' => 'La contraseña es incorrecta.',
	'date' => 'El campo :attribute no corresponde con una fecha válida.',
	'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
	'date_format' => 'El campo :attribute no corresponde con el formato de fecha :format.',
	'decimal' => 'El campo :attribute debe tener :decimal decimales.',
	'declined' => 'El campo :attribute debe ser rechazado.',
	'declined_if' => 'El campo :attribute debe ser rechazado cuando :other es :value.',
	'different' => 'Los campos :attribute y :other deben ser diferentes.',
	'digits' => 'El campo :attribute debe ser un número de :digits dígitos.',
	'digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.',
	'dimensions' => 'El campo :attribute tiene dimensiones de imagen inválidas.',
	'distinct' => 'El campo :attribute tiene un valor duplicado.',
	'doesnt_end_with' => 'El campo :attribute no debe terminar por uno de los siguientes valores: :values.',
	'doesnt_start_with' => 'El campo :attribute no debe empezar por uno de los siguientes valores: :values.',
	'email' => 'El campo :attribute debe ser un email válido.',
	'ends_with' => 'El campo :attribute debe finalizar con alguno de los siguientes valores: :values.',
	'enum' => 'El :attribute seleccionado es inválido.',
	'exists' => 'El :attribute seleccionado no existe.',
	'extensions' => 'El campo :attribute debe tener una de las siguientes extensiones: :values.',
	'file' => 'El campo :attribute debe ser un archivo.',
	'filled' => 'El campo :attribute debe tener un valor.',
	'gt' => [
		'numeric' => 'El campo :attribute debe ser mayor a :value.',
		'file' => 'El archivo :attribute debe pesar más de :value kilobytes.',
		'string' => 'El campo :attribute debe contener más de :value caracteres.',
		'array' => 'El campo :attribute debe contener más de :value elementos.',
	],
	'gte' => [
		'numeric' => 'El campo :attribute debe ser mayor o igual a :value.',
		'file' => 'El archivo :attribute debe pesar :value o más kilobytes.',
		'string' => 'El campo :attribute debe contener :value o más caracteres.',
		'array' => 'El campo :attribute debe contener :value o más elementos.',
	],
	'hex_color' => 'El campo :attribute debe ser un color hexadecimal válido.',
	'image' => 'El campo :attribute debe ser una imagen.',
	'in' => 'El campo :attribute es inválido.',
	'in_array' => 'El campo :attribute no existe en :other.',
	'integer' => 'El campo :attribute debe ser un número entero.',
	'ip' => 'El campo :attribute debe ser una dirección IP válida.',
	'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
	'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
	'json' => 'El campo :attribute debe ser una cadena de texto JSON válida.',
	'lowercase' => 'El campo :attribute debe estar en minúscula.',
	'lt' => [
		'numeric' => 'El campo :attribute debe ser menor a :value.',
		'file' => 'El archivo :attribute debe pesar menos de :value kilobytes.',
		'string' => 'El campo :attribute debe contener menos de :value caracteres.',
		'array' => 'El campo :attribute debe contener menos de :value elementos.',
	],
	'lte'=> [
		'numeric' => 'El campo :attribute debe ser menor o igual a :value.',
		'file' => 'El archivo :attribute debe pesar :value o menos kilobytes.',
		'string' => 'El campo :attribute debe contener :value o menos caracteres.',
		'array' => 'El campo :attribute debe contener :value o menos elementos.',
	],
	'mac_address' => 'El campo :attribute debe ser una dirección MAC válida.',
	'max'=> [
		'numeric' => 'El campo :attribute no debe ser mayor a :max.',
		'file' => 'El archivo :attribute no debe pesar más de :max kilobytes.',
		'string' => 'El campo :attribute no debe contener más de :max caracteres.',
		'array' => 'El campo :attribute no debe contener más de :max elementos.',
	],
	'max_digits' => 'El campo :attribute no debe tener más de :max dígitos.',
	'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
	'min' => [
		'numeric' => 'El campo :attribute debe ser al menos :min.',
		'file' => 'El archivo :attribute debe pesar al menos :min kilobytes.',
		'string' => 'El campo :attribute debe contener al menos :min caracteres.',
		'array' => 'El campo :attribute debe contener al menos :min elementos.',
	],
	'min_digits' => 'El campo :attribute debe tener al menos :min dígitos.',
	'missing' => 'El campo :attribute debe faltar.',
	'missing_if' => 'El campo :attribute debe falta cuando :other es :value.',
	'missing_unless' => 'El campo :attribute debe faltar excepto si :other es :value.',
	'missing_with' => 'El campo :attribute debe faltar cuando :values está presente.',
	'missing_with_all' => 'El campo :attribute debe faltar cuando :values están presentes.',
	'multiple_of' => 'El campo :attribute debe ser un múltiplo de :value.',
	'not_in' => 'El :attribute seleccionado no es válido.',
	'not_regex' => 'El formato del campo :attribute no es válido.',
	'numeric' => 'El campo :attribute debe ser un número.',
	'password' => [
		'letters' => 'El campo :attribute debe contener al menos una letra.',
		'mixed' => 'El campo :attribute debe contener al menos una letra mayúscula y una letra minúscula.',
		'numbers' => 'El campo :attribute debe contener al menos un número.',
		'symbols' => 'El campo :attribute debe contener al menos un símbolo.',
		'uncompromised' => 'El :attribute dado ha aparecido en una fuga de datos. Por favor, elige un :attribute diferente.',
	],
	'present' => 'El campo :attribute debe estar presente.',
	'present_if' => 'El campo :attribute debe estar presente cuando :other es :value.',
	'present_unless' => 'El campo :attribute debe estar presente excepto si :other es :value.',
	'present_with' => 'El campo :attribute debe estar presente cuando :values está presente.',
	'present_with_all' => 'El campo :attribute debe estar presente cuando :values están presentes.',
	'prohibited' => 'El campo :attribute está prohibido.',
	'prohibited_if' => 'El campo :attribute está prohibido cuando :other es :value.',
	'prohibited_unless' => 'El campo :attribute está prohibido excepto si :other está en :values.',
	'prohibits' => 'El campo :attribute prohíbe que :other esté presente.',
	'regex' => 'El formato del campo :attribute es inválido.',
	'required' => 'El campo :attribute es obligatorio.',
	'required_array_keys' => 'El campo :attribute debe contener entradas para: :values.',
	'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
	'required_if_accepted' => 'El campo :attribute es obligatorio cuando :other es aceptado.',
	'required_unless' => 'El campo :attribute es obligatorio excepto cuando :other está en :values.',
	'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
	'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes.',
	'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
	'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los campos :values están presentes.',
	'same' => 'Los campos :attribute y :other deben coincidir.',
	'size' => [
		'numeric' => 'El campo :attribute debe ser :size.',
		'file' => 'El archivo :attribute debe pesar :size kilobytes.',
		'string' => 'El campo :attribute debe contener :size caracteres.',
		'array' => 'El campo :attribute debe contener :size elementos.',
	],
	'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values',
	'string' => 'El campo :attribute debe ser una cadena de caracteres.',
	'timezone' => 'El campo :attribute debe ser una zona horaria válida.',
	'unique' => 'El valor del campo :attribute ya está en uso.',
	'uploaded' => 'El campo :attribute no se pudo subir.',
	'uppercase' => 'El campo :attribute debe estar en mayúsculas.',
	'url' => 'El campo :attribute debe ser una URL válida.',
	'ulid' => 'El campo :attribute debe ser una ULID válida.',
	'uuid' => 'El campo :attribute debe ser una UUID válida.',

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

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
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
		'name' => 'nombre',
		'email' => 'correo electrónico',
		'password' => 'contraseña',
		'password_confirmation' => 'confirmar contraseña',
		'tenant_id' => 'tenant',
	],

];
