# Contribuindo com o Laravel

- [Introdução](#introduction)
- [Pull Requests](#pull-requests)
- [Normas de Codificação](#coding-guidelines)

<a name="introduction"></a>
## Introdução

Laravel é livre, software de código aberto, sendo assim qualquer um pode contribuir para o seu desenvolvimento e progresso. O código fonte do Laravel está atualmente hospedado em [Github](http://github.com), que proporciona uma maneira fácil para forkar o projeto e mesclar suas contribuições.

<a name="pull-requests"></a>
## Pull Requests

O processo de pull request é diferente para novos recursos e para bugs. Antes de enviar um pull request para uma novo recurso, você deverá primeiro criar um issue com `[Proposal]` no título. A proposta deverá descrever o novo recurso, bem como ideias para a implementação. A proposta será então revisto e, aprovado ou negado. Uma vez que a proposta seja aprovada, um pull request pode ser criado para a implementação do novo recurso. Pull requests que não seguem essa diretriz, será fechado imediatamente.

Pull requests para bugs pode ser enviado sem a criação de qualquer proposta. Se você acredita que conhece uma solução para o bug apresentado no Github, por favor, deixe um comentário detalhando sua proposta de correção.

### Solicitações de Recursos

Se você tem uma ideia para um novo recurso e gostaria de vê-lo adicionando ao Laravel, você pode criar um issue com `[Request]` no título. O recurso solicitado será revisto por um colaborador do núcleo do Laravel.

<a name="coding-guidelines"></a>
## Normas de Codificação

Laravel segue os padrões de código da [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) e [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md). Como complemento a esses padrões, segue uma lista de outros padrões de código que devem ser seguidos:

- Declarações de Namespace devem estar na mesma linha que `<?php`.
- Abertura de classes `{` devem estar na mesma linha do nome da classe.
- Abertura de funções e estruturas de controle `{` devem estar em uma linha separada.
- Sempre use `and` e `or`. Nunca `&&` ou `||`.
- Nomes de interfaces devem iniciar com `Interface` (`FooInterface`)
