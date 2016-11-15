<?hh //strict

class :page-content extends :x:primitive {
	public function stringify(): string {
		$Content = '';
		foreach($this->getChildren() as $child) {
			$Content .= :xhp::renderChild($child);
		}
		return $Content;
	}
}
