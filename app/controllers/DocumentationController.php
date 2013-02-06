<?php

/**
 * Hande requests for documentation.
 *
 * @author Dayle Rees <me@daylerees.com>
 * @copyright  Dayle Rees 2013.
 * @license MIT <http://opensource.org/licenses/MIT>
 */
class DocumentationController extends Controller {

	public function showDocs($chapter = null)
	{
		// Show installation page by default.
		if ($chapter === null) $chapter = Config::get('docs.home', 'home');


		// Build an array of file stubs to load from disk and
		// include the documentation index by default.
		$data = array(
			'chapter'	=> $chapter,
			'index'		=> Config::get('docs.index', 'documentation')
		);

		// Laravel promotes best practice, please handle Exceptions
		// wisely with appropriate try{}catch{} statements.
		try {

			// We use Markdown Extra for parsing, this library has been
			// included from the package composer.json.
			$markdown = new Markdown();

			// Walk through the data array, loading documentation from
			// the filesystem and converting it to markdown for display
			// on the documentation pages.
			array_walk($data, function(&$raw) use ($markdown) {
				$path = base_path().Config::get('docs.path', '/docs');
				$raw = File::get($path."/{$raw}.md");
				$raw = $markdown->transformMarkdown($raw);
			});

		}
		catch (Exception $e) {

			// Catch all exceptions and abort the application with the 404
			// status command which will show our 404 page.
			App::abort(404);

		}

		// Show the documentation template, which extends our master template
		// and provides a documentation index within the sidebar section.
		return View::make('docs', $data);
	}

}
