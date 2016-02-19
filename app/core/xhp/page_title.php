<?hh //strict

class :page-title extends :x:primitive {
  children (%phrase);
  public function stringify(): string {
    $Content = '';
    foreach($this->getChildren() as $child) {
      $Content .= :xhp::renderChild($child);
    }
    return $Content;
  }
}
