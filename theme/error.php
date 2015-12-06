<?hh //strict
class Theme_Error extends Page {
  public function __construct(): void {

  }
  public function render(): :html {
    $errorCode = App::getInstance()->HTTPCode;
    return <html>
      <head></head>
      <body>HTTP Error {$errorCode}</body>
    </html>;
  }
}
