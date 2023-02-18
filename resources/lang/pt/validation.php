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

'accepted' => ':attribute deve ser aceito.',
'accepted_if' => ':attribute deve ser aceito quando :other é :value.',
'active_url' => ':attribute não é uma URL válida.',
'after' => ':attribute deve ser uma data após :date.',
'after_or_equal' => ':attribute deve ser uma data após ou igual a :date.',
'alpha' => ':attribute deve conter apenas letras.',
'alpha_dash' => ':attribute deve conter apenas letras, números, traços e sublinhados.',
'alpha_num' => ':attribute deve conter apenas letras e números.',
'array' => ':attribute deve ser uma matriz.',
'before' => ':attribute deve ser uma data antes de :date.',
'before_or_equal' => ':attribute deve ser uma data antes ou igual a :date.',
'between' => [
'numeric' => ':attribute deve estar entre :min e :max.',
'file' => ':attribute deve ter entre :min e :max kilobytes.',
'string' => ':attribute deve ter entre :min e :max caracteres.',
'array' => ':attribute deve ter entre :min e :max itens.',
],
'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
'confirmed' => 'A confirmação de :attribute não coincide.',
'current_password' => 'A senha está incorreta.',
'date' => ':attribute não é uma data válida.',
'date_equals' => ':attribute deve ser uma data igual a :date.',
'date_format' => ':attribute não coincide com o formato :format.',
'declined' => ':attribute deve ser recusado.',
'declined_if' => ':attribute deve ser recusado quando :other é :value.',
'different' => ':attribute e :other devem ser diferentes.',
'digits' => ':attribute deve ter :digits dígitos.',
'digits_between' => ':attribute deve ter entre :min e :max dígitos.',
'dimensions' => ':attribute tem dimensões de imagem inválidas.',
'distinct' => 'O campo :attribute possui um valor duplicado.',
'email' => ':attribute deve ser um endereço de e-mail válido.',
'ends_with' => ':attribute deve terminar com um dos seguintes: :values.',
'exists' => ':attribute selecionado é inválido.',
'file' => ':attribute deve ser um arquivo.',
'filled' => 'O campo :attribute deve ter um valor.',
'gt' => [
'numeric' => ':attribute deve ser maior que :value.',
'file' => ':attribute deve ter mais de :value kilobytes.',
'string' => ':attribute deve ter mais de :value caracteres.',
'array' => ':attribute deve ter mais de :value itens.',
],
'gte' => [
'numeric' => ':attribute deve ser maior ou igual a :value.',
'file' => ':attribute deve ser maior ou igual a :value kilobytes.',
'string' => ':attribute deve ser maior ou igual a :value caracteres.',
'array' => ':attribute deve ter :value itens ou mais.',
],
'image' => ':attribute deve ser uma imagem.',
'in' => ':attribute selecionado é inválido.',
'in_array' => 'O campo :attribute não existe em :other.',
'integer' => ':attribute deve ser um número inteiro.',
'ip' => ':attribute deve ser um endereço IP válido.',
'ipv4' => ':attribute deve ser um endereço IPv4 válido.',
'ipv6' => ':attribute deve ser um endereço IPv6 válido.',
'json' => 'O campo :attribute deve ser uma string JSON válida.',
'lt' => [
'numeric' => 'O campo :attribute deve ser menor que :value.',
'file' => 'O campo :attribute deve ser menor que :value kilobytes.',
'string' => 'O campo :attribute deve ter menos de :value caracteres.',
'array' => 'O campo :attribute deve ter menos de :value itens.',
],
'lte' => [
'numeric' => 'O campo :attribute deve ser menor ou igual a :value.',
'file' => 'O campo :attribute deve ser menor ou igual a :value kilobytes.',
'string' => 'O campo :attribute deve ter no máximo :value caracteres.',
'array' => 'O campo :attribute não deve ter mais do que :value itens.',
],
'max' => [
'numeric' => 'O campo :attribute não deve ser maior que :max.',
'file' => 'O campo :attribute não deve ser maior que :max kilobytes.',
'string' => 'O campo :attribute não deve ter mais do que :max caracteres.',
'array' => 'O campo :attribute não deve ter mais do que :max itens.',
],
'mimes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
'mimetypes' => 'O campo :attribute deve ser um arquivo do tipo: :values.',
'min' => [
'numeric' => 'O campo :attribute deve ser no mínimo :min.',
'file' => 'O campo :attribute deve ter no mínimo :min kilobytes.',
'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
'array' => 'O campo :attribute deve ter pelo menos :min itens.',
],
'multiple_of' => 'O campo :attribute deve ser um múltiplo de :value.',
'not_in' => 'O campo :attribute selecionado é inválido.',
'not_regex' => 'O formato do campo :attribute é inválido.',
'numeric' => 'O campo :attribute deve ser um número.',
'password' => 'A senha está incorreta.',
'present' => 'O campo :attribute deve estar presente.',
'prohibited' => 'O campo :attribute é proibido.',
'prohibited_if' => 'O campo :attribute é proibido quando :other é :value.',
'prohibited_unless' => 'O campo :attribute é proibido a menos que :other esteja em :values.',
'prohibits' => 'O campo :attribute proíbe :other de estar presente.',
'regex' => 'O formato do campo :attribute é inválido.',
'required' => 'O campo :attribute é obrigatório.',
'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
'required_unless' => 'O campo :attribute é obrigatório a menos que :other esteja em :values.',
'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos valores :values estão presentes.',
'same' => 'Os campos :attribute e :other devem ser iguais.',
'size' => [
'numeric' => 'O :attribute deve ser :size.',
'file' => 'O :attribute deve ter :size kilobytes.',
'string' => 'O :attribute deve ter :size caracteres.',
'array' => 'O :attribute deve conter :size itens.',
],
'starts_with' => 'O :attribute deve começar com um dos seguintes valores: :values.',
'string' => 'O :attribute deve ser uma string.',
'timezone' => 'O :attribute deve ser um fuso horário válido.',
'unique' => 'O :attribute já foi utilizado.',
'uploaded' => 'Falha no envio do arquivo :attribute.',
'url' => 'O :attribute deve ser uma URL válida.',
'uuid' => 'O :attribute deve ser um UUID válido.',

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

'reserved' => 'O campo :attribute não é válido.',

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

    'attributes' => [],

];
