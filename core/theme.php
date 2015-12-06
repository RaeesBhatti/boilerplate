<?hh //strict
abstract class Theme {
  abstract const string PREFIX;
  abstract public function __construct(): void;
  abstract public function registerWeb(TRouterWeb $Router): void;
  abstract public function registerAPI(TRouterAPI $Router): void;
}
