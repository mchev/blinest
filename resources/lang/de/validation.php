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

'accepted' => 'Das Feld :attribute muss akzeptiert werden.',
'accepted_if' => 'Das Feld :attribute muss akzeptiert werden, wenn :other gleich :value ist.',
'active_url' => 'Das Feld :attribute ist keine gültige URL.',
'after' => 'Das Feld :attribute muss ein Datum nach dem :date sein.',
'after_or_equal' => 'Das Feld :attribute muss ein Datum nach oder gleich dem :date sein.',
'alpha' => 'Das Feld :attribute darf nur Buchstaben enthalten.',
'alpha_dash' => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
'alpha_num' => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
'array' => 'Das Feld :attribute muss ein Array sein.',
'before' => 'Das Feld :attribute muss ein Datum vor dem :date sein.',
'before_or_equal' => 'Das Feld :attribute muss ein Datum vor oder gleich dem :date sein.',
    'between' => [
'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
'file' => 'Das Feld :attribute muss zwischen :min und :max Kilobytes groß sein.',
'string' => 'Das Feld :attribute muss zwischen :min und :max Zeichen lang sein.',
'array' => 'Das Feld :attribute muss zwischen :min und :max Elemente haben.',
],
'boolean' => 'Das Feld :attribute muss wahr oder falsch sein.',
'confirmed' => 'Die Bestätigung für das Feld :attribute stimmt nicht überein.',
'current_password' => 'Das Passwort ist falsch.',
'date' => 'Das Feld :attribute ist kein gültiges Datum.',
'date_equals' => 'Das Feld :attribute muss ein Datum gleich dem :date sein.',
'date_format' => 'Das Feld :attribute entspricht nicht dem Format :format.',
'declined' => 'Das Feld :attribute muss abgelehnt werden.',
'declined_if' => 'Das Feld :attribute muss abgelehnt werden, wenn :other gleich :value ist.',
'different' => 'Das Feld :attribute und :other müssen unterschiedlich sein.',
'digits' => 'Das Feld :attribute muss :digits Ziffern enthalten.',
'digits_between' => 'Das Feld :attribute muss zwischen :min und :max Ziffern enthalten.',
'dimensions' => 'Das Feld :attribute hat ungültige Bildabmessungen.',
'distinct' => 'Das Feld :attribute hat einen duplizierten Wert.',
'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
'ends_with' => 'Das Feld :attribute muss mit einem der folgenden Werte enden: :values.',
'exists' => 'Das ausgewählte :attribute ist ungültig.',
'file' => 'Das Feld :attribute muss eine Datei sein.',
'filled' => 'Das Feld :attribute muss einen Wert enthalten.',
 'gt' => [
'numeric' => 'Das :attribute muss größer als :value sein.',
'file' => 'Die :attribute muss größer als :value Kilobytes sein.',
'string' => 'Die :attribute muss mehr als :value Zeichen haben.',
'array' => 'Die :attribute muss mehr als :value Elemente haben.',
],
'gte' => [
'numeric' => 'Das :attribute muss größer oder gleich :value sein.',
'file' => 'Die :attribute muss größer oder gleich :value Kilobytes sein.',
'string' => 'Die :attribute muss größer oder gleich :value Zeichen haben.',
'array' => 'Die :attribute muss :value Elemente oder mehr haben.',
],
'image' => 'Das :attribute muss ein Bild sein.',
'in' => 'Das ausgewählte :attribute ist ungültig.',
'in_array' => 'Das :attribute-Feld existiert nicht in :other.',
'integer' => 'Das :attribute muss eine ganze Zahl sein.',
'ip' => 'Das :attribute muss eine gültige IP-Adresse sein.',
'ipv4' => 'Das :attribute muss eine gültige IPv4-Adresse sein.',
'ipv6' => 'Das :attribute muss eine gültige IPv6-Adresse sein.',
'json' => 'Das :attribute muss eine gültige JSON-Zeichenfolge sein.',
'lt' => [
'numeric' => 'Das :attribute muss kleiner als :value sein.',
'file' => 'Die :attribute muss kleiner als :value Kilobytes sein.',
'string' => 'Die :attribute muss weniger als :value Zeichen haben.',
'array' => 'Die :attribute muss weniger als :value Elemente haben.',
],
'lte' => [
'numeric' => 'Das :attribute muss kleiner oder gleich :value sein.',
'file' => 'Die :attribute muss kleiner oder gleich :value Kilobytes sein.',
'string' => 'Die :attribute muss kleiner oder gleich :value Zeichen haben.',
'array' => 'Die :attribute darf nicht mehr als :value Elemente haben.',
],
'max' => [
'numeric' => 'Das :attribute darf nicht größer als :max sein.',
'file' => 'Die :attribute darf nicht größer als :max Kilobytes sein.',
'string' => 'Die :attribute darf nicht größer als :max Zeichen sein.',
'array' => 'Die :attribute darf nicht mehr als :max Elemente haben.',
],
'mimes' => 'Das :attribute muss eine Datei vom Typ :values sein.',
'mimetypes' => 'Das :attribute muss eine Datei vom Typ :values sein.',
'min' => [
'numeric' => 'Das :attribute muss mindestens :min sein.',
'file' => 'Die :attribute muss mindestens :min Kilobytes sein.',
'string' => 'Die :attribute muss mindestens :min Zeichen haben.',
'array' => 'Die :attribute muss mindestens :min Elemente haben.',
],
'multiple_of' => 'Das :attribute muss ein Vielfaches von :value sein.',
'not_in' => 'Das ausgewählte :attribute ist ungültig.',
'not_regex' => 'Das Format des :attribute ist ungültig.',
'numeric' => 'Das :attribute muss eine Zahl sein.',
   'password' => 'Das Passwort ist inkorrekt.',
