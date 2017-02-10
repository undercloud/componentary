<?php
require __DIR__ . '/DomHelper.php';
require __DIR__ . '/AbstractDom.php';
require __DIR__ . '/DomWalker.php';
require __DIR__ . '/Resolver.php';
require __DIR__ . '/Component.php';
require __DIR__ . '/Element.php';
require __DIR__ . '/Style.php';
require __DIR__ . '/ClassList.php';

use Elementary\Component;
use Elementary\Element;

Class Combo extends Component
{
	public function render()
	{
		$e = new Element('img');

		$e->style = [
			'border' => '2px solid red',
			'maxWidth' => '200px'
		];

		$e->src = 'https://www.petfinder.com/wp-content/uploads/2013/09/cat-black-superstitious-fcs-cat-myths-162286659.jpg';

		return $e;
	}
}

class App extends Component
{
	public function render()
	{
		return (
			"<Combo hu='ombo' />
			<ul>
				<li>Mali</li>
			</ul>"
		);
	}
}

$e = new App;
echo $e;