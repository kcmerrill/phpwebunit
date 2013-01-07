<h1>PHPWebUnit</h1>
Love TDD? Love PHPUnit? Love the simplicity of SimpleTest?
Me too .. this is a simple wrapper utility that lets one use phpunit in a web browser, that has the same visual clues that simpletest does.

A simple green bar, or a red bar(with phpunit output) showing the issue.

It's usage is pretty simple too, depending on autoloading, you can get it down to two lines per test.

```
//This will test the current php file
require_once __FILE__. '/phpwebunit';
new kcmerrill\tdd\phpwebunit;


//This will test the current php's directory
require_once __FILE__. '/phpwebunit';
new kcmerrill\tdd\phpwebunit(__DIR__);
```

Here are some screenshots: