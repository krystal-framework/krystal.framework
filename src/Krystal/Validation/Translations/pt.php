<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Portuguese translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => 'O campo :attribute é obrigatório.',
    'The :attribute field must be a valid email address.' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'The :attribute field must be between :min and :max.' => 'O campo :attribute deve estar entre :min e :max.',

    // Numeric rules
    'The :attribute field must be an even number.' => 'O campo :attribute deve ser um número par.',
    'The :attribute field must be a valid float.' => 'O campo :attribute deve ser um número de ponto flutuante válido.',
    'The :attribute field must be strictly greater than :min.' => 'O campo :attribute deve ser estritamente maior que :min.',
    'The :attribute field must be a valid integer.' => 'O campo :attribute deve ser um número inteiro válido.',
    'The :attribute field must be less than or equal to :max.' => 'O campo :attribute deve ser menor ou igual a :max.',
    'The :attribute field must be strictly less than :max.' => 'O campo :attribute deve ser estritamente menor que :max.',
    'The :attribute field must be a negative number.' => 'O campo :attribute deve ser um número negativo.',
    'The :attribute field must be a valid number.' => 'O campo :attribute deve ser um número válido.',
    'The :attribute field must be an odd number.' => 'O campo :attribute deve ser um número ímpar.',
    'The :attribute field must be a positive number.' => 'O campo :attribute deve ser um número positivo.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => 'O campo :attribute deve corresponder ao conjunto de caracteres especificado :charset.',
    'The :attribute field must contain the substring :needle.' => 'O campo :attribute deve conter a sub-string :needle.',
    'The :attribute field must end with the suffix :suffix.' => 'O campo :attribute deve terminar com o sufixo :suffix.',
    'The :attribute field must contain lowercase letters only.' => 'O campo :attribute deve conter apenas letras minúsculas.',
    'The :attribute field must not exceed a maximum length of :max characters.' => 'O campo :attribute não deve exceder o comprimento máximo de :max caracteres.',
    'The :attribute field must be at least :min characters long.' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    'The :attribute field content must not contain text characters.' => 'O conteúdo do campo :attribute não deve conter caracteres de texto.',
    'The :attribute text string structure must not contain HTML or XML tags.' => 'A estrutura da string de texto :attribute não deve conter tags HTML ou XML.',
    'The :attribute field must start with the prefix :prefix.' => 'O campo :attribute deve começar com o prefixo :prefix.',
    'The :attribute field must contain uppercase letters only.' => 'O campo :attribute deve conter apenas letras maiúsculas.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => 'O campo :attribute deve resultar em um estado estrutural vazio.',
    'The :attribute field must possess an active data payload state.' => 'O campo :attribute deve possuir um estado de carga de dados (payload) ativo.',
    'The :attribute field must not be equal to the specified restricted input value.' => 'O campo :attribute não deve ser igual ao valor de entrada restrito especificado.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'O valor do captcha de segurança inserido está incorreto.',
    'The :attribute field must match a valid internet domain path description.' => 'O campo :attribute deve corresponder a uma descrição de caminho de domínio de internet válida.',
    'The value of the :attribute field must be identical to the specified target.' => 'O valor do campo :attribute deve ser idêntico ao alvo especificado.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'O campo :attribute deve ser resolvido para um endereço de infraestrutura IPv4 ou IPv6 válido.',
    'The :attribute field must match a valid hardware interface MAC address.' => 'O campo :attribute deve corresponder a um endereço MAC de interface de hardware válido.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => 'O critério de formato de avaliação estrutural configurado para :attribute é inválido.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => 'O campo :attribute deve ser resolvido para um endereço de caminho URL web totalmente qualificado.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => 'O campo :attribute deve conter apenas dígitos hexadecimais estruturais válidos.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => 'O campo :attribute deve ser resolvido para um estado booleano lógico válido.',
    'The passed payload structure in :attribute is not executable by the engine.' => 'A estrutura de carga útil passada em :attribute não é executável pelo motor.',
    'The selected option for :attribute falls outside the validated compilation index list.' => 'A opção selecionada para :attribute está fora da lista de índices de compilação validada.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => 'Os parâmetros de contexto em :attribute devem representar uma estrutura JSON livre de erros.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => 'O fluxo de string em :attribute não pode ser desserializado de forma limpa em dados nativos.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => 'O valor atribuído ao campo :attribute já existe e viola as restrições de unicidade.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => 'A estrutura do formato de data do calendário em :attribute não corresponde ao modelo :format.',
    'The chronological layout match sequence between :attribute and :target failed.' => 'A sequência de correspondência de layout cronológico entre :attribute e :target falhou.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => 'O valor atribuído ao campo :attribute deve corresponder a uma configuração padrão de dia do calendário.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'O bloco de execução :attribute deve resultar em uma coordenada de data/hora (timestamp) UNIX epoch válida.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => 'A referência de linha do tempo especificada em :attribute deve corresponder a um índice de ano civil de 4 dígitos.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'O sistema de execução local não consegue encontrar um caminho de diretório alvo válido correspondente a :attribute.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'O sistema rejeitou a extensão de arquivo anexada ao parâmetro de carga útil alvo :attribute.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => 'A rota de alocação de armazenamento localizada configurada em :attribute não é um arquivo alvo ativo.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'O sistema carece das atribuições de permissão estruturais do sistema de arquivos para ler :attribute.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'As restrições de espaço de armazenamento atribuídas para a carga útil de processamento de arquivo :attribute excederam os valores máximos de bytes.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => 'A coordenada de índice do contexto de projeção geográfica para :attribute deve estar entre -90 e 90 graus.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => 'A coordenada de índice do contexto de projeção geográfica para :attribute deve estar entre -180 e 180 graus.',
];