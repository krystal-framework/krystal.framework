<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Japanese translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => ':attributeフィールドは必須です。',
    'The :attribute must be a valid email address.' => ':attributeフィールドは有効なメールアドレス形式である必要があります。',
    'The :attribute must be between :min and :max.' => ':attributeフィールドは:minから:maxの間である必要があります。',

    // Expanded numeric rules
    'The :attribute must be an even number.' => ':attributeフィールドは偶数である必要があります。',
    'The :attribute must be a valid floating-point number.' => ':attributeフィールドは有効な浮動小数点数である必要があります。',
    'The :attribute must be strictly greater than :min.' => ':attributeフィールドは:minより大きい必要があります。',
    'The :attribute must be a valid integer.' => ':attributeフィールドは有効な整数である必要があります。',
    'The :attribute must be less than or equal to :max.' => ':attributeフィールドは:max以下である必要があります。',
    'The :attribute must be strictly less than :max.' => ':attributeフィールドは:max未満である必要があります。',
    'The :attribute must be a negative number.' => ':attributeフィールドは負の数である必要があります。',
    'The :attribute must be a valid number description.' => ':attributeフィールドは有効な数値である必要があります。',
    'The :attribute must be an odd number.' => ':attributeフィールドは奇数である必要があります。',
    'The :attribute must be a positive number.' => ':attributeフィールドは正の数である必要があります。',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => ':attributeフィールドは指定された文字コード（:charset）と一致する必要があります。',
    'The :attribute must contain the sub-string phrase: :needle.' => ':attributeフィールドは部分文字列「:needle」を含んでいる必要があります。',
    'The :attribute must end with the specified suffix: :suffix.' => ':attributeフィールドは末尾がサフィックス「:suffix」で終わる必要があります。',
    'The :attribute must contain lowercase characters only.' => ':attributeフィールドは小文字のみを含んでいる必要があります。',
    'The :attribute must not exceed a maximum length of :max characters.' => ':attributeフィールドは最大:max文字を超えてはなりません。',
    'The :attribute must be at least :min characters.' => ':attributeフィールドは最小:min文字以上である必要があります。',
    'The :attribute field payload must not contain any text characters.' => ':attributeフィールドの内容にテキスト文字を含めることはできません。',
    'The :attribute text string structure cannot contain HTML or XML tags.' => ':attributeテキスト文字列構造にHTMLまたはXMLタグを含めることはできません。',
    'The :attribute must start with the specified prefix: :prefix.' => ':attributeフィールドはプレフィックス「:prefix」で始まる必要があります。',
    'The :attribute must contain uppercase characters only.' => ':attributeフィールドは大文字のみを含んでいる必要があります。',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => ':attributeフィールドは空の構造状態である必要があります。',
    'The :attribute field must possess an active data state payload.' => ':attributeフィールドはアクティブなデータペイロード状態を保持している必要があります。',
    'The :attribute cannot equal the specified restricted entry option value.' => ':attributeフィールドは指定された制限された入力値と等しくてはなりません。',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => '入力されたセキュリティキャプチャの値が正しくありません。',
    'The :attribute must match a valid network internet domain path description.' => ':attributeフィールドは有効なインターネットドメインパスの記述と一致する必要があります。',
    'The :attribute field value must be identical to the specified comparison target.' => ':attributeフィールドの値は指定されたターゲットと同一である必要があります。',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attributeフィールドは有効なIPv4またはIPv6インフラストラクチャアドレスに解決される必要があります。',
    'The :attribute must match a valid hardware interface MAC address specification.' => ':attributeフィールドは有効なハードウェアインターフェースのMACアドレスと一致する必要があります。',
    'The structural evaluation format criteria configured for :attribute is invalid.' => ':attributeに設定された構造評価フォーマット基準が無効です。',
    'The :attribute must resolve to a fully qualified web URL path address.' => ':attributeフィールドは完全修飾されたWeb URLパスアドレスに解決される必要があります。',
    'The :attribute can consist of valid structural hexadecimal digits only.' => ':attributeフィールドは有効な構造的16進数のみで構成されている必要があります。',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => ':attributeフィールドは有効な論理ブール状態に解決される必要があります。',
    'The passed payload structure inside :attribute is not executable by the engine.' => ':attributeで渡されたペイロード構造はエンジンで実行可能ではありません。',
    'The selected :attribute choice is outside the validated compilation index list.' => ':attributeで選択されたオプションは、検証済みのコンパイルインデックスリストの範囲外です。',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => ':attributeのコンテキストパラメータはエラーのないJSON構造を表している必要があります。',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => ':attributeの文字列ストリームは、ネイティブデータに正常にデシリアライズできません。',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => ':attributeフィールドに割り当てられた値はすでに存在し、一意性制約に違反しています。',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => ':attributeのカレンダー日付フォーマット構造がテンプレート「:format」と一致しません。',
    'The chronological layout matching sequence between :attribute and :target failed.' => ':attributeと:targetの間の時系列レイアウト一致シーケンスに失敗しました。',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => ':attributeフィールドに割り当てられた値は、標準的なカレンダーの日付設定に対応している必要があります。',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => '実行ブロック:attributeは有効なUNIXエポックタイムスタンプ座標を返す必要があります。',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => ':attributeで指定されたタイムライン参照は、4桁のカレンダー西暦インデックスと一致する必要があります。',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'ローカル実行システムは、:attributeに対応する有効なディレクトリパスのターゲットを見つけることができません。',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'システムは、ターゲットペイロードパラメータ:attributeに付加されたファイル拡張子を拒否しました。',
    'The localized storage map route configured inside :attribute is not an active target file.' => ':attributeに設定されたローカライズされたストレージ割り当てルートは、アクティブなターゲットファイルではありません。',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'システムは、:attributeを読み取るための構造的なファイルシステム権限の割り当てを欠いています。',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'ファイル処理ペイロード:attributeに割り当てられたストレージスペースの制約が最大バイト値を超えました。',
    'The :attribute must be an image (e.g., JPG, PNG, WEBP).' => 'attribute は画像である必要があります (例: JPG, PNG, WEBP)。',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => ':attributeの地理的投影コンテキストインデックス座標は-90度から90度の間である必要があります。',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => ':attributeの地理的投影コンテキストインデックス座標は-180度から180度の間である必要があります。',
];