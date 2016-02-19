<?hh //strict

class Theme_Guest_Main extends Theme {
  const string PREFIX = '/';
  public function __construct() {

  }
  public function registerWeb(TRouterWeb $Router): void {
    $Router->get([], Theme_Guest_Home::class, true);
  }
  public function registerAPI(TRouterAPI $Router): void {
    $Router->post(['api', 'account'], class_meth('Theme_Guest_API', 'Login'));
  }
}
