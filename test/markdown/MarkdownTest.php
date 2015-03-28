<?php

namespace Anax\DI;

/**
 * Testing the Dependency Injector service container.
 *
 */
class MarkdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test
     *
     */
    public function testMarkdownDefaultTransform()
    {
        $text = "[I'm an inline-style link](https://www.google.com)
        ![alt text](https://github.com/adam-p/markdown-here/raw/master/src/common/images/icon48.png 'Logo Title Text 1')


        ```javascript
var s = 'JavaScript syntax highlighting';
alert(s);
```

```python
s = 'Python syntax highlighting'
print s
```

```
No language indicated, so no syntax highlighting.
But let's throw in a <b>tag</b>.
```


Colons can be used to align columns.

| Tables        | Are           | Cool  |
| ------------- |:-------------:| -----:|
| col 3 is      | right-aligned | $1600 |
| col 2 is      | centered      |   $12 |
| zebra stripes | are neat      |    $1 |

The outer pipes (|) are optional, and you don't need to make the raw Markdown line up prettily. You can also use inline Markdown.

Markdown | Less | Pretty
--- | --- | ---
*Still* | `renders` | **nicely**
1 | 2 | 3


Three or more...

---

Hyphens

***

Asterisks

___

Underscores
";
        $di = new \Michelf\Markdown();

        $res = $di->defaultTransform($text);

        $this->assertNotNull($res);
        $this->assertContainsOnly('string', array($res));

    }




}
