<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Portuguese translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => 'O campo :attribute é obrigatório.',
    'The :attribute must be a valid email address.' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'The :attribute must be between :min and :max.' => 'O campo :attribute deve estar entre :min e :max.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => 'O campo :attribute deve ser um número par.',
    'The :attribute must be a valid floating-point number.' => 'O campo :attribute deve ser um número de ponto flutuante válido.',
    'The :attribute must be strictly greater than :min.' => 'O campo :attribute deve ser estritamente maior que :min.',
    'The :attribute must be a valid integer.' => 'O campo :attribute deve ser um número inteiro válido.',
    'The :attribute must be less than or equal to :max.' => 'O campo :attribute deve ser menor ou igual a :max.',
    'The :attribute must be strictly less than :max.' => 'O campo :attribute deve ser estritamente menor que :max.',
    'The :attribute must be a negative number.' => 'O campo :attribute deve ser um número negativo.',
    'The :attribute must be a valid number description.' => 'O campo :attribute deve ser um número válido.',
    'The :attribute must be an odd number.' => 'O campo :attribute deve ser um número ímpar.',
    'The :attribute must be a positive number.' => 'O campo :attribute deve ser um número positivo.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => 'O campo :attribute deve corresponder ao conjunto de caracteres especificado :charset.',
    'The :attribute must contain the sub-string phrase: :needle.' => 'O campo :attribute deve conter a sub-string :needle.',
    'The :attribute must end with the specified suffix: :suffix.' => 'O campo :attribute deve terminar com o sufixo :suffix.',
    'The :attribute must contain lowercase characters only.' => 'O campo :attribute deve conter apenas letras minúsculas.',
    'The :attribute must not exceed a maximum length of :max characters.' => 'O campo :attribute não deve exceder o comprimento máximo de :max caracteres.',
    'The :attribute must be at least :min characters.' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    'The :attribute field payload must not contain any text characters.' => 'O conteúdo do campo :attribute não deve conter caracteres de texto.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => 'A estrutura da string de texto :attribute não deve conter tags HTML ou XML.',
    'The :attribute must start with the specified prefix: :prefix.' => 'O campo :attribute deve começar com o prefixo :prefix.',
    'The :attribute must contain uppercase characters only.' => 'O campo :attribute deve conter apenas letras maiúsculas.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => 'O campo :attribute deve resultar em um estado estrutural vazio.',
    'The :attribute field must possess an active data state payload.' => 'O campo :attribute deve possuir um estado de carga de dados (payload) ativo.',
    'The :attribute cannot equal the specified restricted entry option value.' => 'O campo :attribute não deve ser igual ao valor de entrada restrito especificado.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'O valor do captcha de segurança inserido está incorreto.',
    'The :attribute must match a valid network internet domain path description.' => 'O campo :attribute deve corresponder a uma descrição de caminho de domínio de internet válida.',
    'The :attribute field value must be identical to the specified comparison target.' => 'O valor do campo :attribute deve ser idêntico ao alvo especificado.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'O campo :attribute deve ser resolvido para um endereço de infraestrutura IPv4 ou IPv6 válido.',
    'The :attribute must match a valid hardware interface MAC address specification.' => 'O campo :attribute deve corresponder a um endereço MAC de interface de hardware válido.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => 'O critério de formato de avaliação estrutural configurado para :attribute é inválido.',
    'The :attribute must resolve to a fully qualified web URL path address.' => 'O campo :attribute deve ser resolvido para um endereço de caminho URL web totalmente qualificado.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => 'O campo :attribute deve conter apenas dígitos hexadecimais estruturais válidos.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => 'O campo :attribute deve ser resolvido para um estado booleano lógico válido.',
    'The passed payload structure inside :attribute is not executable by the engine.' => 'A estrutura de carga útil passada em :attribute não é executável pelo motor.',
    'The selected :attribute choice is outside the validated compilation index list.' => 'A opção selecionada para :attribute está fora da lista de índices de compilação validada.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => 'Os parâmetros de contexto em :attribute devem representar uma estrutura JSON livre de erros.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => 'O fluxo de string em :attribute não pode ser desserializado de forma limpa em dados nativos.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => 'O valor atribuído ao campo :attribute já existe e viola as restrições de unicidade.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => 'A estrutura do formato de data do calendário em :attribute não corresponde ao modelo :format.',
    'The chronological layout matching sequence between :attribute and :target failed.' => 'A sequência de correspondência de layout cronológico entre :attribute e :target falhou.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => 'O valor atribuído ao campo :attribute deve corresponder a uma configuração padrão de dia do calendário.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => 'O bloco de execução :attribute deve resultar em uma coordenada de data/hora (timestamp) UNIX epoch válida.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => 'A referência de linha do tempo especificada em :attribute deve corresponder a um índice de ano civil de 4 dígitos.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'O sistema de execução local não consegue encontrar um caminho de diretório alvo válido correspondente a :attribute.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'O sistema rejeitou a extensão de arquivo anexada ao parâmetro de carga útil alvo :attribute.',
    'The localized storage map route configured inside :attribute is not an active target file.' => 'A rota de alocação de armazenamento localizada configurada em :attribute não é um arquivo alvo ativo.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'O sistema carece das atribuições de permissão estruturais do sistema de arquivos para ler :attribute.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'As restrições de espaço de armazenamento atribuídas para a carga útil de processamento de arquivo :attribute excederam os valores máximos de bytes.',
    'The :attribute must be an image (e.g., JPG, PNG, WEBP).' => 'O :attribute deve ser uma imagem (por exemplo, JPG, PNG, WEBP).',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => 'A coordenada de índice do contexto de projeção geográfica para :attribute deve estar entre -90 e 90 graus.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => 'A coordenada de índice do contexto de projeção geográfica para :attribute deve estar entre -180 e 180 graus.',
];