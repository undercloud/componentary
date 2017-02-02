<?php
require __DIR__ . '/DomHelper.php';
require __DIR__ . '/AbstractDom.php';
require __DIR__ . '/DomWalker.php';
require __DIR__ . '/Resolver.php';
require __DIR__ . '/Element.php';

Class Combo extends Element
{
	public function render()
	{
		return (
			'<h1>Yeeeaaahh</h1><p></p><img />'
		);
	}
}

class App extends Element
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
echo $e->toString();