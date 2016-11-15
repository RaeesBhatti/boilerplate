<?hh //strict
abstract class Page {
	public string $Title = '';
	public array<Stringish> $Header = [];
	public array<Stringish> $Footer = [];
	abstract public function __construct();
	abstract public function render(): Stringish;
}
