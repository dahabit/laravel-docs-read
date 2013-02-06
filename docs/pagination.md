# Paginação

- [Configuração](#configuration)
- [Uso](#usage)

<a name="configuration"></a>
## Configuração

Em outros frameworks, a paginação pode ser dolorosa. Laravel faz isso suave. Há uma única opção no arquivo de configuração `app/config/view.php`. A opção `pagination` especifica qual view deve ser usada para criar links de paginação. Por padrão, Laravel inclui duas views.

A view `pagination::slider` que irá mostrar uma inteligente "série" de links baseado-se na página atual, enquanto a viw `pagination::simple` simplesmente mostra  os botoões "previous(anterior)" e "next(próxima)". **Ambas as views são compatíveis com o Twitter Bootstrap.**

<a name="usage"></a>
## Uso

Existem várias maneiras para paginar itens. A forma mais simples é usando o método `paginate` do construtor de query ou do modelo Eloquent.

**Paginando Resultados do Banco de Dados**

	$users = DB::table('users')->paginate(15);

Você pode também paginar o modelo [Eloquent](/docs/eloquent):

**Paginando um Modelo Eloquent**

	$users = User::where('votes', '>', 100)->paginate(15);

O argumento passado para o método é o número de itens que você deseja mostrar por páginas. Uma vez que tenha recebido os resultados, você pode mostrar eles em sua view, e criar links de paginação usando o método `links`:

	<div class="container">
		<?php foreach ($users as $user): ?>
			<?php echo $user->name;
		<?php endforeach; ?>
	</div>

	<?php echo $users->links(); ?>

Isso é tudo o que é preciso para criar um sistema de paginação! Observe que não é preciso informar ao framework a página atual. Laravel irá determinar isso para você automaticamente.

Às vezes, você pode querer criar uma instância de paginação manualmente, passando uma série de itens. Você pode usar o método `Paginator::make`:

**Criando Paginação Manualmente**

	$paginator = Paginator::make($items, $totalItems, $perPage);