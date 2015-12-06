<?hh //strict
class Theme_Error implements Page {
  public static function Render(): :html {
    $errorCode = App::getInstance()->HTTPCode;
    return <html>
      <head></head>
      <body>HTTP Error {$errorCode}</body>
    </html>;
  }
}
