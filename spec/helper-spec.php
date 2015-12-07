<?hh

function runAppHelperTests(): void {
  describe('Helpers', function() {

    describe('organizeDependencies', function() {
      it('works as intended', function() {
        $dependencies = [
          shape(
            'src' => 'd.js',
            'name' => 'd',
            'dependencies' => Set{'a', 'c'}
          ),
          shape(
            'src' => 'b.js',
            'name' => 'b',
            'dependencies' => Set{'a'},
          ),
          shape(
            'src' => 'a.js',
            'name' => 'a',
            'dependencies' => Set{}
          ),
          shape(
            'src' => 'c.js',
            'name' => 'c',
            'dependencies' => Set{}
          )
        ];
        $organized = Helper::organizeDependencies($dependencies);
        expect($organized[0]['name'])->toBe('a');
        expect($organized[1]['name'])->toBe('c');
        expect($organized[2]['name'])->toBe('d');
        expect($organized[3]['name'])->toBe('b');
      });
    });

  });
}

require(__DIR__.'/base.php');
runAppHelperTests();
