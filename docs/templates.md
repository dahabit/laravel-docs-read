# Templates

- [Layouts do Controlador](#controller-layouts)
- [Blade Templating](#blade-templating-engine)
- [Outras Estruturas de Controle do Blade](#other-blade-control-structures)

<a name="controller-layouts"></a>
## Layouts do Controlador

Uma maneira de utilizar templates em Laravel é através do layouts do controlador. Especificando a propriedade `layout` no controlador, a view específica será criada para você e irá se tornar a resposta de suas ações.

**Definindo Um Layout Em Um Controlador**

	class UserController extends BaseController {

		/**
		 * The layout that should be used for responses.
		 */
		protected $layout = 'layouts.master';

		/**
		 * Show the user profile.
		 */
		public function showProfile()
		{
			$this->layout->content = View::make('user.profile');
		}

	}

<a name="blade-template-engine"></a>
## Blade Templating

Blade é uma simples, e ainda poderoso motor de templating fornecido com o Laravel. Ao contrário de layouts do controlador, Blade é guiado por _herança de template_ e _seções_. Todos os templates Blade deve usar a extensão `.blade.php`.

**Definindo Um Layout Blade**

	<!-- Armazenado em app/views/layouts/master.blade.php -->

	<html>
		<body>
			@section('sidebar')
				Esta é a barra lateral principal.
			@stop

			<div class="container">
				@yield('content')
			</div>
		</body>
	</html>

**Usando Um Layout Blade**

	@extends('layouts.master')

	@section('sidebar')
		@parent

		<p>Isto é anexado a barra lateral principal.</p>
	@stop

	@section('content')
		<p>Este é o conteúdo do corpo.</p>
	@stop

Note que a view que `extend` um layout Blade, simplesmente substitui seções do layout. O conteúdo do layout pode ser incluído em uma view filha usando a directiva `@parent` em uma seção, o que lhe permite anexar o conteúdo de uma seção de layout, como uma barra lateral ou rodapé.

<a name="other-blade-control-structures"></a>
## Outras Estruturas de Controle do Blade

**Ecoado Dados**

	Hello, {{ $name }}.

	The current UNIX timestamp is {{ time() }}.

**Instruções IF**

	@if (count($records) > 0)
		I have records!
	@else
		I don't have any records!
	@endif

	@unless (Auth::check())
		You are not signed in.
	@endunless

**Loops (Laços)**

	@for ($i = 0; $i < 10; $i++)
		The current value is {{ $i }}
	@endfor

	@foreach ($users as $user)
		<p>This is user {{ $user->id }}</p>
	@endforeach

	@while (true)
		<p>I'm looping forever.</p>
	@endwhile

**Incluíndo Sub-Views**

	@include('view.name')

**Comentários**

	{{-- Este comentário não será exibido no HTML --}}
