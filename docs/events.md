# Eventos

- [Uso Básico](#basic-usage)
- [Usando Classes como Listeners(Ouvintes)](#using-classes-as-listeners)
- [Subscribers(Assinantes) de Eventos](#event-subscribers)

<a name="basic-usage"></a>
## Uso Básico

A Classe `Event` do Laravel fornece uma simples implementação de observador, permitindo você subscribe(assinar) e listen(ouvir) eventos na sua aplicação. A instalação de eventos do Laravel estende a classe `Symfony\Component\EventDispatcher\EventDispatcher`.

**Assinando Um Evento**

	Event::listen('user.login', function($event)
	{
		$event->user->last_login = new DateTime;

		$event->user->save();
	});

**Disparando Um Evento**

	$event = Event::fire('user.login', array('user' => $user));

Note que o método `Event::fire` retorna um objeto `Event`, permitindo você verificar a carga do evento depois dos listeners(ouvintes) terem sido chamados.

Você podem também especificar a prioridade quando subscribing(assinar) eventos. Listeners(ouvintes) que tem alta prioridade serão executados primeiro, enquanto listeneres(ouvintes) que possuem a mesma prioridade serão executados pela ordem de subscription(assinatura).

**Assinando Eventos Com Prioridade**

	Event::listen('user.login', 'LoginHandler', 10);

	Event::listen('user.login', 'OtherHandler', 5);

Algumas vezes, você pode desejar interromper a propagação de um evento ou outro. Você pode fazer apensar usando o método `$event->stop()`.

**Interrompendo A Propagação De Um Evento**

	Event::listen('user.login', function($event)
	{
		// Handle the event...

		$event->stop();
	});

<a name="using-classes-as-listeners"></a>
## Usando Classes como Listeners(Ouvintes)

Em alguns casos, você queira usar uma classe como manipuladora de um evento do que uma Closure. Classes de listeners(ouvintes) de evento fora do [Laravel IoC container](/docs/ioc), fornecendo um completo poder de injeção de dependências para os seus listeners(ouvintes).

**Registrando Uma Classe Listener(Ouvinte)**

	Event::listen('user.login', 'LoginHandler');

Por padrão, o método `handle` na classe `LoginHandler` será chamada:

**Definindo Uma Classe De Event Listener(Ouvinte)**

	class LoginHandler {

		public function handle($event)
		{
			//
		}

	}

Se você não quiser o método `handle` como padrão, especifique o método que deverá ser subscribed(assinado):

**Especificando Que Método Será Subscribe(assinado)**

	Event::listen('user.login', 'LoginHandler@onLogin');

<a name="event-subscribers"></a>
## Subscribers(Assinantes) de Eventos

Subscribers(Assinantes) de eventos são classes que podem subscribe(assinar) mútiplos eventos de dentro da própria classe. Subscribers(assinantes) devem estender a classe `EventSubscriber` e definir um método `subscribes`.

**Definindo Um Subscriber(assinante) de Evento**

	class UserEventHandler extends EventSubscriber {

		/**
		 * Handle user login events.
		 */
		public function onUserLogin($event)
		{
			//
		}

		/**
		 * Handle user logout events.
		 */
		public function onUserLogout($event)
		{
			//
		}

		/**
		 * Register the listeners for the subscriber.
		 *
		 * @return array
		 */
		public static function subscribes()
		{
			return array(
				'user.login' => array(
					array('onUserLogin', 10),
				),
				'user.logout' => array(
					array('onUserLogout', 10),
				),
			);
		}

	}

Uma vez que a subscriber(assinatura) foi definida, ela pode ser registrada com a classe `Event`.

**Registrando Um Subscriber(assinante) de Evento**

	$subscriber = new UserEventHandler();
	Event::subscribe($subscriber);

**Removendo Um Subscriber(assinante) de Evento**

	Event::unsubscribe($subscriber);
	
A instância que que foi passada para o método `subscribe()` também pode ser passada para o método `unsubscribe()` removê-la.
