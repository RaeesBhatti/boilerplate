<?hh //strict
class :page-head extends :x:primitive {
  public function renderChildren(): XHPRoot {
    $Content = '';
    foreach($this->getChildren() as $child) {
      $Content .= :xhp::renderChild($child);
    }
    return <string content={$Content} />;
  }
  public function stringify(): string {
    return '';
  }
}