'present' => 'Das Feld :attribute muss vorhanden sein.',
'prohibited' => 'Das Feld :attribute ist nicht erlaubt.',
'prohibited_if' => 'Das Feld :attribute ist nicht erlaubt, wenn :other :value ist.',
'prohibited_unless' => 'Das Feld :attribute ist nicht erlaubt, es sei denn, :other ist in :values enthalten.',
'prohibits' => 'Das Feld :attribute verbietet es, dass :other vorhanden ist.',
'regex' => 'Das Format des Feldes :attribute ist ungültig.',
'required' => 'Das Feld :attribute ist erforderlich.',
'required_if' => 'Das Feld :attribute ist erforderlich, wenn :other :value ist.',
'required_unless' => 'Das Feld :attribute ist erforderlich, es sei denn, :other ist in :values enthalten.',
'required_with' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
'required_with_all' => 'Das Feld :attribute ist erforderlich, wenn alle :values vorhanden sind.',
'required_without' => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keine der :values vorhanden ist.',
'same' => 'Das Feld :attribute und :other müssen übereinstimmen.',
'size' => [
'numeric' => 'Das Feld :attribute muss :size sein.',
'file' => 'Das Feld :attribute muss :size Kilobytes groß sein.',
'string' => 'Das Feld :attribute muss :size Zeichen enthalten.',
'array' => 'Das Feld :attribute muss :size Elemente enthalten.',
],
'starts_with' => 'Das Feld :attribute muss mit einem der folgenden Werte beginnen: :values.',
'string' => 'Das Feld :attribute muss eine Zeichenkette sein.',
'timezone' => 'Das Feld :attribute muss eine gültige Zeitzone sein.',
'unique' => 'Das Feld :attribute wurde bereits verwendet.',
'uploaded' => 'Das Hochladen von :attribute ist fehlgeschlagen.',
'url' => 'Das Feld :attribute muss eine gültige URL sein.',
'uuid' => 'Das Feld :attribute muss eine gültige UUID sein.',

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

  'reserved' => 'Das :attribute Feld ist ungültig.',

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
