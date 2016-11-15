<?hh //strict

class :page-script extends :x:primitive {
	public static int $Number = 0;

	attribute
		ImmSet<string> dependencies = ImmSet{},
		Stringish name,
		Stringish src @required;
	children (:page-script)*;
	public function getName(): string {
		$name = $this->:name;
		if($name === null){
			$name = 'script_'.(++ static::$Number);
		}
		return (string) $name;
	}
	public function getDependencies(): ImmSet<string> {
		return $this->:dependencies;
	}
	public function getSource(): string {
		return (string) $this->:src;
	}
	public function stringify(): string {
		throw new Exception(':page-script should not be renderer');
	}
}
