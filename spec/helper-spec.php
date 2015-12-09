<?hh

function runAppHelperTests(): void {
  describe('Helpers', function() {

    describe('organizeDependencies', function() {
      it('works as intended', function() {
        $dependencies = [
          shape(
            'src' => 'd.js',
            'name' => 'd',
            'dependencies' => ImmSet{'a', 'c'}
          ),
          shape(
            'src' => 'b.js',
            'name' => 'b',
            'dependencies' => ImmSet{'a'},
          ),
          shape(
            'src' => 'a.js',
            'name' => 'a',
            'dependencies' => ImmSet{}
          ),
          shape(
            'src' => 'c.js',
            'name' => 'c',
            'dependencies' => ImmSet{}
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
