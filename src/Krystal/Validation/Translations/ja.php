<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Japanese translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => ':attributeフィールドは必須です。',
    'The :attribute field must be a valid email address.' => ':attributeフィールドは有効なメールアドレス形式である必要があります。',
    'The :attribute field must be between :min and :max.' => ':attributeフィールドは:minから:maxの間である必要があります。',

    // Numeric rules
    'The :attribute field must be an even number.' => ':attributeフィールドは偶数である必要があります。',
    'The :attribute field must be a valid float.' => ':attributeフィールドは有効な浮動小数点数である必要があります。',
    'The :attribute field must be strictly greater than :min.' => ':attributeフィールドは:minより大きい必要があります。',
    'The :attribute field must be a valid integer.' => ':attributeフィールドは有効な整数である必要があります。',
    'The :attribute field must be less than or equal to :max.' => ':attributeフィールドは:max以下である必要があります。',
    'The :attribute field must be strictly less than :max.' => ':attributeフィールドは:max未満である必要があります。',
    'The :attribute field must be a negative number.' => ':attributeフィールドは負の数である必要があります。',
    'The :attribute field must be a valid number.' => ':attributeフィールドは有効な数値である必要があります。',
    'The :attribute field must be an odd number.' => ':attributeフィールドは奇数である必要があります。',
    'The :attribute field must be a positive number.' => ':attributeフィールドは正の数である必要があります。',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => ':attributeフィールドは指定された文字コード（:charset）と一致する必要があります。',
    'The :attribute field must contain the substring :needle.' => ':attributeフィールドは部分文字列「:needle」を含んでいる必要があります。',
    'The :attribute field must end with the suffix :suffix.' => ':attributeフィールドは末尾がサフィックス「:suffix」で終わる必要があります。',
    'The :attribute field must contain lowercase letters only.' => ':attributeフィールドは小文字のみを含んでいる必要があります。',
    'The :attribute field must not exceed a maximum length of :max characters.' => ':attributeフィールドは最大:max文字を超えてはなりません。',
    'The :attribute field must be at least :min characters long.' => ':attributeフィールドは最小:min文字以上である必要があります。',
    'The :attribute field content must not contain text characters.' => ':attributeフィールドの内容にテキスト文字を含めることはできません。',
    'The :attribute text string structure must not contain HTML or XML tags.' => ':attributeテキスト文字列構造にHTMLまたはXMLタグを含めることはできません。',
    'The :attribute field must start with the prefix :prefix.' => ':attributeフィールドはプレフィックス「:prefix」で始まる必要があります。',
    'The :attribute field must contain uppercase letters only.' => ':attributeフィールドは大文字のみを含んでいる必要があります。',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => ':attributeフィールドは空の構造状態である必要があります。',
    'The :attribute field must possess an active data payload state.' => ':attributeフィールドはアクティブなデータペイロード状態を保持している必要があります。',
    'The :attribute field must not be equal to the specified restricted input value.' => ':attributeフィールドは指定された制限された入力値と等しくてはなりません。',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => '入力されたセキュリティキャプチャの値が正しくありません。',
    'The :attribute field must match a valid internet domain path description.' => ':attributeフィールドは有効なインターネットドメインパスの記述と一致する必要があります。',
    'The value of the :attribute field must be identical to the specified target.' => ':attributeフィールドの値は指定されたターゲットと同一である必要があります。',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attributeフィールドは有効なIPv4またはIPv6インフラストラクチャアドレスに解決される必要があります。',
    'The :attribute field must match a valid hardware interface MAC address.' => ':attributeフィールドは有効なハードウェアインターフェースのMACアドレスと一致する必要があります。',
    'The structural evaluation format criterion configured for :attribute is invalid.' => ':attributeに設定された構造評価フォーマット基準が無効です。',
    'The :attribute field must resolve to a fully qualified web URL path address.' => ':attributeフィールドは完全修飾されたWeb URLパスアドレスに解決される必要があります。',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => ':attributeフィールドは有効な構造的16進数のみで構成されている必要があります。',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => ':attributeフィールドは有効な論理ブール状態に解決される必要があります。',
    'The passed payload structure in :attribute is not executable by the engine.' => ':attributeで渡されたペイロード構造はエンジンで実行可能ではありません。',
    'The selected option for :attribute falls outside the validated compilation index list.' => ':attributeで選択されたオプションは、検証済みのコンパイルインデックスリストの範囲外です。',
    'The context parameters in :attribute must represent an error-free JSON structure.' => ':attributeのコンテキストパラメータはエラーのないJSON構造を表している必要があります。',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => ':attributeの文字列ストリームは、ネイティブデータに正常にデシリアライズできません。',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => ':attributeフィールドに割り当てられた値はすでに存在し、一意性制約に違反しています。',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => ':attributeのカレンダー日付フォーマット構造がテンプレート「:format」と一致しません。',
    'The chronological layout match sequence between :attribute and :target failed.' => ':attributeと:targetの間の時系列レイアウト一致シーケンスに失敗しました。',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => ':attributeフィールドに割り当てられた値は、標準的なカレンダーの日付設定に対応している必要があります。',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => '実行ブロック:attributeは有効なUNIXエポックタイムスタンプ座標を返す必要があります。',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => ':attributeで指定されたタイムライン参照は、4桁のカレンダー西暦インデックスと一致する必要があります。',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'ローカル実行システムは、:attributeに対応する有効なディレクトリパスのターゲットを見つけることができません。',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'システムは、ターゲットペイロードパラメータ:attributeに付加されたファイル拡張子を拒否しました。',
    'The localized storage allocation route configured in :attribute is not an active target file.' => ':attributeに設定されたローカライズされたストレージ割り当てルートは、アクティブなターゲットファイルではありません。',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'システムは、:attributeを読み取るための構造的なファイルシステム権限の割り当てを欠いています。',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'ファイル処理ペイロード:attributeに割り当てられたストレージスペースの制約が最大バイト値を超えました。',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => ':attributeの地理的投影コンテキストインデックス座標は-90度から90度の間である必要があります。',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => ':attributeの地理的投影コンテキストインデックス座標は-180度から180度の間である必要があります。',
];