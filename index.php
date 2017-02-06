<?php
require __DIR__ . '/DomHelper.php';
require __DIR__ . '/AbstractDom.php';
require __DIR__ . '/DomWalker.php';
require __DIR__ . '/Resolver.php';
require __DIR__ . '/Component.php';

use Elementary\Component;

Class Combo extends Component
{
	public function render()
	{
		return (
			'<h1>Yeeeaaahh</h1><p></p><img />'
		);
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