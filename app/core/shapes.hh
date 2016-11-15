<?hh //strict
type TRouterWeb = Router<classname<Page>>;
type TRouterAPI = Router<(function():array<string, mixed>)>;
type User = shape(
	'id' => int
);
