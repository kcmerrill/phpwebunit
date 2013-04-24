<h1>PHPWebUnit</h1>
Love TDD? Love PHPUnit? Love the simplicity of SimpleTest?
Me too .. this is a simple wrapper utility that lets one use phpunit in a web browser, that has the same visual clues that simpletest does.

A simple green bar, or a red bar(with phpunit output) showing the issue.

Here is a youtube video describing it's usage: http://www.youtube.com/watch?v=an5ZjEVTg0g

It's usage is pretty simple too, depending on autoloading, you can get it down to two lines per test.

Prefered Way:
Use an IDE that has a live preview functionality. 

Set your base path to say: http://test.example.com/phpwebunit/index.php/ <-- You'll need to create this.
In index.php have code that would look something like this:


```
$sot = __DIR__ . '/../../' . implode('/', $test);
if(file_exists($sot)){
    new \kcmerrill\tdd\phpwebunit('', array('bin'=>'/usr/local/php5/bin/phpunit'), array('SCRIPT_FILENAME'=>$sot));
}
```

Another Way:
```
//This will test the current php file
require_once __FILE__. '/phpwebunit';
new kcmerrill\tdd\phpwebunit;


//This will test the current php's directory
require_once __FILE__. '/phpwebunit';
new kcmerrill\tdd\phpwebunit(__DIR__);
```



Here are some screenshots:
![Screenshot](https://raw.github.com/kcmerrill/phpwebunit/master/examples/screenshot.png)

[![Build Status](https://travis-ci.org/kcmerrill/phpwebunit.png?branch=master)](https://travis-ci.org/kcmerrill/phpwebunit)
