# Eloquent ORM

- [Introducão](#introduction)
- [Uso Básico](#basic-usage)
- [Insert, Update, Delete](#insert-update-delete)
- [Timestamps](#timestamps)
- [Relacionamentos](#relationships)
- [Carregamento Prévio](#eager-loading)
- [Inserindo em Modelos Relacionados](#inserting-related-models)
- [Trabalhando com Tabelas Intermediárias](#working-with-pivot-tables)
- [Coleções](#collections)
- [Acessadores & Modificadores](#accessors-and-mutators)
- [Atribuição em Massa](#mass-assignment)
- [Covertendo para Arrays / JSON](#converting-to-arrays-or-json)

<a name="introduction"></a>
## Introdução

O Eloquent ORM incluso no Laravel fornece uma bonita e simples implementação ActiveRecord para trabalhar com seu banco de dados. Cada tabela do banco de dados tem um "Model" correspondente que será usado para interagir com a tabela.

Antes de iniciarmos, certifique se de configurar uma conexão com o banco de dados no `app/config/database.php`.

<a name="basic-usage"></a>
## Uso Básico

Para começar, crie um modelo Eloquent. Modelos ficam armazenados no diretório `app/models`, mas você é livre para colocar em qualquer lugar que você quiser desde que possam ser automaticamente carregados de acordo com seu arquivo `composer.json`.

**Definindo um Modelo Eloquent**

	class User extends Eloquent {}

Note que não informamos ao Eloquent que tabela o modelo `User` irá usar. O nome da classe em letra minúscula e no plural, será usado como o nome da tabela, a menos que outro nome seja especificado. Sendo assim, o Eloquent assume que modelo `User` armazena os registros na tabela `users`. Especifique a propriedade `table` no seu modelo, caso queira usar um nome personalizado para a tabela:

	class User extends Eloquent {

		protected $table = 'my_users';

	}

> **Nota:** Eloquent irá assumir também, que cada tabela possui uma chave primária com o nome `id`. Caso queira usar outro nome, defina a propriedade `primaryKey`.

Uma vez que o modelo está definido, você está pronto para começar a recuperar e criar registros na sua tabela. Note que você vai precisar colocar as colunas `updated_at` e `created_at` em sua tabela por padrão. Se você não deseja ter essas colunas mantidas automaticamente, defina a propriedade `$timestamps` no seu modelo para `false`

**Recuperando Todos Os Modelos(Registros)**

	$users = User::all();

**Recuperando Um Registro Através Da Chave Primária**

	$user = User::find(1);

	var_dump($user->name);

> **Nota:** Todos os métodos disponíveis no [construtor de consultas](/docs/queries) também estão disponíveis ao consultar modelos do Eloquent.

**Consultando Usando Modelos Eloquent**

	$users = User::where('votes', '>', 100)->take(10)->get();

	foreach ($users as $user)
	{
		var_dump($user->name);
	}

Claro, você pode também usar funções agregadoras do construtor de consultas.

**Agregadores No Eloquent**

	$count = User::where('votes', '>', 100)->count();

<a name="insert-update-delete"></a>
## Insert, Update, Delete

Para criar um novo registro em seu banco de dados por um modelo, simplesmente crie uma nova instância do modelo e chame o método `save`.

**Salvando Um Novo Modelo**

	$user = new User;

	$user->name = 'John';

	$user->save();

Você pode também usar o método `create` para salvar um novo modelo em uma só linha. A instância do modelo inserido será retornada do método:

**Usando O Método Create Do Modelo**

	$user = User::create(array('name' => 'John'));

Para atualizar um modelo, você o recupera, muda o atributo, e usa o método `save`:

**Atualizando Um Modelo Recuperado**

	$user = User::find(1);

	$user->email = 'john@foo.com';

	$user->save();

Você pode executar atualizações encadeando de consultas dos modelos:

	$affectedRows = User::where('votes', '>', 100)->update(array('status' => 2));

Para apagar um modelo, simplesmente chame o método `delete` method em uma instância:

**Apagando Um Modelo Existente**

	$user = User::find(1);

	$user->delete();

Sim, você pode apagar encadeando uma consulta no modelo:

	$affectedRows = User::where('votes', '>', 100)->delete();

<a name="timestamps"></a>
## Timestamps

Por padrão, Eloquent mantém as colunas `created_at` e `updated_at` nas tabelas do seu banco de dados, automaticamente. Basta adicionar essas colunas `datetime` para a sua tabela e o Eloquent vai cuidar do resto. Se você não deseja que o Eloquent mantenha essas colunas, defina a propriedade:

**Disabilitando Auto Timestamps**

	class User extends Eloquent {

		protected $table = 'users';

		public $timestamps = false;

	}

Se desejar personalizar o formato de seus timestamps, sobrescreva o método `freshTimestamp` em seu modelo:

**Fornecendo Um Formato Personalizado Para Timestamp**

	class User extends Eloquent {

		public function freshTimestamp()
		{
			return time();
		}

	}

<a name="relationships"></a>
## Relacionamentos

Suas tabelas do banco de dados provavelmente estão relacionadas a alguma outra. Por exemplo, um publicação de blog podem ter muitos comentários, ou pode estar relacionado ao usuário que publicou. Eloquent faz o gerenciamento e trabalho com essas relações, fácil. Laravel suporta quatro tipos de relações:

- [Um Para Um](#one-to-one)
- [Um Para Muitos](#one-to-many)
- [Muitos Para Muitos](#many-to-many)
- [Relacionamentos Polimórficos](#polymorphic-relations)

<a name="one-to-one"></a>
### Um para Um

Um relacionamento um-para-um é uma relação muita básica. Exemplo, um modelo `User` pode ter um `Phone`. Podemos definir essa relação no Eloquent:

**Definindo Uma Relação Um Para Um**

	class User extends Eloquent {

		public function phone()
		{
			return $this->hasOne('Phone');
		}

	}

O primeiro argumento passado para o método `hasOne` é o nome do modelo relacionado. Assim que relação é definida, podemos recuperar isso usando propriedades dinâmicas do Eloquent:

	$phone = User::find(1)->phone;

O Sql realizado por essa instrução será como a seguinte:

	select * from users where id = 1

	select * from phones where user_id = 1

Note que Eloquent assume que a chave estrangeira do relacionamento é baseada no nome do modelo. No exemplo, o modelo `Phone` assume que a chave estrangeira usada é `user_id`. Se desejar sobrescrever essa convenção, passe um segundo argumento para o método `hasOne`:

	return $this->hasOne('Phone', 'chave_personalizada');

Para definir o inverso, a relação no modelo `Phone`, usamos o método `belongsTo`:

**Definindo O Inverso De Uma Relação**

	class Phone extends Eloquent {

		public function user()
		{
			return $this->belongsTo('User');
		}

	}

<a name="one-to-many"></a>
### Um para Muitos

Um exemplo de relacionamento um-para-muitos é um publicação de blog que "tem muitos" comentários. Podemos modelar essa relação:

	class Post extends Eloquent {

		public function comments()
		{
			return $this->hasMany('Comment');
		}

	}

Agora podemos acessar os comentários da publicação através da propriedade dinâmica:

	$comments = Post::find(1)->comments;

Se você precisar adicionar mais restrições para as quais os posts são recuperados, poderá invocar o método comments e continuar com as condições de encadeamento:

	$comments = Post::find(1)->comments()->where('title', '=', 'foo')->first();

Novamente, você pode sobrescrever a convencional chave estrangeira passando um segundo argumento ao método `hasMany`:

	return $this->hasMany('Comment', 'custom_key');

Para definir o inverso dessa relação no modelo `Comment`, nos usámos o método `belongsTo`:

**Definindo O Inverso De Uma Relação**

	class Comment extends Eloquent {

		public function post()
		{
			return $this->belongsTo('Post');
		}

	}

<a name="many-to-many"></a>
### Muitos Para Muitos

Relações muitos-para-muitos é o tipo de relacionamento mais complicado. Um exemplo de um relacionamento é um usuário com muitos papéis(roles), onde os papéis também compartilhados por outros usuários. Exemplo, muitos usuários tem o papel de "Admin". Três tabelas são necessárias para este relacionamento: `users`, `roles`, e `role_user`. A tabela `role_user` é derivada da ordem alfabética dos nomes dos modelos relacionados, e devem ter as colunas `user_id` e `role_id`.

Podemos definir relações muitos-para-muitos usando o método `belongsToMany`:

	class User extends Eloquent {

		public function roles()
		{
			return $this->belongsToMany('Role');
		}

	}

Agora podemos recuperar os papéis(roles) através do modelo `User`:

	$roles = User::find(1)->roles;

Se você quiser usar um nome de tabela não convencional para a sua tabela intermediária, você pode passar como um segundo argumento para o método `belongsToMany`:

	return $this->belongsToMany('Role', 'user_roles');

Você pode sobrescrever a convenção de chaves associadas:

	return $this->belongsToMany('Role', 'user_roles', 'user_id', 'foo_id');

<a name="polymorphic-relations"></a>
### Relacionamentos Polimórficos

Relacionamentos Polimórficos permitem um modelo pertencer a mais de um modelo numa simples associação. Exemplo, você pode ter um modelo photo(foto) que pertence a um modelo staff(equipe) ou a um modelo order(pedido). Definiríamos essa relação assim:

	class Photo extends Eloquent {

		public function imageable()
		{
			return $this->morphTo();
		}

	}

	class Staff extends Eloquent {

		public function photos()
		{
			return $this->morphMany('Photo', 'imageable');
		}

	}

	class Order extends Eloquent {

		public function photos()
		{
			return $this->morphMany('Photo', 'imageable');
		}

	}

Agora, podemos recuperar as photos(fotos) de um membro da staff(equipe) ou de um order(pedido):

**Recuperando Um Relacionamento Polimórfico**

	$staff = Staff::find(1);

	foreach ($staff->photos as $photo)
	{
		//
	}

Mas, a verdadeira magia do "polimórfico" é quando você acessa staff(equipe) ou order(pedido) do modelo `Photo`:

**Recuperando O Dono De Um Relacionamento Polimórfico**

	Photo::find(1);

	$imageable = $photo->imageable;

A relação `imageable` no modelo `Photo` retornará uma instância de `Staff` ou `Order`, dependendo de qual tipo é o dono do modelo photo.

Para ajudar a entender como isso funciona, vamos explorar a estrutura do banco de dados dessa relação polimórfica:

**Estruturas da Tabela da Relação Polimórfica**

	staff
		id - integer
		name - string

	orders
		id - integer
		price - integer

	photos
		id - integer
		path - string
		imageable_id - integer
		imageable_type - string

Os campos chave para observar aqui são `imageable_id` e `imageable_type` na tabela `photos`. O imageable_id conterá o valor do ID, que neste exemplo, será o staff ou order, enquanto que imageable_type irá conter o nome da classe dona do modelo. Isso é o que permite o ORM determinar que tipo possui o modelo para retornar quando acessar a relação `imageable`.

<a name="eager-loading"></a>
## Carregamento Prévio

Carregamento prévio existe para aliviar problema com consulta N + 1. Exemplo, considere um modelo `Book` que está relacionado a `Author`. A relação é definida assim:

	class Book extends Eloquent {

		public function author()
		{
			return $this->belongsTo('Author');
		}

	}

Agora considere o seguinte código:

	foreach (Book::all() as $book)
	{
		echo $book->author->name;
	}

Esse loop executará 1 consulta para recuperar todos os livros, em seguida, uma outra consulta onde cada livro recupera o autor. Então, se temos 25 livros, este loop executará 26 consultas.

Felizmente, podemos usar o carregamento prévio para reduzir drasticamente o número de consultas. As relações que devem ser carregadas previamente pode ser especificado através do método `with`:

	foreach (Book::with('author')->get() as $book)
	{
		echo $book->author->name;
	}

No loop acima, apenas duas consultas serão executadas:

	select * from books

	select * from authors where id in (1, 2, 3, 4, 5, ...)

Uso inteligente de carregamento prévio pode aumentar drasticamente o desempenho de sua aplicação.

Claro, você pode carregar previamente múltiplas relações de uma só vez:

	$books = Book::with('author', 'publisher')->get();

Você pode até mesmo carregar relações aninhadas:

	$books = Book::with('author.contacts')->get();

No exemplo acima, a relação `author` será previamente carregada, e a relação `contacts` também será carregada.

### Condições de Carregamento Prévio

Talvez, você deseje carregar previamente uma relação, mas também especificar uma condição. Aqui está um exemplo:

	$users = User::with(array('posts' => function($query)
	{
		$query->where('title', 'like', '%first%');
	}))->get();

Nesse exemplo, posts dos usuários serão previamente carregados, mas somente se o posts possuírem a palavra "first" na coluna title.

### Carregamento Prévio Preguiçoso

Também é possível carregar modelos previamente relacionados diretamente de uma coleção de modelo já existente. Isto pode ser útil quando decidir dinamicamente carregar modelos relacionados ou não, ou em combinação com caching.

	$books = Book::all();

	$books->load('author', 'publisher');

<a name="inserting-related-models"></a>
## Inserindo em Modelos Relacionados

Frequentemente você precisá inserir novos modelos relacionados. Por exemplo, você pode querer inserir um novo comentário para um post. Em vez de configurar manualmente a chave estrangeira `post_id` no modelo, você pode inserir o novo comentário diretamente no seu modelo pai `Post`:

**Anexando Um Modelo Relacionado**

	$comment = new Comment(array('message' => 'A new comment.'));

	$post = Post::find(1);

	$comment = $post->comments()->save($comment);

Nesse exemplo, o campo `post_id` será automaticante definido no comentário inserido.

### Inserindo em Modelos Relacionados (Muitos Para Muitos)

Você também pode inserir modelos relacionados quando trabalhar com relações muitos-para-muitos relações. Vamos continuar usando nossos modelos `User` e `Role` como exemplos. Podemos facilmente anexar novos papéis(roles) para um usuário usando o método `attach`:

**Anexando Modelos Muitos Para Muitos**

	$user = User::find(1);

	$user->roles()->attach(1);

Você pode passar um conjunto de atributos que serão armazenados na tabela intermediária da relação:

	$user->roles()->attach(1, array('expires' => $expires));

Você também pode usar o método `sync` para anexar modelos relacionados. O método `sync` aceita um array de IDs para colocar na tabela intermediária. Após a operação ser concluída, apenas os IDs do array estará na tabela intermédia do modelo:

**Usando Sync Para Anexar Modelos Muitos Para Muitos**

	$user->roles()->sync(array(1, 2, 3));

Outras vezes você pode querer criar um novo modelo relacional e anexar em um simples comando. Para isso, use o método `save`:

	$role = new Role(array('name' => 'Editor'));

	User::find(1)->roles()->save($role);

No exemplo, o novo modelo `Role` será salvo e anexado para o modelo usuário. Você pode também passar um conjunto de atributos para colocar junto da tabela para esta operação:

	User::find(1)->roles()->save($role, array('expires' => $expires));

<a name="working-with-pivot-tables"></a>
## Trabalhando com Tabelas Dinâmicas

Como você já aprendeu, trabalhar com relações muitos-para-muitos requer uma tabela intermediária. Eloquent fornece algumas maneiras muito úteis de interagir com essa tabela. Exemplo, vamos assumir que nosso objeto `User` tem muitos objetos `Role` que se relacionam. Depois de acessar essa relação, podemos acessar a tabela intermediária dos modelos:

	$user = User::find(1);

	foreach ($user->roles as $role)
	{
		echo $role->pivot->created_at;
	}

Observe que cada modelo `Role` que recuperamos é automaticamente recebe um atributo `pivot`. Esse atributo contem o modelo que representa a tabela intermediária, e pode ser utilizado como qualquer outro modelo Eloquent.

Por padrão, apenas as chaves estarão presentes no objeto o `pivot`. Se a sua tabela dinâmica contém atributos extras, você deve especificá-los ao definir a relação:

	return $this->belongsToMany('Role')->withPivot('foo', 'bar');

Agora os atributos `foo` and `bar` serão acessíveis no nosso objeto `pivot` do modelo `Role`.

Se você deseja que suas tabelas dinâmicas mantenham `created_at` e `updated_at` timestamps, use o método `withTimestamps` na definição da relação:

	return $this->belongsToMany('Role')->withTimestamps();

Para excluir todos os registros da tabela intermediária de um modelo, você pode usar o método `delete`:

**Apagando Registros De Uma Tabela Intermediária**

	User::find(1)->roles()->delete();

Note que essa operação não deleta registros da tabela `roles`, mas somente da tabela intermediária.

<a name="collections"></a>
## Coleções

Todos os conjuntos de vários resultados retornado pelo Eloquent através do método `get` ou por uma relação, retorna um objeto `Collection` do Eloquent. Este objeto implementa a interface `IteratorAggregate` do PHP para que possa ser iterado como um array. No entanto, este objeto tem também uma variedade de outros métodos úteis para trabalhar com os conjuntos de resultados.

Por exemplo, podemos determinar se um resultado contém uma chave primária informada, usando o método `contains`:

**Verificando Se Uma Coleção Contém Uma Chave**

	$roles = User::find(1)->roles;

	if ($roles->contains(2))
	{
		//
	}

Coleções podem ser convertidas em array ou JSON:

	$roles = User::find(1)->roles->toArray();

	$roles = User::find(1)->roles->toJson();

Se uma coleção for convertida para string, será retornado um JSON:

	$roles = (string) User::find(1)->roles;

Pode ser que você queria retornar um objeto coleção personalizada, com métodos próprios adicionados. Você pode especificar isso no seu modelo Eloquent sobrescrevendo o método `newCollection`:

**Retornando Uma Coleção Personalizada**

	class User extends Eloquent {

		public function newCollection(array $models = array())
		{
			return new CustomCollection($models);
		}

	}

<a name="accessors-and-mutators"></a>
## Acessadores & Modificadores

Eloquent fornece uma maneira conveniente para transformar atributos do seu modelo quando acessá-los ou atribuí-los. Basta definir um método `getFoo` para declarar um acessador. Mantenha em mente que os métodos devem ser camel-casing, mesmo que suas colunas de banco de dados sejam snake-case:

**Definindo Um Acessador**

	class User extends Eloquent {

		public function giveFirstName($value)
		{
			return ucfirst($value);
		}

	}

No exemplo acima, a coluna `first_name` tem um acessador. Note que o valor do atributo é passado para o acesssador.

Modificadores são declarados de maneira semelhante:

**Definindo Um Modificador**

	class User extends Eloquent {

		public function takeFirstName($value)
		{
			$this->attributes['first_name'] = strtolower($value);
		}

	}

<a name="mass-assignment"></a>
## Atribuição em Massa

Ao criar um novo modelo, você passa um array de valores para o construtor do modelo. Estes valores são então atribuídos ao modelo através da atribuição em massa. Isto é conveniente, no entanto, pode ter um problema **sério** de segurança, quando cegamente passa os valores de entrada do usuário para o modelo. Se a entrada do usuário é cegamente passada para um modelo, o usuário é livre para modificar **qualquer** e **todos** os atributos do modelo.

Uma abordagem mais segura para a atribuição de valores, é eles atribuir manualmente, ou para definir as propriedades `fillable(preenchível)` ou `guarded(protegido)` no seu modelo.

A propriedade `fillable` especifica quais atributos podem ser atribuídos em massa. Isso pode ser definido no nível da classe ou da instância.

**Definindo Atributos Preenchíveis Em Um Modelo**

	class User extends Eloquent {

		protected $fillable = array('first_name', 'last_name', 'email');

	}

Neste exemplo, apenas os três atributos listados serão atribuídos em massa.

`guarded` é o inverso de `fillable`, e serve como uma "lista negra":

**Definindo Atributos Protegidos Em Um Modelo**

	class User extends Eloquent {

		protected $guarded = array('id', 'password');

	}

No exemplo acima, os atributos `id` e `password` **não** podem ser atribuídos em massa. Todos os outros atributos podem. Você pode também bloquear **todos** atributos de atribuição em massa usando o método de guarda:

**Bloqueando Todos Os Atributos Contra Uma Atribuição Em Massa**

	protected $guarded = array('*');

<a name="converting-to-arrays-or-json"></a>
## Covertendo para Arrays / JSON

Ao construir APIs JSON, você pode precisar converter seus modelos e relações para arrays ou JSON. Então, Eloquent inclui métodos para isso. Para converter um modelo e sua relação já carregada para uma array, você pode usar o método `toArray`:

**Convertendo Um Modelo Para Um Array**

	$user = User::with('roles')->first();

	return $user->toArray();

Veja que coleções inteiras de modelos podem também ser convertidos para arrays:

	return User::all()->toArray();

Para converter um modelo para JSON, use o método `toJson`:

**Convertendo Um Modelo Para JSON**

	return User::find(1)->toJson();


Lembre-se que quando um modelo ou coleção é convertido para uma string, ele será convertido para JSON, ou seja, você pode retornar objetos Eloquent diretamente das rotas da sua aplicação!

**Retornando Um Modelo Por Uma Rota**

	Route::get('users', function()
	{
		return User::all();
	});

Às vezes, você pode querer limitar os atributos que retornados no array do seu modelo ou no JSON, como senhas. Para fazer isso, adicione a propriedade `hidden` para o seu modelo:

**Ocultando Atributos Da Conversão Array Ou JSON**

	class User extends Eloquent {

		protected $hidden = array('password');

	}
