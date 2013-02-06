# O Ciclo de Vida da Requisição

- [Visão Geral](#overview)
- [Arquivos de Inicialização](#start-files)
- [Eventos de Aplicativo](#application-events)

<a name="overview"></a>
## Visão Geral

O Ciclo de Vida da requisição do Laravel é bastante simples. Uma requisição entra na sua aplicação e é despachada para a rota apropriada ou controlador. A resposta para aquela rota é então enviada de volta para o navegador e exibida na tela. Algumas vezes você pode querer fazer algum processo antes e depois de sua rota atual ser chamada. Existem muitas oportunidades para fazer isso, dois dos quais são arquivos de "start"(inicialização) e eventos de aplicativo.

<a name="start-files"></a>
## Arquivos de Inicialização

Seus arquivos de inicialização de aplicativos sao armazenados em `app/start`. Por padrão, três são incluídos em sua aplicação: `global.php`, `local.php`, e `artisan.php`. Para mais informações sobre `artisan.php`, consulte a documentação sobre [linha de comando Artisan](/docs/commands#registering-commands).

O arquivo de inicialização `global.php` contém alguns itens básicos por padrão, como o registro do [Logger](/docs/errors) e a inclusão dos seu arquivo `app/filters.php`. Mesmo assim, você pode adicionar qualquer coisa nesse arquivo se quiser. Ele se incluirá automaticamente em _cada_ requisição da sua aplicação, independentemente do ambiente. O arquivo `local.php`, por outro lado, somente é chamado quando a aplicação é executada no ambiente `local`. Para mais informações sobre ambientes, visite a documentação de [configuração](/docs/configuration).

Naturalmente, se você tem outros ambientes alem de `local`, você deve criar arquivos de inicialização para esses outros ambientes. Eles irão automaticamente serem incluídos quando sua aplicação estiver rodando naquele ambiente.

<a name="application-events"></a>
## Eventos de Aplicativo

Você também pode fazer antes e depois do processo de requisição registrando eventos de aplicativo `before`(antes) e `after`(depois):

**Registrando Eventos do Aplicativo**

    App::before(function()
    {
        //
    });

    App::after(function()
    {
        //
    });

Listeners(ouvintes) desses eventos irá executar `before`(antes) e `after`(depois) de cada requisição na sua aplicação.