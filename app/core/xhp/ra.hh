<?hh //strict

class :ra extends :a {
	public function stringify(): string {
		$this->setAttribute('href', Helper::toAbsolute($this->getAttribute('href')));
		return parent::stringify();
	}
}
