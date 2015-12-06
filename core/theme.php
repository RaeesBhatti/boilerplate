<?hh //strict
interface Theme {
  abstract const string PREFIX;
  abstract public function registerWeb(TRouterWeb $Router): void;
  abstract public function registerAPI(TRouterAPI $Router): void;
}
