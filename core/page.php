<?hh //strict
abstract class Page {
  abstract public function __construct(): void;
  abstract public function render(): :html;
}
