# Validação

- [Uso Básico](#basic-usage)
- [Trabalhando com Mensagens de Erro](#working-with-error-messages)
- [Mensagens de Erro & Views](#error-messages-and-views)
- [Regras de Validações Disponíveis](#available-validation-rules)
- [Mensagens de Erro Personalizadas](#custom-error-messages)
- [Regras de Erro Personalizadas](#custom-validation-rules)

<a name="basic-usage"></a>
## Uso Básico

Lavarel vem com métodos simples e fáceis para validar e recuperar mensagens de erro através da classe `Validation`.

**Exemplo Básico de Validação**

    $validator = Validator::make(
        array('name' => 'Dayle'),
        array('name' => 'required|min:5')
    );

O primeiro argumento passado para o método `make` sãos os dados a ser validados. O segundo argumento são as regras de validação que serão aplicados aos dados.

Várias regras devem ser delimitadas usando o caractere "pipe", ou como elementos separados em um array.

**Usando Arrays Para Especificar Regras**

    $validator = Validator::make(
        array('name' => 'Dayle'),
        array('name' => array('required', 'min:5'))
    );

Quando uma instância `Validator` for criar, o método `fails` (ou `passes`) pode ser usado para realizar a validação.

    if ($validator->fails())
    {
        // The given data did not pass validation
    }

Se a validação falhar, você receberá mensagens de erro de validação.

    $messages = $validator->messages();

**Validação Arquivos**

A classe `Validator` fornece muitas regras para validar arquivos, como `size`, `mimes`, e outros. Ao validar arquivos, você pode simplesmente passá-los no validador com os outros dados.

<a name="working-with-error-messages"></a>
## Trabalhando com Mensagens de Erro

Depois de chamar o método `messages` de uma instância `Validator`, você receberá uma instância de `MessageBag`, que tem uma variedade de métodos convenientes para o trabalho com as mensagens de erro.

**Recuperando A Primeira Mensagem De Erro De Um Campo**

    echo $messages->first('email');

**Recuperando Todas As Mensagens de Erro De Um Campo**

    foreach ($messages->get('email') as $message)
    {
        //
    }

**Recuperando Todas As Mensagens De Erros De Todos Os Campos**

    foreach ($messages->all() as $message)
    {
        //
    }

**Determinando Se Uma Mensagem De Erro De Um Campo Existe**

    if ($messages->has('email'))
    {
        //
    }

**Recuperando Uma Mensagem De Erro Formatada**

    echo $messages->first('email', '<p>:message</p>');

> **Nota:** Por padrão, as mensagens são formatadas usando sintaxe compatível com o Bootstrap.

**Recuperando Todas As Mensagens De Erro Com Um Formato**

    foreach ($messages->all('<li>:message</li>') as $message)
    {
        //
    }

<a name="error-messages-and-views"></a>
## Mensagens de Erro & Views

Depois de ter realizado a validação, você vai precisar de uma maneira fácil de obter as mensagens de erro de volta em suas views. Isto é convenientemente tratado por Laravel. Considere as seguintes rotas como exemplo:

    Route::get('register', function()
    {
        return View::make('user.register');
    });

    Route::post('register', function()
    {
        $rules = array(...);

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            return Redirect::to('register')->withErrors($validator);
        }
    });

Observe que quando a validação falhar, nós passamos uma instância de `Validator` para o Redirect(redirecionamento) usando o método `withErrors`. Esse método irá guardar as mensagens de erro na sessão para então estar disponíveis na próxima requisição.

No entanto, observe que não temos de chamar explicitamente as mensagens de erro para a view da nossa rota GET. Isto porque Laravel sempre verifica se há erros nos dados da sessão e, automaticamente, vinculá-os na view, se estiverem disponíveis.  **Assim, é importante notar que a variável `$errors` estará sempre disponível nas suas views, em cada requisição**, permitindo você convenientemente que a variável `$errors` é sempre definida e pode ser usado com segurança. A variável `$errors` será uma instância de `MessageBag`.

Sendo assim, depois de redirecionar, utilize automaticamente a variárel `$errors` vinculada na sua view:

    <?php echo $errors->first('email'); ?>

<a name="available-validation-rules"></a>
## Regras de Validações Disponíveis

Segue uma lista de todas as regras de validação disponíveis e suas funções:

- [Aceito](#rule-accepted)
- [URL Ativa](#rule-active-url)
- [Depois (Data)](#rule-after)
- [Alfa(Alfabeto)](#rule-alpha)
- [Alfa Traço](#rule-alpha-dash)
- [Alfa Numérico](#rule-alpha-num)
- [Antes (Data)](#rule-before)
- [Entre](#rule-between)
- [Confirmado](#rule-confirmed)
- [Diferente](#rule-different)
- [E-Mail](#rule-email)
- [Existe (Banco de Dados)](#rule-exists)
- [Imagem (Arquivo)](#rule-image)
- [In(contém)](#rule-in)
- [Inteiro](#rule-integer)
- [Endereço de IP](#rule-ip)
- [Máximo](#rule-max)
- [MIME Types](#rule-mimes)
- [Mínimo](#rule-min)
- [Númerico](#rule-numeric)
- [Expressão Regular](#rule-regex)
- [Obrigatório](#rule-required)
- [Obrigatório com](#rule-required-with)
- [Igual](#rule-same)
- [Tamanho](#rule-size)
- [Único (Banco de Dados)](#rule-unique)
- [URL](#rule-url)

<a name="rule-accepted"></a>
#### accepted(aceito)

O campo em fase de validação deve ser _yes_, _on_, ou _1_. Isto é útil para a validação de aceitação de "Termos de Serviço".

<a name="rule-active-url"></a>
#### active_url(url ativa)

O campo em fase de validação deve ser uma URL válida de acordo com a função `checkdnsrr` do PHP.

<a name="rule-after"></a>
#### after:_date_(depois, data)

O campo em fase de validação deve ser um valor anterior a uma data informada. As datas serão passadas para a função `strtotime` do PHP.

<a name="rule-alpha"></a>
#### alpha (alfabeto)

O campo em fase de validação deve ser totalmente com caracteres do alfabeto.

<a name="rule-alpha-dash"></a>
#### alpha_dash (alfabeto e traços)

O campo em fase de validação deve possuir caracteres alfa-numéricos, e traços e underscores.

<a name="rule-alpha-num"></a>
#### alpha_num (alfa-numérico)

O campo em fase de validação deve possuir caracteres alfa-numéricos

<a name="rule-before"></a>
#### before:_date_ (antes, data)

O campo em fase de validação deve ser um valor anterior a data informada. As datas serão passadas para a função `strtotime` do PHP.

<a name="rule-between"></a>
#### between:_min_,_max_ (entre, minimo, máximo)

O campo em fase de validação deve ter um tamanho entre um minimo e máximo. Strings, números, e arquivos são avaliados da mesma forma da regra `size`.

<a name="rule-confirmed"></a>
#### confirmed (confirmado)

O campo em fase de validação deve ter um campo de casamento. Exemplo, se você tem um campo `senha` a ser validado, um campo de casamento `senha_confirmation` deve estar presente na entrada.

<a name="rule-different"></a>
#### different:_field_ (diferente)

O campo recebido _field_ deve ser diferente do campo a ser validado.

<a name="rule-email"></a>
#### email

O campo em fase de validação deve ter o formato de endereço de email.

<a name="rule-exists"></a>
#### exists:_table_,_column_ (existe, tabela e coluna)

O campo em fase de validação deve existir na tabela informada.

**Uso Básico Da Regra Exists**

    'state' => 'exists:states'

**Especificando Nomes De Colunas Personalizas**

    'state' => 'exists:states,abbreviation'

<a name="rule-image"></a>
#### image (imagem)

O campo em fase de validação deve uma imagem (jpeg, png, bmp, or gif)

<a name="rule-in"></a>
#### in:_foo_,_bar_,... (contém)

O campo em fase de validação deve estar incluso numa lista de valores informada.

<a name="rule-integer"></a>
#### integer

O campo em fase de validação deve ter um valor inteiro.

<a name="rule-ip"></a>
#### ip

O campo em fase de validação deve estar no formato de endereço de IP.

<a name="rule-max"></a>
#### max:_value_ (máximo)

O campo em fase de validação deve ser menor que o _value_ máximo. Strings, números, e arquivos são avaliados da mesma forma que a regra `size`.

<a name="rule-mimes"></a>
#### mimes:_foo_,_bar_,...

O arquivo em fase de validação deve ter um MIME type correspondente com uma lista de extensões.

**Uso Básico da Regra MIME**

    'photo' => 'mimes:jpeg,bmp,png'

<a name="rule-min"></a>
#### min:_value_ (mínimo)

O campo em fase de validação deve ter um _value_ mínimo. Strings, números, e arquivos são avaliados da mesma forma que a regra `size`.

<a name="rule-numeric"></a>
#### numeric (numérico)

O campo em fase de validação deve have a numeric value.

<a name="rule-regex"></a>
#### regex:_pattern_ (expressão regular)

O campo em fase de validação deve casar com a expressão regular informada.

**Note:** Ao usar o padrão `regex`, se faz necessário especificar as regas num array ao invés do delimitador pipe, especialmente se a expressão regular contém o caractere pipe.

<a name="rule-required"></a>
#### required (obrigatório)

O campo em fase de validação deve estar presente nos dados de entrada.

<a name="rule-required-with"></a>
#### required_with:_foo_,_bar_,... (obrigatório com)

O campo em fase de validação deve estar presente _only if_(somente se) o outro campo especificado estiver presente.

<a name="rule-same"></a>
#### same:_field_ (igual)

O _field_(campo) deve corresponder ao campo em fase de validação.

<a name="rule-size"></a>
#### size:_value_ (tamanho)

O campo em fase de validação deve ter um tamanho igual ao _value_(valor informado). Para dados de em string, _value_ corresponde ao número de caracteres. Dados numérico, _value_ corresponde ao inteiro informado. Arquivos, _size_ corresponde ao tamanho do arquivo em kilobytes.

<a name="rule-unique"></a>
#### unique:_table_,_column_,_except_,_idColumn_ (único)

O campo em fase de validação deve ser único na tabela do banco de dados. Se a opção `column` não for informada, o nome do campo será usada.

**Uso Básico Da Regra Único**

    'email' => 'unique:users'

**Especificando Um Nome De Coluna Personalizado**

    'email' => 'unique:users,email_address'

**Forçando A Regra Único A Ignorar Um ID Informado**

    'email' => 'unique:users,email_address,10'

<a name="rule-url"></a>
#### url

O campo em fase de validação deve ser formatado como uma URL.

<a name="custom-error-messages"></a>
## Mensagens de Erro Personalizadas

Se necessário, você pode usar mensagens de erro personalizadas para validação em vez dos padrões. Existem várias formas de especificar mensagens personalizadas.

**Passando Mensagens Personalizadas Para O Validator**

    $messages = array(
        'required' => 'The :attribute field is required.',
    );

    $validator = Validator::make($input, $rules, $messages);

*Nota:* O place-holder `:attribute` será substituído pelo nome real do campo em fase de validação. Você também pode utilizar outros place-holders em mensagens de validação.

**Outros Place-Holders De Validação**

    $messages = array(
        'same'    => 'The :attribute and :other must match.',
        'size'    => 'The :attribute must be exactly :size.',
        'between' => 'The :attribute must be between :min - :max.',
        'in'      => 'The :attribute must be one of the following types: :values',
    );

Talvez você queira especificar um mensagens de erro personalizada para um determinado campo:

**Especificando Uma Mensagem Personalizadas Para Um Determinado Atributo**

    $messages = array(
        'email.required' => 'We need to know your e-mail address!',
    );

Em alguns casos, você pode desejar especificar suas mensagens personalizadas em um arquivo de linguagem ao invés de passar diretamente para o `Validator`. Para isso, adicione suas mensagens para um array `custom` no array do seu arquivo de linguagem `app/lang/xx/validation.php`.

**Especificando Mensagens Personalizadas Em Arquivos de Linguagem**

    'custom' => array(
        'email' => array(
            'required' => 'Queremos saber o seu endereço de e-mail!',
        ),
    ),

<a name="custom-validation-rules"></a>
## Custom Validation Rules

Laravel fornece uma variedade de regras de validação úteis, no entanto, você pode especificar alguns dos seus próprios. Um método de registro de regras de validação personalizadas usa o método `Validator::extend`:

**Registrando Uma Regra de Validação Personalizada**

    Validator::extend('foo', function($attribute, $value, $parameters)
    {
        return $value == 'foo';
    });

A Closure da validação personalizada recebe três argumentos: o nome do atributo `$attribute` a ser validado, o `$value`(valor) do atributo, e um array de `$parameters`(parâmetros) passados para a regra.

Note que você também precisa definir uma mensagem de erro para as suas regras. Você pode fazer isso usando um array de mensagem em linha personalizado ou adicionando uma entrada no arquivo de linguagem de validação.

Em vez de usar uma Closure para estender o Validator, você pode estender a classe Validator. Para isso, escreva uma classe Validator que estende `Illuminate\Validation\Validator`. Você pode adicionar métodos de validação prefixando com `validate`:

**Estendo A Classe Validator**

    <?php

    class CustomValidator extends Illuminate\Validation\Validator {

        public function validateFoo($attribute, $value, $parameters)
        {
            return $value == 'foo';
        }

    }

Depois, registre sua estendida classe Validator personalizada:

**Registrando Um Resolvedor De Validação Personalizada**

    Validator::resolver(function()
    {
        return new CustomValidator;
    });

Ao criar regras de validação personalizada, algumas vezes é necessário definir trocadores de place-holders para as mensagens de erro. Você pode fazer isso criando um validador personalizado, conforme descrito acima, e acrescentando uma função `replaceXXX` para a validação.

    protected function replaceFoo($message, $attribute, $rule, $parameters)
    {
        return str_replace(':foo', $parameters[0], $message);
    }
