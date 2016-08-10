<?hh //strict

class :rimg extends :img {
	public function stringify(): string {
		$this->setAttribute('src', Helper::toAssets($this->getAttribute('src')));
		return parent::stringify();
	}
}
