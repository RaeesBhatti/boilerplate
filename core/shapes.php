<?hh //strict
type TRouterWeb = Router<classname<Page>>;
type TRouterAPI = Router<(function():array<string, string>)>;
type User = shape(
  'id' => int
);
