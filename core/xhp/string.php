<?hh //strict

class :string extends :x:primitive {
  attribute Stringish content @required;
  category %flow, %phrase;
  public function stringify(): string {
    return (string) $this->:content;
  }
}
