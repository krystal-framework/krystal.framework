<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Chinese (Simplified) translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => ':attribute 字段是必填的。',
    'The :attribute field must be a valid email address.' => ':attribute 字段必须是一个有效的电子邮件地址。',
    'The :attribute field must be between :min and :max.' => ':attribute 字段必须在 :min 和 :max 之间。',

    // Numeric rules
    'The :attribute field must be an even number.' => ':attribute 字段必须是偶数。',
    'The :attribute field must be a valid float.' => ':attribute 字段必须是一个有效的浮点数。',
    'The :attribute field must be strictly greater than :min.' => ':attribute 字段必须严格大于 :min。',
    'The :attribute field must be a valid integer.' => ':attribute 字段必须是一个有效的整数。',
    'The :attribute field must be less than or equal to :max.' => ':attribute 字段必须小于或等于 :max。',
    'The :attribute field must be strictly less than :max.' => ':attribute 字段必须严格小于 :max。',
    'The :attribute field must be a negative number.' => ':attribute 字段必须是一个负数。',
    'The :attribute field must be a valid number.' => ':attribute 字段必须是一个有效的数字。',
    'The :attribute field must be an odd number.' => ':attribute 字段必须是奇数。',
    'The :attribute field must be a positive number.' => ':attribute 字段必须是一个正数。',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => ':attribute 字段必须符合指定的字符集 :charset。',
    'The :attribute field must contain the substring :needle.' => ':attribute 字段必须包含子字符串 :needle。',
    'The :attribute field must end with the suffix :suffix.' => ':attribute 字段必须以后缀 :suffix 结尾。',
    'The :attribute field must contain lowercase letters only.' => ':attribute 字段必须只包含小写字母。',
    'The :attribute field must not exceed a maximum length of :max characters.' => ':attribute 字段的长度不能超过 :max 个字符。',
    'The :attribute field must be at least :min characters long.' => ':attribute 字段的长度必须至少为 :min 个字符。',
    'The :attribute field content must not contain text characters.' => ':attribute 字段的内容不能包含文本字符。',
    'The :attribute text string structure must not contain HTML or XML tags.' => ':attribute 文本字符串结构不能包含 HTML 或 XML 标签。',
    'The :attribute field must start with the prefix :prefix.' => ':attribute 字段必须以前缀 :prefix 开头。',
    'The :attribute field must contain uppercase letters only.' => ':attribute 字段必须只包含大写字母。',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => ':attribute 字段必须返回为空的结构状态。',
    'The :attribute field must possess an active data payload state.' => ':attribute 字段必须包含有效的数据载荷状态。',
    'The :attribute field must not be equal to the specified restricted input value.' => ':attribute 字段不能等于指定的受限输入值。',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => '输入的图形验证码不正确。',
    'The :attribute field must match a valid internet domain path description.' => ':attribute 字段必须符合有效的互联网域名路径描述。',
    'The value of the :attribute field must be identical to the specified target.' => ':attribute 字段的值必须与指定的 :target 相同。',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute 字段必须解析为有效的 IPv4 或 IPv6 基础设施地址。',
    'The :attribute field must match a valid hardware interface MAC address.' => ':attribute 字段必须匹配有效的硬件接口 MAC 地址。',
    'The structural evaluation format criterion configured for :attribute is invalid.' => '为 :attribute 配置的结构评估格式准则无效。',
    'The :attribute field must resolve to a fully qualified web URL path address.' => ':attribute 字段必须解析为完整的网页 URL 路径地址。',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => ':attribute 字段必须仅由有效的结构化十六进制数字组成。',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => ':attribute 字段必须解析为有效的逻辑布尔状态。',
    'The passed payload structure in :attribute is not executable by the engine.' => '在 :attribute 中传递的载荷结构无法被引擎执行。',
    'The selected option for :attribute falls outside the validated compilation index list.' => '为 :attribute 选择的选项超出了验证的编译索引列表范围。',
    'The context parameters in :attribute must represent an error-free JSON structure.' => ':attribute 中的上下文参数必须是无错误的 JSON 结构。',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => ':attribute 中的字符串流无法正确反序列化为原生数据。',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => '分配给 :attribute 字段的值已经存在，且违反了唯一性约束。',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => ':attribute 中的日历日期格式结构与模板 :format 不匹配。',
    'The chronological layout match sequence between :attribute and :target failed.' => ':attribute 和 :target 之间的按时间顺序布局匹配序列失败。',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => '分配给 :attribute 字段的值必须符合标准的日历天配置。',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => '执行块 :attribute 必须返回有效的 UNIX 时间戳坐标。',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => ':attribute 中指定的时线参考必须符合 4 位数字的日历年份索引。',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => '本地执行系统找不到与 :attribute 对应的有效目录路径目标。',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => '系统拒绝了附加到目标载荷参数 :attribute 的文件扩展名。',
    'The localized storage allocation route configured in :attribute is not an active target file.' => '在 :attribute 中配置的本地化存储分配路由不是一个活跃的目标文件。',
    'The system lacks the structural file-system permission assignments to read :attribute.' => '系统缺乏读取 :attribute 的结构化文件系统权限。',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => '为文件处理载荷 :attribute 分配的存储空间限制超过了最大字节值。',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => ':attribute 的地理投影上下文索引坐标必须在 -90 到 90 度之间。',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => ':attribute 的地理投影上下文索引坐标必须在 -180 到 180 度之间。',
];