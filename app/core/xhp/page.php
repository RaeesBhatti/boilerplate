<?hh //strict

class :page extends :x:element {
  attribute
    Stringish lang = 'en',
    Stringish class;
  children (:page-content | :page-head | :page-script | :page-style | :page-title)*;
  private ?Page $Page = null;
  public function attachTo(Page $Page): void {
    $this->Page = $Page;
  }
  protected function render(): XHPRoot{
    $App = App::getInstance();
    $Page = $this->Page;
    invariant($Page !== null, 'No Page attached to :page');

    $Content = '';
    $Scripts = [];
    $Styles = [];
    $Header = $Page->Header;
    $Footer = $Page->Footer;

    foreach($this->getChildren() as $child) {
      if ($child instanceof :page-script) {
        if ($App->isH2 && $child->getChildren()->count()) {
          foreach($child->getChildren() as $child) {
            invariant($child instanceof :page-script, 'Child in :page-script is not :page-script');
            $Scripts[] = shape(
              'name' => $child->getName(),
              'src' => $child->getSource(),
              'dependencies' => $child->getDependencies()
            );
          }
        } else {
          $Scripts[] = shape(
            'name' => $child->getName(),
            'src' => $child->getSource(),
            'dependencies' => $child->getDependencies()
          );
        }
      } else if ($child instanceof :page-style) {
        if ($App->isH2 && $child->getChildren()->count()) {
          foreach($child->getChildren() as $child) {
            invariant($child instanceof :page-style, 'Child in :page-style is not :page-style');
            $Styles[] = shape(
              'name' => $child->getName(),
              'src' => $child->getSource(),
              'dependencies' => $child->getDependencies()
            );
          }
        } else {
          $Styles[] = shape(
            'name' => $child->getName(),
            'src' => $child->getSource(),
            'dependencies' => $child->getDependencies()
          );
        }
      } else if ($child instanceof :page-content) {
        $Content = (string) :xhp::renderChild($child);
      } else if ($child instanceof :page-title) {
        $Page->Title = $child->stringify();
      } else if ($child instanceof :page-head) {
        $Header[] = $child->renderChildren();
      }
    }

    foreach(Helper::organizeDependencies($Scripts) as $Script) {
      $Footer[] = <script defer={$Script['dependents'] === 0 && !$Script['dependencies']->count()} src={Helper::toAssets($Script['src'])}></script>;
    }
    foreach(Helper::organizeDependencies($Styles) as $Style) {
      $Header[] = <link rel="stylesheet" type="text/css" href={Helper::toAssets($Style['src'])} />;
      $App->LinkHeader .= '<'.Helper::toAssets($Style['src']).'>; rel=stylesheet, ';
    }
    header($App->LinkHeader);

    return
      <x:doctype>
        <html lang={$this->:lang}>
          <head>
            <title>{$Page->Title}</title>
            <meta charset="UTF-8" />
            {$Header}
          </head>
          <body class={$this->:class}>
            <string content={$Content} />
            {$Footer}
          </body>
        </html>
      </x:doctype>;
  }
}
