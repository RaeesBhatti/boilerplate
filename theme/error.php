<?hh //strict
class Theme_Error implements Page {
  public static function Render(): :html {
    return <html>
      <head></head>
      <body>HTTP Error {App::$HTTPCode}</body>
    </html>;
  }
}
