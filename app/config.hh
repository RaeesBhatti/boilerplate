<?hh //strict
enum HTTP: string as string {
  GET = 'GET';
  POST = 'POST';
  PUT = 'PUT';
  DELETE = 'DELETE';
  PATCH = 'PATCH';
}
enum AppEnv: string as string {
  API = 'API';
  UNIT_TESTS = 'UNIT_TESTS';
  MAIN = 'MAIN';
}
const string ASSETS_PREFIX = 'res.app.com/';
const string HOSTNAME = 'app.com';
