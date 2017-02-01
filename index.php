<?php
require __DIR__ . '/DomHelper.php';
require __DIR__ . '/AbstractDom.php';
require __DIR__ . '/Element.php';
require __DIR__ . '/Component.php';

class App extends Element
{
	public function render()
	{
		return (
			"<Combo hu='ombo' />
			Viiby Висушу
			<ul>
				<li>One</li>
				<li>Two</li>
				<li>Three</li>
			</ul>"
		);
	}
}

echo 'дааа';

$e = new App;

echo $e->toString();