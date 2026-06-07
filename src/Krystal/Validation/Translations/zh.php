<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Chinese (Simplified) translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => ':attribute 字段是必填的。',
    'The :attribute must be a valid email address.' => ':attribute 必须是一个有效的电子邮件地址。',
    'The :attribute must be between :min and :max.' => ':attribute 必须在 :min 和 :max 之间。',

    // Expanded numeric rules
    'The :attribute must be an even number.' => ':attribute 必须是偶数。',
    'The :attribute must be a valid floating-point number.' => ':attribute 必须是一个有效的浮点数。',
    'The :attribute must be strictly greater than :min.' => ':attribute 必须严格大于 :min。',
    'The :attribute must be a valid integer.' => ':attribute 必须是一个有效的整数。',
    'The :attribute must be less than or equal to :max.' => ':attribute 必须小于或等于 :max。',
    'The :attribute must be strictly less than :max.' => ':attribute 必须严格小于 :max。',
    'The :attribute must be a negative number.' => ':attribute 必须是一个负数。',
    'The :attribute must be a valid number description.' => ':attribute 必须是一个有效的数字。',
    'The :attribute must be an odd number.' => ':attribute 必须是奇数。',
    'The :attribute must be a positive number.' => ':attribute 必须是一个正数。',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => ':attribute 必须符合指定的 :charset 字符编码。',
    'The :attribute must contain the sub-string phrase: :needle.' => ':attribute 必须包含子字符串: :needle。',
    'The :attribute must end with the specified suffix: :suffix.' => ':attribute 必须以后缀: :suffix 结尾。',
    'The :attribute must contain lowercase characters only.' => ':attribute 必须只包含小写字符。',
    'The :attribute must not exceed a maximum length of :max characters.' => ':attribute 长度不能超过 :max 个字符。',
    'The :attribute must be at least :min characters.' => ':attribute 长度至少为 :min 个字符。',
    'The :attribute field payload must not contain any text characters.' => ':attribute 字段载荷不能包含任何文本字符。',
    'The :attribute text string structure cannot contain HTML or XML tags.' => ':attribute 文本字符串结构不能包含 HTML 或 XML 标签。',
    'The :attribute must start with the specified prefix: :prefix.' => ':attribute 必须以前缀: :prefix 开头。',
    'The :attribute must contain uppercase characters only.' => ':attribute 必须只包含大写字符。',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => ':attribute 必须解析为空的结构状态。',
    'The :attribute field must possess an active data state payload.' => ':attribute 字段必须包含有效的数据载荷状态。',
    'The :attribute cannot equal the specified restricted entry option value.' => ':attribute 不能等于指定的受限输入值。',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => '输入的验证码不正确。',
    'The :attribute must match a valid network internet domain path description.' => ':attribute 必须匹配有效的互联网域名路径描述。',
    'The :attribute field value must be identical to the specified comparison target.' => ':attribute 字段的值必须与指定的比较目标相同。',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute 必须解析为有效的 IPv4 或 IPv6 基础设施地址。',
    'The :attribute must match a valid hardware interface MAC address specification.' => ':attribute 必须匹配有效的硬件接口 MAC 地址。',
    'The structural evaluation format criteria configured for :attribute is invalid.' => '为 :attribute 配置的结构评估格式准则无效。',
    'The :attribute must resolve to a fully qualified web URL path address.' => ':attribute 必须解析为完整的网页 URL 路径地址。',
    'The :attribute can consist of valid structural hexadecimal digits only.' => ':attribute 只能由有效的结构化十六进制数字组成。',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => ':attribute 字段必须解析为有效的逻辑布尔状态。',
    'The passed payload structure inside :attribute is not executable by the engine.' => ':attribute 中传递的载荷结构无法被引擎执行。',
    'The selected :attribute choice is outside the validated compilation index list.' => '选择的 :attribute 超出了验证编译索引列表范围。',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => ':attribute 中的上下文参数必须是无错误的 JSON 结构。',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => ':attribute 中的字符串流无法正确反序列化为原生数据。',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => '分配给 :attribute 的值重复，违反了唯一性约束。',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => ':attribute 中的日历日期格式结构与模板 :format 不匹配。',
    'The chronological layout matching sequence between :attribute and :target failed.' => ':attribute 和 :target 之间的时间顺序布局匹配失败。',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => '分配给 :attribute 的值必须代表标准的月份日期配置。',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => '执行块 :attribute 必须解析为有效的 UNIX 时间戳。',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => ':attribute 中提供的参考时间必须符合 4 位数的年份索引。',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => '本地执行系统找不到与 :attribute 对应的有效目录路径。',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => '系统拒绝了附加到目标载荷参数 :attribute 的文件扩展名。',
    'The localized storage map route configured inside :attribute is not an active target file.' => ':attribute 中配置的本地化存储路由不是一个活跃的目标文件。',
    'The system does not possess structural file system authorization maps to read :attribute.' => '系统没有读取 :attribute 的文件系统权限。',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => '为文件处理载荷 :attribute 分配的存储空间限制超过了最大字节数。',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => ':attribute 的地理投影上下文索引坐标必须在 -90 到 90 度之间。',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => ':attribute 的地理投影上下文索引坐标必须在 -180 到 180 度之间。',
];